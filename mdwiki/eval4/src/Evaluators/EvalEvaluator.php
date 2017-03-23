<?php

namespace Meebio\PhpEvalConsole\Evaluators;

class EvalEvaluator extends Evaluator
{

    public function evaluate($code)
    {
        $this->reset();

        try {
            ob_start();

            $evalClosure = function () use ($code) {
                return eval($code);
            };

            // unbind this if closure method bindTo exists
            if (method_exists($evalClosure, 'bindTo')) {
                $evalClosure = $evalClosure->bindTo(null);
            }

            $executionStart = microtime(true);
            $returnValue    = $evalClosure();
            $executionEnd   = microtime(true);
            $success        = true;

            $this->executionTime = $executionEnd - $executionStart;
            $this->output        = ob_get_contents();

            ob_end_clean();

            if (!is_null(error_get_last())) {
                $this->addError(error_get_last());
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
