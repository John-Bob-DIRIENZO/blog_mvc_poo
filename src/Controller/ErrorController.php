<?php


namespace Controller;


class ErrorController extends BaseController
{
    public function executeNoRoute()
    {
        return $this->render("Error 404", [], "Error/404");
    }
}