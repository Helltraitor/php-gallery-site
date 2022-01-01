<?php

declare(strict_types=1);

namespace Controllers;

use Views\HomeView;

class HomeController extends ABCController
{
    public function __construct()
    {
        parent::__construct(new HomeView());
    }
}