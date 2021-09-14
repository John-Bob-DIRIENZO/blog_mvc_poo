<?php


namespace Controller;


class ErrorController extends BaseController
{
    public function executeNoRoute()
    {
        $this->HTTPResponse->addHeader('HTTP/1.0 404 Not Found');
        return $this->render("Error 404", [], "Error/404");
    }
}