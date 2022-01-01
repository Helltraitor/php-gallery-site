<?php

declare(strict_types=1);

namespace Views;

use Models\Image;

class SelfImageView extends ImageView
{
    /**
     * @var bool $fileExists Flag which used by instance functions
     */
    protected bool $fileExists = false;

    /**
     * @var string $filename Name of uploaded file. Used for type validation
     */
    protected string $filename = '';

    /**
     * @var string $description Posted description
     */
    protected string $description = '';

    public function __construct()
    {
        $this->type = 'self';

        if ($_SESSION['AUTH']) {
            $this->user = $_SESSION['USER']['ID'];
            $this->title = $_SESSION['USER']['NAME'];
            $this->images = json_encode(Image::getImagesData($this->user));
        }

        $description = array_key_exists('description', $_POST) ? $_POST['description'] : '';
        $description = filter_var($description, FILTER_SANITIZE_STRING);
        $this->description = $description ?: '';
    }

    /**
     * @return bool True when file name ends with jpeg ot jpg and type file
     *      is image/jpeg
     */
    public function checkFiletype(): bool
    {
        return $_FILES['file']['type'] === 'image/jpeg'
            && (str_ends_with($this->filename, '.jpg')
                || str_ends_with($this->filename, '.jpeg'));
    }

    /**
     * Collects data from the form. Must be called after isValid and before
     * getFormData
     */
    public function collect()
    {
        $this->fileExists = array_key_exists('file', $_FILES);

        $filename = $this->fileExists ? $_FILES['file']['name'] : '';
        $filename = filter_var($filename, FILTER_SANITIZE_STRING);
        $this->filename = $filename ?: '';
    }

    /**
     * @return array An array which contains 'description', 'error', 'tmp' keys
     */
    public function getFormData(): array
    {
        return [
            'description' => $this->description,
            'error' => $this->fileExists ? $_FILES['file']['error'] : 0,
            'tmp' => $_FILES['file']['tmp_name']
        ];
    }

    /**
     * @return bool True if file exists, pass all checks, and have a size
     *      that less or equal to 3M
     */
    public function isValidFile(): bool
    {
        // There must be antivirus checking, but it lose somewhere in cosmos
        return $this->fileExists
            && $this->checkFiletype()
            && $_FILES['file']['size'] < 3 * 1024 * 1024;
    }
}