<?php
error_reporting(E_ALL);
require_once __DIR__ . '/vendor/autoload.php';

use Meebio\PhpEvalConsole\Console;

$console = new Console();
$console->boot();