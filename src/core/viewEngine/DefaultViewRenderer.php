<?php


namespace App\Core\ViewEngine;

use App\Core\Application;
use ViewManager;

class DefaultViewRenderer implements ViewRendererInterface
{

    public string $layout = 'main';

    public function setLayout(string $layout): void
    {
        $this->layout = $layout;
    }

    public function render(string $view, array $data = []): string
    {

        $viewContent = $this->renderOnlyView($view, $data);
        $layoutContetnt = $this->renderLayout();
        return str_replace("{{content}}", $viewContent, $layoutContetnt);
    }

    private function renderLayout()
    {
        ob_start();
        require_once Application::$app->basePath . "/../src/views/layouts/{$this->layout}.php";
        return ob_get_clean();
    }
    private function renderOnlyView(string $view, array $data)
    {
        extract($data);
        ob_start();
        require_once Application::$app->basePath . "/../src/views/$view.php";
        return ob_get_clean();
    }
}
