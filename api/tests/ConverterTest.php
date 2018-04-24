<?php

include "Converter.php";

use PHPUnit\Framework\TestCase;

class ConverterTest extends TestCase {

    private $formats = array( "mp3", "flac", "au", "amr", "wav", "ogg", "wma", "midi", "aif", "au", "aifc", "aiff", "webm", "awb", "aa", "aax" );

    public function getTestFormats() {
        return $this->formats;
    }

    public function testFIleFormat() {
        $file = new Converter();
        $this->assertContains($file->getExtension(), $this->getTestFormats());
    }
}