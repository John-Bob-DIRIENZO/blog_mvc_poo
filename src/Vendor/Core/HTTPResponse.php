<?php


namespace Vendor\Core;


class HTTPResponse
{
    public function addHeader($header)
    {
        header($header);
    }

    public function redirect($location, int $code = 0, bool $replace = true)
    {
        header('Location: ' . $location, $replace, $code);
        exit;
    }

    public function unauthorized(string $message): void
    {
        $this->addHeader('WWW-Authenticate: Basic realm="This area needs authentication"');
        $this->addHeader('HTTP/1.0 401 Unauthorized');
        exit($message);
    }

    // Changement par rapport à la fonction setcookie() : le dernier argument est par défaut à true
    public function setCookie($name, $value = '', $expire = 0, $path = null, $domain = null, $secure = false, $httpOnly = true)
    {
        setcookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);
    }
}