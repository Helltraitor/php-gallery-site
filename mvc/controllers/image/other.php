<?php

declare(strict_types=1);

namespace Controllers;

use Models\User;
use PDOException;
use Views\OtherImageView;

class OtherImageController extends ImageController
{
    public function __construct()
    {
        parent::__construct(new OtherImageView());
    }

    public function handle(array $vars)
    {
        // User could try to get access for his page as guest
        if ($_SESSION['AUTH'] && $_SESSION['USER']['ID'] === (int)$vars['id']) {
            header('Location: /person');
            exit;
        }
        // User could not exist
        try {
            $user = User::getById((int)$vars['id']);
        }
        catch (PDOException) {
            $error = new ErrorController(404, 'User is not found');
            $error->handle([]);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            // User contains id of user which made this request
            $this->view->setTitle($user->getName());
            $this->view->setImageData($user->getId());
            $this->view->render();
            // Api gate
        } else {
            $this->view->collect();
            if (!$this->view->isValid()) {
                $error = new ErrorController(400, 'Invalid post');
                $error->handle([]);
            } elseif ($this->view->isRatingSat()) {
                $error = new ErrorController(403, 'Rating already was sat by this user');
                $error->handle([]);
                exit;
            }
            $this->view->updateRating();
        }
    }
}
