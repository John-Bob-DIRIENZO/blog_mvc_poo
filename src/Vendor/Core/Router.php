<?php


namespace Vendor\Core;


use Controller\ErrorController;

class Router
{
    public function getController()
    {
        $xml = new \DOMDocument();
        $xml->load('./Config/routes.xml');
        $routes = $xml->getElementsByTagName('route');

        isset($_GET['p']) ? $path = htmlspecialchars($_GET['p']) : $path = "";
        isset($_GET['id']) ? $id = intval($_GET['id']) : $id = null;

        foreach ($routes as $route) {
            if ($path === $route->getAttribute('p')) {
                $controllerClass = 'Controller\\' . $route->getAttribute('controller');
                $action = $route->getAttribute('action');
                return new $controllerClass($action, $id);
            }
        }

        return new ErrorController('noRoute');

    }
}