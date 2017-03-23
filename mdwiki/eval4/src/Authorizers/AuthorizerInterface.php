<?php

namespace Meebio\PhpEvalConsole\Authorizers;

interface AuthorizerInterface
{

    /**
     * Check if user is authorized to access application.
     *
     * @return bool
     */
    public function isAuthorized();

    /**
     * Render error for unauthorized user.
     */
    public function renderError();
}
