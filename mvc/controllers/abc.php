<?php

declare(strict_types=1);

namespace Controllers;

use Views\ABCView;

/**
 * Class ABCController is abstract basic class (ABC) for all controllers
 * in the mvc system
 *
 * @package Controllers contains all controllers of the site
 */
abstract class ABCController
{
    /**
     * @var ?ABCView $view Contains subclass ABCView instance for render site page
     */
    protected ?ABCView $view;

    /**
     * ABCController constructor
     *
     * @param ?ABCView $view ABCView subclass instance which will use
     *     for render or null if page implements api logic
     */
    protected function __construct(?ABCView $view)
    {
        $this->view = $view;
    }

    /**
     * This function is the entry point of controller instance which accepts
     * required outer scope variables and uses views and models classes for
     * building page
     *
     * @param array $vars An array which could be used by controller class
     *      implementation
     */
    public function handle(array $vars)
    {
        $this->view->render();
    }
}