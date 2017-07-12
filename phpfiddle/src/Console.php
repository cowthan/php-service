<?php

namespace Meebio\PhpEvalConsole;

use Meebio\PhpEvalConsole\Authorizers\AuthorizerInterface;
use Meebio\PhpEvalConsole\Authorizers\IpAuthorizer;
use Meebio\PhpEvalConsole\Evaluators\EvalEvaluator;
use Meebio\PhpEvalConsole\Evaluators\EvaluatorInterface;
use InvalidArgumentException;

class Console
{

    /**
     * @var array
     */
    protected $config;

    /**
     * @var float
     */
    protected $consoleStart;

    /**
     * @var EvaluatorInterface;
     */
    protected $evaluator;

    /**
     * @var AuthorizerInterface[];
     */
    protected $authorizers;

    /**
     * Array with error code => string pairs.
     *
     * Used to convert error codes into human readable strings.
     *
     * @var array
     */
    protected static $errorMap = array(
        E_ERROR             => 'E_ERROR',
        E_WARNING           => 'E_WARNING',
        E_PARSE             => 'E_PARSE',
        E_NOTICE            => 'E_NOTICE',
        E_CORE_ERROR        => 'E_CORE_ERROR',
        E_CORE_WARNING      => 'E_CORE_WARNING',
        E_COMPILE_ERROR     => 'E_COMPILE_ERROR',
        E_COMPILE_WARNING   => 'E_COMPILE_WARNING',
        E_USER_ERROR        => 'E_USER_ERROR',
        E_USER_WARNING      => 'E_USER_WARNING',
        E_USER_NOTICE       => 'E_USER_NOTICE',
        E_STRICT            => 'E_STRICT',
        E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
        E_DEPRECATED        => 'E_DEPRECATED',
        E_USER_DEPRECATED   => 'E_USER_DEPRECATED',
        E_ALL               => 'E_ALL',
    );

    /**
     * Execution profile.
     *
     * @var array
     */
    protected $profile = array(
        'queries'      => array(),
        'memory'       => 0,
        'memory_peak'  => 0,
        'time'         => 0,
        'time_queries' => 0,
        'time_total'   => 0,
        'output'       => '',
        'output_size'  => 0,
        'error'        => false,
    );

    /**
     * @param array $config
     */
    public function __construct($config = array())
    {
        $this->consoleStart = microtime(true);
        $this->loadConfig($config);
        Helper::$templateDefaultDir = $this->getConfigItem('views_path');

        $evaluator = $this->getConfigItem('evaluator');
        if (!($evaluator instanceof EvaluatorInterface)) {
            throw new InvalidArgumentException('Evaluator must implement EvaluatorInterface');
        }
        $this->evaluator = $evaluator;

        $authorizers = $this->getConfigItem('authorizer');
        if (is_null($authorizers)) {
            $authorizers = array();
        } elseif(!is_array($authorizers)) {
            $authorizers = array($authorizers);
        }
        foreach ($authorizers as $authorizer) {
            if (!($authorizer instanceof AuthorizerInterface)) {
                throw new InvalidArgumentException('Authorizer must implement AuthorizerInterface');
            }
        }
        $this->authorizers = $authorizers;
    }

    /**
     * @return array
     */
    protected function getDefaultConfig()
    {
        return array(
            'assets_dir'            => 'assets',
            'views_path'            => __DIR__ . '/Views',
            'console_view_path'     => __DIR__ . '/Views/console.php',
            'execute_url'           => null, //"http://cowthan.com:8089/mdwiki/eval4/index.php",
            'evaluator'             => new EvalEvaluator(),
            'authorizer'            => new IpAuthorizer(null, null), //(array('::1', '127.0.0.1')),
            'queries_callback'      => null,
            'post_execute_callback' => null,
        );
    }

    protected function loadConfig($config = array())
    {
        $this->config = array_merge($this->getDefaultConfig(), $config);

        if (is_null($this->config['execute_url'])) {
            $this->config['execute_url'] = $this->detectExecuteUrl();
        }
    }

    /**
     * @return array
     */
    protected function getConfig()
    {
        return $this->config;
    }

    /**
     * @param $key
     * @return mixed
     */
    protected function getConfigItem($key)
    {
        return array_key_exists($key, $this->config) ? $this->config[$key] : null;
    }

    protected function authorize()
    {
        foreach ($this->authorizers as $authorizer) {
            if (!$authorizer->isAuthorized()) {
                $authorizer->renderError();
            }
        }
    }

