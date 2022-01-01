<?php

declare(strict_types=1);

namespace Controllers;

use Models\Image;
use PDOException;

class GetImageController extends ImageController
{
    public function __construct()
    {
        parent::__construct(null);
    }

    /**
     * Function returns an image in HTTP page which will be used by frontend
     *
     * @param array $vars Vars variable must contains the int 'user' and int 'id'.
     *      This must be guarantied by FastRoute
     */
    public function handle(array $vars)
    {
        try {
            if (!Image::exists((int)$vars['id'])) {
                $error = new ErrorController(404, 'File not found');
                $error->handle([]);
                exit;
            }
        } catch (PDOException) {
            $error = new ErrorController(500, 'Internal server error while use database');
            $error->handle([]);
            exit;
        }
        // According to image implementation, we use db directly.
        // Otherwise we need to fetch file from db (no matter what)
        // and send it here, where we will send it to user
        header('Content-Type: image/jpeg');
        readfile(
            __DIR__
            . '/../../../image-db/'
            . (string)$vars['user'] . '/'
            . (string)$vars['id'] . '.jpeg'
        );
    }
}