<?php

declare(strict_types=1);

namespace Models;

use PDO;
use PDOException;

/**
 * Represents the Connection to the PostgreSQL server. This is the Singleton
 * for connection establishment. Contains the static variable and static functions.
 *
 * @package Models contains different models of MVC and classes which use DB
 */
final class Connection {

    /**
     * @var ?PDO $connection Contains PDO connections which will be kept until
     *      web is up
     */
    private static ?PDO $connection = null;

    private function __construct()
    {
        // No instance allow
    }

    /**
     * Connects to the database and return an instance of PDO
     *
     * @return PDO
     * @throws PDOException
     */
    private static function connect(): PDO
    {
        // Read parameters in the ini configuration file
        $params = parse_ini_file(__DIR__ . '/../../config/postgresql.ini');
        if ($params === false) {
            throw new PDOException(
                'Server unable to connect to database.'
                        . ' Is PostgreSQL driver enabled?'
                        . ' Check it on php.ini 944 line.'
                        . 'If you use another db, search you driver around 94x lines.'
            );
        }
        // Connect to the postgresql database
        $db = sprintf(
            "pgsql:host=%s;port=%d;dbname=%s;user=%s;password=%s",
            $params['host'], $params['port'], $params['database'],
            $params['user'], $params['password']
        );
        $pdo = new PDO($db);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }

    /**
     * Returns an instance of the Connection or create new and returns it further
     *
     * @return PDO
     * @throws PDOException
     */
    public static function get(): PDO
    {
        if (null === Connection::$connection) {
            Connection::$connection = Connection::connect();
        }
        return Connection::$connection;
    }
}