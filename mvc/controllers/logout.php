<?php

declare(strict_types=1);

namespace Controllers;

use Models\Session;
use PDOException;

class LogoutController extends ABCController
{
    public function __construct()
    {
        parent::__construct(null);
    }

    public function handle(array $vars)
    {
        if (!array_key_exists('UUID', $_COOKIE)) {
            nativeDestroySession();
        }
        try {
            $session = Session::getByUUID($_COOKIE['UUID']);
            $session->destroy();
        } catch (PDOException) {
            nativeDestroySession();
        } finally {
            header('Location: /');
        }
    }
}