<?php

namespace App;

use Nette;
use Nette\Utils\Strings;

/**
 *
 * Users management.
 */
class UserManager implements Nette\Security\IAuthenticator
{

    use Nette\SmartObject;

    const
            TABLE_NAME = 'users',
            COLUMN_ID = 'id',
            COLUMN_NAME = 'username',
            COLUMN_PASSWORD_HASH = 'password',
            COLUMN_ROLE = 'role';

    /** @var Nette\Database\Context */
    private $database;

    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

    /**
     * Performs an authentication.
     * @return Nette\Security\Identity
     * @throws Nette\Security\AuthenticationException
     */
    public function authenticate(array $credentials)
    {
        list($username, $password) = $credentials;
        $password = self::removeCapsLock($password);

        $row = $this->database->table(self::TABLE_NAME)->where(self::COLUMN_NAME, $username)->fetch();

        if (!$row) {
            throw new Nette\Security\AuthenticationException('Zadaný login je neplatný.', self::IDENTITY_NOT_FOUND);
        } elseif (!Passwords::verify($password, $row[self::COLUMN_PASSWORD_HASH])) {
            throw new Nette\Security\AuthenticationException('Zadané heslo je neplatné.', self::INVALID_CREDENTIAL);
        } elseif (Passwords::needsRehash($row[self::COLUMN_PASSWORD_HASH])) {
            $row->update(array(
                self::COLUMN_PASSWORD_HASH => Passwords::hash($password),
            ));
        }
        $arr = $row->toArray();
        unset($arr[self::COLUMN_PASSWORD_HASH]);

        return new Nette\Security\Identity($row[self::COLUMN_ID], explode(';', $row[self::COLUMN_ROLE]), $arr);
    }

    /**
     * Adds new user.
     * @param  string
     * @param  string
     * @return void
     */
    public function add($username, $password)
    {
        $this->database->table(self::TABLE_NAME)->insert(array(
            self::COLUMN_NAME => $username,
            self::COLUMN_PASSWORD_HASH => Passwords::hash(self::removeCapsLock($password)),
        ));
    }

    public function changePassword($userId, $oldPassword, $newPassword)
    {
        $oldPassword = self::removeCapsLock($oldPassword);
        $newPassword = self::removeCapsLock($newPassword);

        $row = $this->database->table(self::TABLE_NAME)->where(self::COLUMN_ID, $userId)->fetch();

        if (!$row) {
            throw new Nette\Security\AuthenticationException('Při změně hesla došlo k chybě.', self::IDENTITY_NOT_FOUND);
        } elseif (!Passwords::verify($oldPassword, $row[self::COLUMN_PASSWORD_HASH])) {
            throw new Nette\Security\AuthenticationException('Zadané staré heslo je neplatné.', self::INVALID_CREDENTIAL);
        } elseif (Passwords::needsRehash($row[self::COLUMN_PASSWORD_HASH])) {
            $row->update(array(
                self::COLUMN_PASSWORD_HASH => Passwords::hash($oldPassword),
            ));
        }

        $row->update(array(
            self::COLUMN_PASSWORD_HASH => Passwords::hash($newPassword),
        ));
    }

    /**
     * Fixes caps lock accidentally turned on.
     * @return string
     */
    private static function removeCapsLock($password)
    {
        return $password === Strings::upper($password) ? Strings::lower($password) : $password;
    }
}
