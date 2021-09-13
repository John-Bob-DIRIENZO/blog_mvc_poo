<?php


namespace Controller;


abstract class BaseController
{
    protected $id;
    protected $template = __DIR__ . './../Views/template.php';
    protected $viewsDir = __DIR__ . './../Views/';

    public function __construct(string $action, $id = null)
    {
        $this->id = $id;

        $method = 'execute' . ucfirst($action);
        if (!is_callable([$this, $method])) {
            throw new \RuntimeException('L\'action "' . $this->action . '" n\'est pas définie sur ce module');
        }
        $this->$method();
    }

    public function render(string $title, array $vars, string $view)
    {
        $view = $this->viewsDir . $view . '.view.php';
        ob_start();
        $title = $title;
        $vars = $vars;
        require $view;
        $content = ob_get_clean();
        return require $this->template;

    }
}