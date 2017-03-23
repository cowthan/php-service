<?php

error_reporting(E_ALL);
ini_set('display_errors', true);

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\ExceptionHandler;
use Symfony\Component\Debug\ErrorHandler;

$app = new Silex\Application();

$app->register(new DerAlex\Silex\YamlConfigServiceProvider(__DIR__ . '/../config.yml'));

$app['debug'] = $app['config']['app']['debug'];

ErrorHandler::register();
ExceptionHandler::register($app['debug']);

$app->register(new Silex\Provider\TwigServiceProvider(), ['twig.path' => __DIR__ . '/../views']);

$app->register(
    new SilexPhpRedis\PhpRedisProvider(),
    [
        'redis.host' => $app['config']['redis']['host'],
        'redis.port' => $app['config']['redis']['port'],
        'redis.timeout' => $app['config']['redis']['timeout'],
        'redis.persistent' => $app['config']['redis']['persistent']
    ]
);

$defaultCode = '
<?php

// write your code here';

/**
 * @return string
 */
function getKey()
{
    return substr(md5(uniqid()), 0, rand(7, 12));
}

/**
 * @param $app
 * @return bool
 */
function getIsSaveAvailable($app)
{
    $extensionLoaded = extension_loaded('redis');
    $classExists = class_exists('Redis');

    try {
        $serverIsAlive = $app['redis']->ping();
    }
    catch (Exception $e) {
        $serverIsAlive = false;
    }

    return $extensionLoaded && $classExists && $serverIsAlive;
}

$app->get('/', function () use ($app, $defaultCode) {

    return $app['twig']->render('content.twig', [
        'output' => null,
        'code' => $defaultCode,
        'isSaveAvailable' => getIsSaveAvailable($app)
    ]);
});

$app->get('/runkit', function () use ($app, $defaultCode) {

    $sandbox = new Runkit_Sandbox([
        'safe_mode' => true,
        'open_basedir' => '/var/www/users/jdoe/',
        'allow_url_fopen' => 'false',
        'disable_functions' => 'exec,shell_exec,passthru,system'
    ]);

    echo 'runkit';
    return true;

});

$app->get('/load/{id}', function ($id) use ($app, $defaultCode) {

    $code = $app['redis']->get($id);
    $success = true;

    if (!$code) {
        $success = false;
    }

    return json_encode([
        'success' => $success,
        'code' => $code
    ]);

});

$app->post('/eval', function (Request $request) use ($app) {
    $code = $request->request->get('code');

    $clearCode = substr($code, 5);

    ob_start();
    try {
        eval($clearCode);
    }
    catch (Exception $e) {
        echo $e->getCode();
    }
    $output = ob_get_clean();

    return json_encode([
        'success' => true,
        'output' => $output
    ]);
});

$app->post('/save', function (Request $request) use ($app) {
    $code = $request->request->get('code');

    $key = getKey();

    $success = $app['redis']->setnx($key, $code);

    return json_encode([
        'success' => $success,
        'result' => $key
    ]);
});

$app->error(function (\Exception $e, $code) {
    // return new \Symfony\Component\HttpFoundation\Response('We are sorry, but something went terribly wrong.');
        echo 1;
});

$app->run();