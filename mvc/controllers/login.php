<?php

declare(strict_types=1);

namespace Controllers;

use Models\{Session, User};
use PDOException;
use Views\LoginView;

class LoginController extends ABCController
{
    public function __construct()
    {
        parent::__construct(new LoginView());
    }

    /**
     * login function used for log user in the site. When errors occurred make
     * redirect on the login page
     */
    protected function login(string $next)
    {
        $email = array_key_exists('email', $_POST) ? $_POST['email'] : '';
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        $password = array_key_exists('password', $_POST) ? $_POST['password'] : '';

        if (!$email || !$password) {
            header('Location: /login?next=' . $next);
            exit;
        }
        try {
            $user = User::getByLogin($email, $password);
            $session = Session::create($user->getId());
            $session->register();
        } catch (PDOException) {
            header('Location: /login?next=' . $next);
            exit;
        }
    }

    public function handle(array $vars)
    {
        $next = array_key_exists('next', $_REQUEST) ? $_REQUEST['next'] : '/';
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->view->setNext($next);
            $this->view->render();
        } else {
            $this->login($next);
            header('Location: ' . $next);
            exit;
        }
    }
}