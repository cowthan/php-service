<?php

namespace Meebio\PhpEvalConsole\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Meebio\PhpEvalConsole\Authorizers\IpAuthorizer;
use Meebio\PhpEvalConsole\Console;
use Meebio\PhpEvalConsole\Evaluators\EvalEvaluator;

class PhpEvalConsoleLaravelServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Console::class, function ($app) {
            return new Console($this->consoleConfig());
        });
    }

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['db']->connection()->enableQueryLog();

        $this->publishes([
            __DIR__ . '/../../assets' => public_path('vendor/php-eval-console'),
        ], 'public');

        if (!$this->app->routesAreCached()) {
            $this->app['router']->get(
                'console',
                [
                    'as' => 'phpEvalConsole.view',
                    function (Console $console) {
                        $console->renderView();
                    },
                ]
            );

            $this->app['router']->post(
                'console',
                [
                    'as' => 'phpEvalConsole.execute',
                    function (Console $console) {
                        $console->execute();
                    },
                ]
            );
        }
    }

    /**
     * @return array
     */
    protected function consoleConfig()
    {
        return [
            'assets_dir'       => asset('vendor/php-eval-console'),
            'execute_url'      => route('phpEvalConsole.execute'),
            'evaluator'        => new EvalEvaluator(),
            'authorizer'       => new IpAuthorizer(['::1', '127.0.0.1']),
            'queries_callback' => function () {
                return $this->queriesCallback();
            },
        ];
    }

    /**
     * @return array
     */
    protected function queriesCallback()
    {
        $queries = $this->app['db']->getQueryLog();

        $normalizedQueries = array_map(function ($query) {
            return [
                'query' => $this->interpolateQuery($query['query'], $query['bindings']),
                'time'  => $query['time'],
            ];
        }, $queries);

        return $normalizedQueries;
    }

    /**
     * Replaces any parameter placeholders in a query with the value of that
     * parameter. Useful for debugging. Assumes anonymous parameters from
     * $params are are in the same order as specified in $query
     *
     * @param string $query The sql query with parameter placeholders
     * @param array $params The array of substitution parameters
     * @return string The interpolated query
     */
    public function interpolateQuery($query, $params)
    {
        $keys   = [];
        $values = $params;

        # build a regular expression for each parameter
        foreach ($params as $key => $value) {
            if (is_string($key)) {
                $keys[] = '/:' . $key . '/';
            } else {
                $keys[] = '/[?]/';
            }

            if (is_string($value)) {
                $values[$key] = "'" . $value . "'";
            }

            if (is_array($value)) {
                $values[$key] = "'" . implode("','", $value) . "'";
            }

            if (is_null($value)) {
                $values[$key] = 'NULL';
            }
        }

        $query = preg_replace($keys, $values, $query, 1, $count);

        return $query;
    }
}
