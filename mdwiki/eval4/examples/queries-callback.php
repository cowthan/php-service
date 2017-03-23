<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Meebio\PhpEvalConsole\Console;

$console = new Console(array(
    'assets_dir'       => '../assets',
    'queries_callback' => function () {
        return array(
            array(
                'query' => 'SELECT * FROM `users`;',
                'time'  => 0.113,
            ),
            array(
                'query' => 'SELECT * FROM `posts` JOIN `category` ON `post`.`category_id` = `category`.`id`;',
                'time'  => 0.231,
            ),
        );
    },
));
$console->boot();

