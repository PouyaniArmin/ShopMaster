<?php

namespace App\Core;

use App\Core\ViewEngine\DefaultViewRenderer;
use App\Core\ViewEngine\ViewManager;

class Controller
{
 
    private ViewManager $view_manager;

    public function __construct()
    {
        $this->view_manager=new ViewManager;

    }
    public function view(string $view,array $data=[]){
        $this->view_manager->setRenderer(new DefaultViewRenderer);
        return $this->view_manager->render($view,$data);
    }
}
