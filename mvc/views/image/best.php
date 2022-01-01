<?php

declare(strict_types=1);

namespace Views;

use Models\Image;

class BestImageView extends ImageView
{
    public function __construct()
    {
        if ($_SESSION['AUTH']) {
            $this->user = $_SESSION['USER']['ID'];
        }
        $this->type = 'best';
        $this->title = 'Best';
        $this->images = json_encode(Image::getBestImagesData());
    }
}