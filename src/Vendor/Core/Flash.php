<?php


namespace Vendor\Core;


class Flash
{
    public static function setFlash(string $message): void
    {
        $_SESSION['flash'] = htmlspecialchars($message);
    }

    public static function hasFlash(): bool
    {
        return isset($_SESSION['flash']);
    }

    public static function getFlash()
    {
        if (isset($_SESSION['flash'])) {
            $message = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $message;
        }
    }
}