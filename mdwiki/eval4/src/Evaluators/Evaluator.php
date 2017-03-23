<?php

namespace Meebio\PhpEvalConsole\Evaluators;

abstract class Evaluator implements EvaluatorInterface
{

    /**
     * @var mixed
     */
    protected $output;

    /**
     * @var array
     */
    protected $errors = array();

    /**
     * @var float
     */
    protected $executionTime = 0;

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return mixed
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @return float
     */
    public function getExecutionTime()
    {
        return $this->executionTime;
    }

    /**
     * Add error.
     *
     * @param $error
     */
    protected function addError($error)
    {
        $this->errors[] = $error;
    }

    /**
     * Reset evaluation properties.
     */
    protected function reset()
    {
        $this->output        = null;
        $this->errors        = array();
        $this->executionTime = 0;
    }
}
