<?php

declare(strict_types=1);

namespace Views;

use Models\Image;

class LatestImageView extends ImageView
{
    public function __construct()
    {
        if ($_SESSION['AUTH']) {
            $this->user = $_SESSION['USER']['ID'];
        }
        $this->type = 'latest';
        $this->title = 'Latest';
        $this->images = json_encode(Image::getLatestImagesData());
    }
}