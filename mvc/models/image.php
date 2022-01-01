<?php

declare(strict_types=1);

namespace Models;

use PDO;
use PDOException;

/**
 * Class Image represents image and db driver for using file system as db.
 * The reason of this decision is simple of using file system. Instead
 * of using exactly db we can use abstraction and if it will need just
 * replace some parts for using another db (e.g. MongoDB)
 *
 */
class Image
{
    /**
     * @var int $id Id of image in public.image table
     */
    private int $id;

    /**
     * @var int $user Id of user in public."user" table
     */
    private int $user;

    /**
     * @var string $description The posted description
     */
    private string $description;

    /**
     * @var string path to temp file after form is uploaded
     */
    private string $temp_file;

    /**
     * @param int $user User id in postgresql db
     * @param string $description Posted description
     * @param string $temp_file Path to the temp file like C:\...\abraKADABRA.tmp
     */
    public function __construct(int $user, string $description, string $temp_file)
    {
        $this->id = 0;
        $this->user = $user;
        $this->description = $description;
        $this->temp_file = $temp_file;
    }

    /**
     * @throws PDOException
     */
    protected function deleteDBInfo()
    {
        $prepared = Connection::get()->prepare(
          'DELETE FROM public.image
               WHERE id=:id'
        );
        $valid = $prepared->bindParam(':id', $this->id, PDO::PARAM_INT);
        if (!$valid || !$prepared->execute()) {
            throw new PDOException();
        }
    }

    /**
     * @throws PDOException
     */
    protected function deleteDBFile() {
        $result = unlink(
            __DIR__
            . '/../../image-db/'
            . (string)$this->user . '/'
            . (string)$this->id . '.jpeg'
        );
        if (!$result) {
            throw new PDOException();
        }
    }

    /**
     * Delete file info from postgresql and delete file itself from file system
     */
    public function delete() {
        $this->deleteDBInfo();
        $this->deleteDBFile();
    }

    /**
     * @param int $id Id of image in public.image
     * @return bool True if image exists in db else false
     */
    public static function exists(int $id): bool
    {
        $prepared = Connection::get()->prepare(
            'SELECT id
	             FROM public.image
	             WHERE id = :id;'
        );
        $valid = $prepared->bindParam(':id', $id, PDO::PARAM_INT);
        if (!$valid || !$prepared->execute()) {
            throw new PDOException();
        }
        $row = $prepared->fetchAll(PDO::FETCH_ASSOC);
        return !empty($row);
    }

    /**
     * @return array Array of 20 best images data from db
     */
    public static function getBestImagesData(): array
    {
        $prepared = Connection::get()->prepare(
            'SELECT public.image.id, "user", name, rating, rated, description, rated_users
	             FROM public.image
		             INNER JOIN public."user" on (
			             public.image."user" = public."user".id
		         )
	             ORDER BY
				     rating::float / (rated + 1)::float DESC,
				     rated DESC
	             LIMIT 20;'
        );
        if (!$prepared->execute()) {
            return [];
        }
        return $prepared->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param int $user User id in the db
     * @return array Array of images data of requested user
     */
    public static function getImagesData(int $user): array
    {
        $prepared = Connection::get()->prepare(
            'SELECT public.image.id, "user", name, rating, rated, description, rated_users
	             FROM public.image
		             INNER JOIN public."user" on (
			             public.image."user" = public."user".id
		             )
                WHERE "user"=:user;'
        );
        $valid = $prepared->bindParam(':user', $user, PDO::PARAM_INT);
        if (!$valid || !$prepared->execute()) {
            return [];
        }
        return $prepared->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @return array Array of 20 latest images data from db
     */
    public static function getLatestImagesData(): array
    {
        $prepared = Connection::get()->prepare(
            'SELECT public.image.id, "user", name, rating, rated, description, rated_users
	             FROM public.image
		             INNER JOIN public."user" on (
			             public.image."user" = public."user".id
		             )
		         ORDER BY public.image.id DESC
	             LIMIT 20;'
        );
        if (!$prepared->execute()) {
            return [];
        }
        return $prepared->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Saves file information in the db and get created file id which will
     * be used in file saving step
     */
    protected function saveDBInfo()
    {
        $prepared = Connection::get()->prepare(
            "INSERT INTO public.image
                 (\"user\", rating, rated, description, rated_users)
                 VALUES (:user, 0, 0, :description, '{}')
                 RETURNING id;"
        );
        $valid = $prepared->bindParam(':user', $this->user, PDO::PARAM_INT);
        $valid &= $prepared->bindParam(':description', $this->description);
        if (!$valid || !$prepared->execute()) {
            throw new PDOException();
        }
        $image = $prepared->fetch(PDO::FETCH_ASSOC);
        if (empty($image)) {
            throw new PDOException();
        }
        $this->id = $image['id'];
    }

    /**
     * @throws PDOException
     */
    protected function saveDBFile()
    {
        $db_folder = __DIR__ . '/../../image-db';
        $user_folder = $db_folder . '/' . (string)$this->user;
        if (!is_dir($user_folder) && !mkdir($user_folder)) {
            throw new PDOException();
        }

        if (
            !rename(
                $this->temp_file,
                $user_folder . '/' . (string)$this->id . '.jpeg'  // only jpeg allowed
            )
        ) {
            throw new PDOException();
        }
    }

    /**
     * Tries to safe file in the db and the file system
     *
     * @throws PDOException
     */
    public function save()
    {
        $this->saveDBInfo();
        try {
            // When function is called file already created in the db
            $this->saveDBFile();
        // So we must flush record on the error
        } catch (PDOException) {
            $this->deleteDBInfo();
        }
    }

    /**
     * Check if image rating was sat by indicated user
     *
     * @param int $id Id of image
     * @param int $user Id of user
     * @return bool
     */
    public static function isRatingSat(int $id, int $user): bool
    {
        $prepared = Connection::get()->prepare(
            /*
            // PHP break '>' and left only '&gt;'
            'SELECT rated_users @> ARRAY[:user] AS rated
	             FROM public.image
	             WHERE id = :id;'
            */
            // Alternative version
            'SELECT :user = ANY(rated_users) AS rated
	             FROM public.image
	             WHERE id = :id;'
        );
        $valid = $prepared->bindParam(':id', $id, PDO::PARAM_INT);
        $valid &= $prepared->bindParam(':user', $user, PDO::PARAM_INT);
        if (!$valid || !$prepared->execute()) {
            throw new PDOException();
        }
        $row = $prepared->fetch(PDO::FETCH_ASSOC);
        if (empty($row)) {
            throw new PDOException();
        }
        return $row['rated']; // Boolean
    }

    /**
     * @param int $id Id of image in database
     * @param int $user User id who update rating
     * @param int $rating Int 1 - 5 which was chosen by user
     * @throws PDOException
     */
    public static function updateRating(int $id, int $user, int $rating)
    {
        $prepared = Connection::get()->prepare(
            'UPDATE public.image
	             SET rating=rating + :rating, rated=rated + 1, rated_users=array_append(rated_users, :user)
	             WHERE id=:id;'
        );
        $valid = $prepared->bindParam(':id', $id, PDO::PARAM_INT);
        $valid &= $prepared->bindParam(':rating', $rating, PDO::PARAM_INT);
        $valid &= $prepared->bindParam(':user', $user, PDO::PARAM_INT);
        if (!$valid || !$prepared->execute()) {
            throw new PDOException();
        }
    }
}