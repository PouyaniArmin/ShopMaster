<?php


namespace App\Core\ViewEngine;
interface ViewRendererInterface{
    public function render(string $view,array $data=[]):string;
}