<?php

$file = $_POST['audio'];

require 'Converter.php';

$con = new Converter($file);

$con->beginWork();