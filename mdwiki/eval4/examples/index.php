<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Meebio\PhpEvalConsole\Console;

$console = new Console(array(
    'assets_dir' => '../assets',
));
$console->boot();
