<?php

declare(strict_types=1);

namespace Models;

use PDO;
use PDOException;

/**
 * Class Session implements session mechanism. Used as default way to distinguish
 * user between each other
 */
class Session
{
    /**
     * @var int $user Id of the existing user
     */
    private int $user;

    /**
     * @var string $uuid The UUID access token which is not exactly UUID but needs
     *      for same things
     */
    private string $uuid;

    /**
     * @var int $expire The date in seconds relative to system time zone.
     *      Note that the server is never running away, Neo. But you could
     *      also use UTC
     */
    private int $expire;

    /**
     * @var bool $valid The valid flag contains current state of session.
     *      The isValid function could update this state. When session destroyed
     *      by the destroy function valid become false
     */
    private bool $valid;

    /**
     * Session constructor. Used only by static functions
     */
    protected function __construct(int $user, string $uuid, int $expire)
    {
        $this->user = $user;
        $this->uuid = $uuid;
        $this->expire = $expire;
        $this->valid = true;
    }

    /**
     * This function create session on the DB side. Use th register function
     * for change current server session state
     *
     * @param int $user Id of user for which starts a new session
     * @return Session Session instance of the user
     * @throws PDOException
     */
    public static function create(int $user): Session
    {
        $session = new Session($user, createUniqueId(), time() + 172800);

        $prepared = Connection::get()->prepare(
            "INSERT INTO public.auth(id, uuid, expire)
	             VALUES (:id, :uuid, :expire);"
        );
        $valid = $prepared->bindParam(':id', $session->user, PDO::PARAM_INT);
        $valid &= $prepared->bindParam(':uuid', $session->uuid, PDO::PARAM_STR, 128);
        $valid &= $prepared->bindParam(':expire', $session->expire, PDO::PARAM_INT);
        if (!$valid || !$prepared->execute()) {
            throw new PDOException();
        }

        return $session;
    }

    /**
     * The default way to destroy session. Use it for every user which was logged in
     *
     * @throws PDOException
     */
    public function destroy()
    {
        nativeDestroySession();
        setcookie('UUID', '', time() - 3600);
        $this->valid = false;

        $prepared = Connection::get()->prepare(
            "DELETE FROM public.auth
	             WHERE uuid=:uuid;"
        );
        $prepared->bindParam(':uuid', $this->uuid, PDO::PARAM_STR, 128);
        if (!$prepared->execute()) {
            throw new PDOException();
        }
    }

    public function getExpire(): int
    {
        return $this->expire;
    }

    public function getUserId(): int
    {
        return $this->user;
    }

    public function getUUID(): string
    {
        return $this->uuid;
    }

    /**
     * Tries to return the existing session from the DB
     *
     * @param string $uuid UUID access token which contains in user cookie
     * @return Session Session which used by indicated uuid
     * @throws PDOException
     */
    public static function getByUUID(string $uuid): Session
    {
        $prepared = Connection::get()->prepare(
        "SELECT id, uuid, expire
	         FROM public.auth
             WHERE uuid=:uuid;"
        );
        $valid = $prepared->bindParam(':uuid', $uuid, PDO::PARAM_STR, 128);
        if (!$valid || !$prepared->execute()) {
            throw new PDOException();
        }

        $session = $prepared->fetch(PDO::FETCH_ASSOC);
        if (empty($session)) {
            throw new PDOException();
        }
        return new Session((int)$session['id'], $session['uuid'], (int)$session['expire']);
    }

    /**
     * Check if the session is expired relative to system time
     *
     * @return bool True when session is dead and must be destroy else false
     */
    public function isExpired(): bool
    {
        return $this->expire < time();
    }

    /**
     * Check itself state and update valid flag
     *
     * @return bool True when session can be used for authenticate else false
     */
    public function isValid(): bool
    {
        $this->valid &= !$this->isExpired();
        return $this->valid;
    }

    /**
     * Session need to be updated one per two days. The windows for update
     * is equal to one day
     *
     * @return bool True if session need to be updated else false
     */
    public function needRenew(): bool
    {
        $fresh = $this->expire - time();
        return 0 < $fresh && $fresh < 86400;
    }

    /**
     * This function register created or updated session in server session
     * and user cookie
     */
    public function register()
    {
        $user = User::getById((int)$this->user);

        $_SESSION['AUTH'] = true;
        $_SESSION['USER'] = [
            'ID' => $user->getId(),
            'NAME' => $user->getName(),
            'UUID' => $this->uuid,
            'EXPIRE' => $this->expire
        ];
        // By some reason expire can contain valid string int
        setcookie('UUID', $this->uuid, (int)$this->expire);
    }

    /**
     * Updates session in the DB and use registerSession inside
     * (because we always need to register updated session)
     */
    public function renew()
    {
        $this->expire = time() + 172800;
        $this->register();

        $prepared = Connection::get()->prepare(
            "UPDATE public.auth
	             SET expire=:expire
	             WHERE id=:id AND uuid=:uuid;"
        );
        $prepared->bindParam(':id', $this->user, PDO::PARAM_INT);
        $prepared->bindParam(':uuid', $this->uuid, PDO::PARAM_STR, 128);
        $prepared->bindParam(':expire', $this->expire, PDO::PARAM_INT);
        if(!$prepared->execute()) {
            throw new PDOException();
        }
    }
}