    /**
     * Start application. Parameter $action indicates if application page should be rendered or code executed.
     * Keeping $action null will make application to guess which action to call.
     *
     * @param string|null $action
     */
    public function boot($action = null)
    {
        if (is_null($action)) {
            if ($this->detectMethod() === 'post') {
                $this->execute();
            } else {
                $this->renderView();
            }
        } elseif ($action === 'console') {
            $this->renderView();
        } elseif ($action === 'execute') {
            $this->execute();
        }
    }

    /**
     * @return string
     */
    protected function detectMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    /**
     * Render application page.
     */
    public function renderView()
    {
        $this->authorize();

        $consoleViewPath = $this->getConfigItem('console_view_path');

        Helper::$sharedTemplateVars['config'] = $this->getConfig();

        echo Helper::template($consoleViewPath);
    }

    /**
     * Execute code and return current profile.
     */
    public function execute()
    {
        $this->authorize();

        if (!$this->evaluator->evaluate($this->getInput())) {
            foreach ($this->evaluator->getErrors() as $error) {
                $this->addProfile('error', $error);
            }
        }
        $output = $this->evaluator->getOutput();

        // Extend the profile
        $this->addProfile(
            array(
                'time'        => round($this->evaluator->getExecutionTime() * 1000),
                'output'      => $output,
                'output_size' => strlen($output),
            )
        );

        if ($this->getConfigItem('queries_callback')) {
            $this->addProfile('queries', $this->getConfigItem('queries_callback'));
        }

        $this->postExecuteCallback($this->getConfigItem('post_execute_callback'));

        $this->returnJson($this->getProfile());
    }

    /**
     * @return string
     */
    protected function detectExecuteUrl()
    {
//        return 'http'
//            . (empty($_SERVER['HTTPS']) ? '' : 's')
//            . '://' . $_SERVER['SERVER_NAME']
//            . ':' . $_SERVER['SERVER_PORT']
//            . $_SERVER['REQUEST_URI'];
        return $_SERVER['REQUEST_URI'];
    }

    /**
     * @return string
     * @throws InvalidArgumentException
     */
    protected function getInput()
    {
        if (!isset($_POST['code']) || !is_string($_POST['code'])) {
            throw new InvalidArgumentException('Code not sent or invalid');
        }

        return $_POST['code'];
    }

    protected function returnJson($data)
    {
        header('Content-type: application/json');
        echo json_encode($data);
    }

    /**
     * Add one or multiple fields into profile.
     *
     * @param mixed $property Property name, or an array of name => value pairs.
     * @param mixed $value Property value.
     */
    protected function addProfile($property, $value = null)
    {
        if (is_array($property)) {
            foreach ($property as $key => $value) {
                $this->addProfile($key, $value);
            }
            return;
        }

        // Normalize properties
        $normalizerName = 'normalize' . ucfirst($property);
        if (method_exists($this, $normalizerName)) {
            $value = call_user_func(array($this, $normalizerName), $value);
        }

        $this->profile[$property] = $value;
    }

    /**
     * Return current profile.
     *
     * @return array
     */
    protected function getProfile()
    {
        // Total execution time by queries
        $timeQueries = 0;
        foreach ($this->profile['queries'] as $k => $query) {
            $timeQueries += $query['time'];
            $this->profile['queries'][$k]['time'] = round($query['time'], 3);
        }

        // Extend the profile with current data
        $this->addProfile(
            array(
                'memory'       => memory_get_usage(true),
                'memory_peak'  => memory_get_peak_usage(true),
                'time_queries' => round($timeQueries, 3),
                'time_total'   => round((microtime(true) - $this->consoleStart) * 1000),
            )
        );

        return $this->profile;
    }

    /**
     * Normalize error profile.
     *
     * @param mixed $error Error object or array.
     * @return array Normalized error array.
     */
    protected function normalizeError($error)
    {
        // Set human readable error type
        if (isset($error['type']) and isset(static::$errorMap[$error['type']])) {
            $error['type'] = static::$errorMap[$error['type']];
        }

        // Validate and return the error
        if (isset($error['type'], $error['message'], $error['file'], $error['line'])) {
            return $error;
        } else {
            return $this->profile['error'];
        }
    }

    /**
     * Normalize queries.
     *
     * @param callback|null $queriesCallback
     * @return array
     */
    protected function normalizeQueries($queriesCallback)
    {
        $queries = array();

        if (is_callable($queriesCallback)) {
            $queries = $queriesCallback();
        }

        return $queries;
    }

    /**
     * Execute post execute callback.
     * Can be used to perform some actions after code evaluation, but before returning result.
     *
     * @param callback|null $postExecuteCallback
     */
    protected function postExecuteCallback($postExecuteCallback)
    {
        if (is_callable($postExecuteCallback)) {
            $postExecuteCallback();
        }
    }
}
