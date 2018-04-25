<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

# Includes the autoloader for libraries installed with composer
require __DIR__ . '/vendor/autoload.php';

use Google\Cloud\Speech\SpeechClient;
use Google\Cloud\Storage\StorageClient;
use Google\Cloud\Core\ExponentialBackoff;

putenv('GOOGLE_APPLICATION_CREDENTIALS=config/api-key.json');

/**
 * Converter.php class focusing on GCP, Methods can be abstracted for AWS too
 * 
 * Step 1: recieves file through constructor
 *      - confirms file typr to be a supported audio file
 * Step 2: Sends file to GCP
 *      - Awaits transcription
 * Step 3: Return Json encoded response to the Frontend
 */
 
class Converter {

    public $file;
    public $projectId = "dragonstone-1524506773397";

    public function __construct( $filename='audio-samples/au4.flac' ) {
        $this->file = $filename;
    }
    
    private $formats = array( 
        "mp3", "flac", "au", "amr", "wav", 
        "ogg", "wma", "midi", "aif", "au",
        "aifc", "aiff", "webm", "awb", "aa", "aax"
     );

    public function getFormats() {
        return $this->formats;
    }

    public function getExtension() {
        $info = new SplFileInfo($this->file);
        return $info->getExtension();
    }

    public function beginWork() {
        // check if file format is valid
        if (in_array($this->getExtension(), $this->getFormats())) {
            // perform transcribtion  $this->transcribe_async_gcs();
            $this->trancribe();
            //echo (json_encode(array ('transcript' => 'works'), JSON_FORCE_OBJECT)); // this is for testing
        }
        else {
            return json_encode(array ('google' => 'Error: unsupported file format'), JSON_FORCE_OBJECT);
            //echo (json_encode(array ('transcript' => 'Error: unsupported file format'), JSON_FORCE_OBJECT)); // this is for testing
        }
    }

    public function trancribe() {
        # Instantiates a client
        $speech = new SpeechClient([
            'projectId' => $this->projectId,
            'languageCode' => 'en-US',
        ]);

        # The audio file's encoding and sample rate
        $options = [
            'encoding' => 'FLAC',
            //'sampleRateHertz' => 44100,
        ];

        # Detects speech in the audio file
        $results = $speech->recognize(fopen($this->file, 'r'), $options);
                
        $this->sendResponse($results);

    }

    public function sendResponse( $results ) {

        $alternative = '';
        foreach ($results as $result) {
            $alternative .= $result->alternatives()[0]['transcript'];
        }
        $response = json_encode(array ('google' => $alternative), JSON_FORCE_OBJECT);
        header('Content-Type: application/json');
        echo ($response);
    }

}