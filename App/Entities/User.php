<?php

namespace App\Entities;

use BadBox\Exceptions\EntityNotFoundException;
use BadBox\Modules\Database;
use BadBox\Utils\Entity;
use BadMethodCallException;
use PDO;

/**
 * Class User
 * @package App\Entities
 * @method string getUsername()
 * @method string getEmail()
 */
class User
    extends Entity
{

    private ?int $id;
    private string $username;
    private string $email;
    private string $password;

    public function __construct(string $username, string $email, string $password, int $id = null)
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
    }

    public static function getByUsername(string $username): ?User
    {
        $result = Database::getInstance()
                          ->preparedQuery(
                              "SELECT * FROM users WHERE username = ? LIMIT 1",
                              [$username]
                          );
        if ($result === false || count($result) === 0) {
            return null;
        }
        return self::createFromArray($result[0]);
    }

    public static function getByEmail(string $email): ?User
    {
        $result = Database::getInstance()
                          ->preparedQuery(
                              "SELECT * FROM users WHERE email = ? LIMIT 1",
                              [$email]
                          );
        if ($result === false || count($result) === 0) {
            return null;
        }
        return self::createFromArray($result[0]);
    }

    public static function insertUser(User $user): bool
    {
        $a = Database::getInstance()
                     ->preparedQuery(
                         'INSERT INTO buzzy.users(username, email, password) VALUES (?,?,?)',
                         [$user->username,
                          $user->email,
                          $user->password]
                     );
        return $a !== false;
    }

    public function jsonSerialize(): array
    {
        return ['username'      => $this->username,
                'db_id'         => $this->id,
                'email'         => $this->email,
                'password_hash' => $this->password];
    }

    private static function createFromArray(array $origin): ?User
    {
        foreach (['username',
                  'email',
                  'id',
                  'password'] as $key) {
            if (!array_key_exists($key, $origin)) {
                return null;
            }
        }
        return new User($origin['username'], $origin['email'], $origin['password'], $origin['id']);
    }
}
