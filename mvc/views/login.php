<?php

declare(strict_types=1);

namespace Views;

class LoginView extends ABCView
{
    /**
     * @var string $next Contains url to the next page after login.
     *      Used by form and sat by controller
     */
    private string $next = '/';

    public function setNext(string $next)
    {
        $this->next = $next;
    }

    public function render()
    {
        include_once __DIR__ . '/../../public/templates/login.php';
    }
}