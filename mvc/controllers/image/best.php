<?php

declare(strict_types=1);

namespace Controllers;

use Views\BestImageView;

class BestImageController extends ImageController
{
    public function __construct()
    {
        parent::__construct(new BestImageView());
    }
}
