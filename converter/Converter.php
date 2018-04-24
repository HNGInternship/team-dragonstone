<?php 

# Includes the autoloader for libraries installed with composer
require __DIR__ . '/vendor/autoload.php';

use Google\Cloud\Speech\SpeechClient;
use Google\Cloud\Storage\StorageClient;
use Google\Cloud\Core\ExponentialBackoff;

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

    public function __construct( $filename='audio-samples/amy.wav' ) {
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
            // return json_encode(array ('transcript' => 'Error: unsupported file format'), JSON_FORCE_OBJECT);
            echo (json_encode(array ('transcript' => 'Error: unsupported file format'), JSON_FORCE_OBJECT)); // this is for testing
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
            'encoding' => 'LINEAR16',
            'sampleRateHertz' => 16000,
        ];

        # Detects speech in the audio file
        $results = $speech->recognize(fopen($this->file, 'r'), $options);

        $this->sendResponse($results);

        /*foreach ($results as $result) {
            echo 'Transcription: ' . $result->alternatives()[0]['transcript'] . PHP_EOL;
        }*/
    }

    public function sendResponse( $result ) {
        header('Cache-Control: no-cache, must-revalidate');
        header('Content-type: application/json');

        $alternative = array();
        foreach ($results as $result) {
            $alternative .= $result->alternatives()[0];

            //printf('Transcript: %s' . PHP_EOL, $alternative['transcript']);
            //printf('Confidence: %s' . PHP_EOL, $alternative['confidence']);
        }
        $response = json_encode(array ('transcript' => $alternative['transcript']), JSON_FORCE_OBJECT);

        return $response;
    }


    /**
     * Transcribe an audio file using Google Cloud Speech API
     * Example:
     * ```
     * transcribe_async_gcs('your-bucket-name', 'audiofile.wav');
     * ```.
     *
     * @param string $bucketName The Cloud Storage bucket name.
     * @param string $onjectName The Cloud Storage object name.
     * @param string $languageCode The Cloud Storage
     *     be recognized. Accepts BCP-47 (e.g., `"en-US"`, `"es-ES"`).
     * @param array $options configuration options.
     *
     * @return string the text transcription
     */

    /*public function transcribe_async_gcs($bucketName, $objectName, $languageCode = 'en-US', $options = []) {
        
        // Create the speech client
        $speech = new SpeechClient([
            'languageCode' => $languageCode,
        ]);

        // Fetch the storage file (audio file from GCP storage)
        $storage = new StorageClient();
        $object = $storage->bucket($bucketName)->object($objectName);

        // Create the asyncronous processing of the Long audio file recognize operation
        $operation = $speech->beginRecognizeOperation(
            $object,
            $options
        );

        // Wait for the operation to complete
        $backoff = new ExponentialBackoff(10);
        $backoff->execute(function () use ($operation) {
            print('Waiting for operation to complete' . PHP_EOL);
            $operation->reload();
            if (!$operation->isComplete()) {
                throw new Exception('Job has not yet completed', 500);
            }
        });

        // Get the results
        if ($operation->isComplete()) {
            $results = $operation->results();
            // Send response to frontend
            $this->sendResponse($results);
        }
    }*/

}