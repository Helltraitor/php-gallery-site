<?php

declare(strict_types=1);

use Controllers\ErrorController;
use Models\Session;
use Models\User;

/**
 * When session is undefined this function must be used for destroy user session
 */
function nativeDestroySession()
{
    session_unset();
    session_destroy();
}

/**
 * Shortcut for calling native destroy and start session functions
 */
function nativeRestartSession()
{
    nativeDestroySession();
    nativeStartSession();
}

/**
 * When session is undefined this function must be used for start new native
 * session. This function set default parameters to _SESSION
 */
function nativeStartSession()
{
    session_start();
    $_SESSION['AUTH'] = false;
    $_SESSION['USER'] = null;
}

/**
 * Main authenticate function which must be called in entry point
 * of the site - index.php
 */
function authenticate()
{
    session_start();

    // PHP don't know this user
    if (!array_key_exists('AUTH', $_SESSION)) {
        $_SESSION['AUTH'] = false;
        $_SESSION['USER'] = null;
        // Cookie is exists and probably valid
        if (array_key_exists('UUID', $_COOKIE) && $_COOKIE['UUID']) {
            // Trying to recovery session
            cookieAuthenticate();
        }
    // PHP know this user, but we need to check his UUID
    } elseif ($_SESSION['AUTH']) {
        // UUID is not exist or reset - session is wrong
        if (!array_key_exists('UUID', $_COOKIE)
            ||array_key_exists('UUID', $_COOKIE) && !$_COOKIE['UUID']) {
            nativeRestartSession();

        // If auth is true then data from db must be already loading into the session
        // Loaded UUID is different - session is wrong
        } elseif ($_SESSION['USER']['UUID'] !== $_COOKIE['UUID']) {
            nativeRestartSession();
            // Trying to recovery session
            cookieAuthenticate();
        // Needs to renew session
        } elseif ($_SESSION['USER']['EXPIRE'] < time() + 86400) {
            cookieAuthenticate();
        }
        // ELSE successful logged in
    // PHP doesn't know this user, but cookie is exists
    } elseif (array_key_exists('UUID', $_COOKIE)) {
        // Trying recovery session
        cookieAuthenticate();
    // NO COOKIE but PHP remember this user
    } else {
        nativeRestartSession();
    }
}

/**
 * When a cookie exists and we can't check it using the session,
 * it seems to need check the cookie
 */
function cookieAuthenticate()
{
    try {
        $session = Session::getByUUID($_COOKIE['UUID']);
        // Too old UUID
        if ($session->isExpired()) {
            try {
                $session->destroy();
            } catch (PDOException) {
            } finally {
                nativeStartSession();
            }
        // UUID is valid, so we need to update \ create USER in _SESSION
        } else {
            // At this moment user must exists
            try {
                $user = User::getById($session->getUserId());
            // Possible db error or user is not exists
            } catch (PDOException) {
                $error = new ErrorController(500, 'Internal server error while use database');
                $error->handle([]);
                exit;
            }
            $_SESSION['AUTH'] = true;
            $_SESSION['USER'] = [
                'ID' => $user->getId(),
                'NAME' => $user->getName(),
                'UUID' => $session->getUUID(),
                'EXPIRE' => $session->getExpire()
            ];
            // Probably need to renew
            if ($session->needRenew()) {
                $session->renew();
            }
        }
    // UUID is not exists or some server internal error
    } catch (PDOException) {
        setcookie('UUID', '', time() - 3600);
        nativeRestartSession();
    }
}