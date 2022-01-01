<?php

declare(strict_types=1);

const ENVIRONMENT = 'PROD';

use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;
use Controllers\ErrorController;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../mvc/controllers/error.php';

/**
 * Register the error handler
 */
$whoops = new Run();
if (ENVIRONMENT !== 'PROD') {
    $whoops->pushHandler(new PrettyPageHandler());
} else {
    $whoops->pushHandler(
        function()
        {
            /* Some code for sending a mail - ^.^ */
            $errorController = new ErrorController(
                500, 'An internal server error. The error successful logged'
            );
            $errorController->handle([]);
        }
    );
}
$whoops->register();