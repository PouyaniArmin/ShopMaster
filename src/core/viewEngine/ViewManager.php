<?php


namespace App\Core\ViewEngine;
use App\Core\ViewEngine\ViewRendererInterface;

class ViewManager
{
    private ViewRendererInterface $viewRendererInterface;
    public function setRenderer(ViewRendererInterface $viewRendererInterface): void
    {
        $this->viewRendererInterface = $viewRendererInterface;
    }

    public function render(string $view, array $date):string
    {
      return  $this->viewRendererInterface->render($view, $date);
    }
}
