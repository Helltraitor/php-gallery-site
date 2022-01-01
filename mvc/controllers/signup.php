<?php

declare(strict_types=1);

namespace Controllers;

use PDOException;
use Models\{Session, User};
use Views\SignupView;

class SignupController extends ABCController
{
    public function __construct()
    {
        parent::__construct(new SignupView());
    }

    /**
     * Tries to create user. Call the error page handler if error occurred
     *
     * @param string $name Name of registered user
     * @param string $email Email of this user
     * @param string $passwordHash Hash created by password_hash function
     * @return User
     */
    public function createUser(string $name, string $email, string $passwordHash): User
    {
        try {
            return User::create($name, $email, $passwordHash);
        } catch (PDOException) {
            $error = new ErrorController(
                500, 'Internal server error while save user data'
            );
            $error->handle([]);
            exit;
        }
    }

    public function handle(array $vars)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->view->collect();
            if ($this->view->isValidSignup()) {
                $form = $this->view->getFormData();
                $new_user = $this->createUser(
                    $form['name'],
                    $form['email'],
                    password_hash($form['password'], PASSWORD_DEFAULT)
                );
                $session = Session::create($new_user->getId());
                $session->register();
                header('Location: /');
                exit;
            }
        }
        $this->view->render();
    }
}