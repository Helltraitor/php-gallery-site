<?php

declare(strict_types=1);

namespace Controllers;

use Views\LatestImageView;

class LatestImageController extends ImageController
{
    public function __construct()
    {
        parent::__construct(new LatestImageView());
    }
}