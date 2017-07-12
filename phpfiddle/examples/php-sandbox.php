<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Meebio\PhpEvalConsole\Console;
use Meebio\PhpEvalConsole\Evaluators\PhpSandboxEvaluator;
use PHPSandbox\PHPSandbox;

$console = new Console(array(
    'assets_dir' => '../assets',
    'evaluator'  => new PhpSandboxEvaluator(new PHPSandbox()),
));
$console->boot();
