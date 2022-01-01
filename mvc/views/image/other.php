<?php

declare(strict_types=1);

namespace Views;

use Models\Image;
use PDOException;

class OtherImageView extends ImageView
{
    /**
     * @var int $id Id of image in db
     */
    private int $id = 0;

    /**
     * @var int $rating Integer value from 1 to 5 from the post
     */
    private int $rating = 0;

    public function __construct()
    {
        $this->type = 'other';
        if ($_SESSION['AUTH']) {
            $this->user = $_SESSION['USER']['ID'];
        }
    }

    /**
     * Collects data from the post. Must be used when request method is post
     * and before isValid
     */
    public function collect()
    {
        if (!$_SESSION['AUTH'] || !array_key_exists('ID', $_POST) || !array_key_exists('RATING', $_POST)) {
            return;
        }

        $id = (int)filter_var($_POST['ID'], FILTER_SANITIZE_NUMBER_INT) ?: 0;
        if ($id) {
            try {
                Image::exists($id); // throws
                $this->id = $id;
            } catch (PDOException) {
                // That could be an internal server error, or a database user
                // deletion error. That is not critical and by default we have
                // this->id equal to zero. So in the error case id not changes
                // and this->isValid is false

                // EQUIVALENT CODE
                // $this->id = 0;  // Zero by default
            }
        }

        $rating = (int)filter_var($_POST['RATING'], FILTER_SANITIZE_NUMBER_INT) ?: 0;
        if (1 <= $rating && $rating <= 5) {
            $this->rating = $rating;
        }
    }

    /**
     * @return bool True if id, user, rating is not equals to zero
     */
    public function isValid(): bool
    {
        return $this->id && $this->user && $this->rating;
    }

    /**
     * @throws PDOException
     */
    public function isRatingSat(): bool
    {
        return Image::isRatingSat($this->id, $this->user);
    }

    public function setImageData(int $target)
    {
        $this->images = json_encode(Image::getImagesData($target));
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @throws PDOException
     */
    public function updateRating()
    {
        Image::updateRating($this->id, $this->user, $this->rating);
    }

    public function render()
    {
        include_once __DIR__ . '/../../../public/templates/image.php';
    }
}