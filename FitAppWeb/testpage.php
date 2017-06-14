<?php

require 'fitbitphp.php';

const FITBIT_KEY= '227V4K';
const FITBIT_SECRET='275689f032b27e5f4201dc3612957f99';

$fitbitkey = $_REQUEST['FITBIT_KEY'];
$fitbitSecret = $_REQUEST['FITBIT_SECRET'];

$fitbit = new FitBitPHP($fitbitkey, $fitbitSecret);

$fitbit->resetSession();
$fitbit->initSession('http://kaleighscruggs.com/FitAppWeb/welcome.php');

$fitbit->resetSession();
$xml = $fitbit->getProfile();

echo '<pre>';
var_dump($xml);
echo "</pre>";