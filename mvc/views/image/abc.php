<?php

declare(strict_types=1);

namespace Views;

/**
 * Class ImageView is abstract basic class for all Image views
 */
abstract class ImageView extends ABCView
{
    /**
     * @var int $user Id of current user
     */
    protected int $user = 0;

    /**
     * @var string $images JSON string which used for echo in js files,
     *      must be define by Image::getImagesData or by another get-image-function
     */
    protected string $images = '';

    /**
     * @var string $title Title of current image page
     */
    protected string $title = '';

    /**
     * @var string $type Type string which used on the render step by template.
     *      For now allowed 'self', 'other', 'best', 'latest' but these values
     *      don't validates and used according to template realization
     */
    protected string $type;

    public function render()
    {
        include_once __DIR__ . '/../../../public/templates/image.php';
    }
}