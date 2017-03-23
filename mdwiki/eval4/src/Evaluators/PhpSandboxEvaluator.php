<?php

namespace Meebio\PhpEvalConsole\Evaluators;

use PHPSandbox\PHPSandbox;

class PhpSandboxEvaluator extends Evaluator
{

    /**
     * @var PHPSandbox
     */
    protected $sandbox;

    /**
     * @param PHPSandbox|null $sandbox
     */
    public function __construct(PHPSandbox $sandbox = null)
    {
        if (is_null($sandbox)) {
            $sandbox = new PHPSandbox;
        }

        $sandbox->capture_output = true;

        $this->sandbox = $sandbox;
    }

    public function evaluate($code)
    {
        $this->reset();

        try {

            $this->output        = $this->sandbox->execute($code);
            $this->executionTime = $this->sandbox->execution_time;
            $success             = true;

            if (!is_null($this->sandbox->get_last_error())) {
                $this->addError($this->sandbox->get_last_error());
            }
        } catch (\Exception $e) {
            $this->addError(
                array(
                    'type'    => get_class($e),
                    'message' => $e->getMessage(),
                    'file'    => $e->getFile(),
                    'line'    => $e->getLine(),
                )
            );
            $success = false;
        }

        return $success;
    }
}
