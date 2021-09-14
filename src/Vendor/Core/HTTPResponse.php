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

    // Changement par rapport à la fonction setcookie() : le dernier argument est par défaut à true
    public function setCookie($name, $value = '', $expire = 0, $path = null, $domain = null, $secure = false, $httpOnly = true)
    {
        setcookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);
    }
}