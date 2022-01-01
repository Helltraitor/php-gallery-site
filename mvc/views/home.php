<?php

declare(strict_types=1);

namespace Views;

class HomeView extends ABCView
{
    public function render()
    {
        include_once __DIR__ . '/../../public/templates/home.php';
    }
}