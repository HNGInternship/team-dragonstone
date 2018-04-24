<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form method="post" enctype="multipart/form-data">
        Upload a File:
        <input type="file" name="myfile" id="fileToUpload">
        <input type="submit" name="submit" value="Upload File Now" >
    </form>
    <?php
        # Includes the autoloader for libraries installed with composer
        require __DIR__ . '/vendor/autoload.php';
        error_reporting(0);

        $currentDir = getcwd();
        $uploadDirectory = "/uploads/";
        $errors = []; // Store all foreseen and unforseen errors here
        $fileExtensions = [ 'mp3', 'flac', 'ogg', 'mpeg', 'wav' ]; // Get all the file extensions
        $fileName = $_FILES['myfile']['name'];
        $fileSize = $_FILES['myfile']['size'];
        $fileTmpName  = $_FILES['myfile']['tmp_name'];
        $fileType = $_FILES['myfile']['type'];
        $tmp = explode('.',$fileName);
        $fileExtension = strtolower(end($tmp));
        $uploadPath = $currentDir . $uploadDirectory . basename($fileName); 
        if (isset($_POST['submit'])) {
            if (! in_array($fileExtension,$fileExtensions)) {
                $errors[] = "This file extension is not allowed. Please upload an audio file";
            }
            if (empty($errors)) {
                $didUpload = move_uploaded_file($fileTmpName, $uploadPath);
                if ($didUpload) {
                    echo "The file " . basename($fileName) . " has been uploaded";
                } else {
                    echo "An error occurred somewhere. Try again or contact the admin";
                }
            } else {
                foreach ($errors as $error) {
                    echo $error . "These are the errors" . "\n";
                }
            }
        }

        $uploadPathFlac = __DIR__ . '/uploads/flac_audio.flac';

        $ffmpeg = FFMpeg\FFMpeg::create();
        $audio = $ffmpeg->open($uploadPath);

        $format = new FFMpeg\Format\Audio\Flac();
        $format->on('progress', function ($audio, $format, $percentage) {
            echo "$percentage % transcoded";
        });

        $format
            ->setAudioChannels(2)
            ->setAudioKiloBitrate(256);

        $audio->save($format, $uploadPathFlac);
        
        

        // # Imports the Google Cloud client library
        // use Google\Cloud\Speech\SpeechClient;

        // # Your Google Cloud Platform project ID
        // $projectId = 'dragonstone-1524506773397';

        // # Instantiates a client
        // $speech = new SpeechClient([
        //     'projectId' => $projectId,
        //     'languageCode' => 'en-US',
        // ]);

        // # The name of the audio file to transcribe
        // $fileName = __DIR__.'/uploads/flac_audio.flac';

        // # The audio file's encoding and sample rate
        // $options = [
        //     'encoding' => 'FLAC',
        // ];

        // # Detects speech in the audio file
        // $results = $speech->recognize(fopen($fileName, 'r'), $options);

        // foreach ($results as $result) {
        //     echo 'Transcription: ' . $result->alternatives()[0]['transcript'] . PHP_EOL;
        // }
    ?>
</body>
</html>