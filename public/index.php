<?php
use App\Classes\SimpleApp;

require_once '../vendor/autoload.php';
require_once '../src/utils.php';

chdir('../');
SimpleApp::init();
SimpleApp::run();