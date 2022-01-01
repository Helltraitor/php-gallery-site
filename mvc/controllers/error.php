<?php

declare(strict_types=1);

namespace Controllers;

use Views\ErrorView;

class ErrorController extends ABCController
{
    /**
     * ErrorController constructor
     *
     * @param int $code HTTP error code which will be used by ErrorView
     * @param string $text Safe HTTP text of the error which will be used
     *     by ErrorView
     */
    public function __construct(int $code, string $text)
    {
        parent::__construct(new ErrorView($code, $text));
    }
}