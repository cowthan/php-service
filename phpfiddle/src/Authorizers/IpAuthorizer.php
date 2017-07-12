<?php

namespace Meebio\PhpEvalConsole\Authorizers;

class IpAuthorizer extends Authorizer
{

    /**
     * @var null|array
     */
    protected $allowed;

    /**
     * @var null|array
     */
    protected $disallowed;

    /**
     * @param null|string|array $allowed
     * @param null|string|array $disallowed
     */
    public function __construct($allowed = null, $disallowed = null)
    {
        $this->allowed    = is_string($allowed) ? array($allowed) : $allowed;
        $this->disallowed = is_string($disallowed) ? array($disallowed) : $disallowed;
    }

    public function isAuthorized()
    {
        $ip = $this->getIp();

        return $this->checkAgainstAllowed($ip) && $this->checkAgainstDisallowed($ip);
    }

    /**
     * @return string|null
     */
    protected function getIp()
    {
        if (isset($_SERVER['REMOTE_ADDR'])) {
            return $_SERVER['REMOTE_ADDR'];
        }

        return null;
    }

    /**
     * @param string $ip
     * @return bool
     */
    protected function checkAgainstAllowed($ip)
    {
        if (is_null($this->allowed)) {
            return true;
        }

        if (is_null($ip)) {
            return false;
        }

        return in_array($ip, $this->allowed, true);
    }

    /**
     * @param string $ip
     * @return bool
     */
    protected function checkAgainstDisallowed($ip)
    {
        if (is_null($this->disallowed)) {
            return true;
        }

        if (is_null($ip)) {
            return false;
        }

        return !in_array($ip, $this->disallowed, true);
    }
}
