<?php

require_once 'vendor/autoload.php';

use Backend\Api;

$api = new Api($_GET);
$api->init();