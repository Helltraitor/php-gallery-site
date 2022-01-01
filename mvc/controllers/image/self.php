<?php

declare(strict_types=1);

namespace Controllers;

use Models\Image;
use PDOException;
use Views\SelfImageView;

class SelfImageController extends ImageController
{
    public function __construct()
    {
        parent::__construct(new SelfImageView());
    }

    public function handle(array $vars)
    {
        if (!$_SESSION['AUTH']) {
            header('Location: /login?next=/person');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->view->render();
        } else {
            // Required to change these lines in php.ini
            // [703] post_max_size = >3M (8M by default)
            // [846] file_uploads = On (On by default)
            // [855] upload_max_filesize = 3M (2M by default)
            // [858] max_file_uploads = 1 (20 by default)
            $this->view->collect();
            $form = $this->view->getFormData();
            if ($form['error']) {
                $errorHandler = new ErrorController(
                    500,
                    'Internal server error while upload file. Error code: '
                    . (string)$form['error']
                );
                $errorHandler->handle([]);
                exit;
            }
            if (!$this->view->isValidFile()) {
                $this->view->render();
                exit;
            }
            try {
                $this->saveFile($form);
                header('Location: /person');
            } catch (PDOException) {
                $error = new ErrorController(
                    500, 'Internal server error while use database'
                );
                $error->handle([]);
            }
        }
    }

    /**
     * This function used when data valid and file exists in file system
     * (successful uploaded)
     *
     * @param array $form An array which contains 'description' and 'tmp'
     * @throws PDOException
     */
    public function saveFile(array $form)
    {
        // For now allow to save only jpeg files
        $image = new Image($_SESSION['USER']['ID'], $form['description'], $form['tmp']);
        $image->save(); // throws
    }
}