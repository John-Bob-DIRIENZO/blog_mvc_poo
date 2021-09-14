<?php


namespace Controller;


use Vendor\Core\HTTPRequest;
use Vendor\Core\HTTPResponse;

abstract class BaseController
{
    protected $HTTPRequest;
    protected $HTTPResponse;
    protected $params;
    protected $template = __DIR__ . './../Views/template.php';
    protected $viewsDir = __DIR__ . './../Views/';

    /**
     * BaseController constructor.
     * @param string $action
     * @param null $id
     */
    public function __construct(string $action, array $params = [])
    {
        $this->HTTPRequest = new HTTPRequest();
        $this->HTTPResponse = new HTTPResponse();
        $this->params = $params;

        $method = 'execute' . ucfirst($action);
        if (!is_callable([$this, $method])) {
            throw new \RuntimeException('L\'action "' . $method . '" n\'est pas définie sur ce module');
        }
        $this->$method();
    }

    /**
     * @param string $title
     * @param array $vars
     * @param string $view
     * @return mixed
     */
    public function render(string $title, array $vars, string $view)
    {
        $view = $this->viewsDir . $view . '.view.php';
        ob_start();
        require $view;
        $content = ob_get_clean();
        return require $this->template;

    }

    public function renderJSON($content)
    {
        $this->HTTPResponse->addHeader('Content-Type: application/json');
        echo json_encode($content, JSON_PRETTY_PRINT);
    }
}