<?php

declare(strict_types=1);

namespace Views;

class ErrorView extends ABCView
{
    /**
     * @var int $code Contains HTTP error code (404, 501 for e.g.)
     */
    protected int $code;

    /**
     * @var string $text Contains safe HTTP text for putting itself into http page
     */
    protected string $text;

    public function __construct(int $code, string $text)
    {
        $this->code = $code;
        $this->text = $text;
    }

    public function render()
    {
        include_once __DIR__ . '/../../public/templates/error.php';
    }
}
