<?php

declare(strict_types=1);

namespace App\Model;

use Nette;
use Nette\Security\Passwords;


/**
 * Users management.
 */
final class UserManager
{
    use Nette\SmartObject;

    private const
        TABLE_NAME = 'users',
        COLUMN_ID = 'id',
        COLUMN_PASSWORD_HASH = 'password',
        COLUMN_EMAIL = 'email';


    private Nette\Database\Explorer $database;

    private Passwords $passwords;


    public function __construct(Nette\Database\Explorer $database, Passwords $passwords)
    {
        $this->database = $database;
        $this->passwords = $passwords;
    }


    public function getTable()
    {
        return $this->database->table(self::TABLE_NAME);
    }

    /**
     * Adds new user.
     * @throws DuplicateNameException
     */
    public function add(string $email, string $password): void
    {
        Nette\Utils\Validators::assert($email, 'email');
        try {
            $this->getTable()->insert([
                self::COLUMN_PASSWORD_HASH => $this->passwords->hash($password),
                self::COLUMN_EMAIL => $email,
            ]);
        } catch (Nette\Database\UniqueConstraintViolationException $e) {
            throw new DuplicateNameException;
        }
    }


    public function getPassword(int $id)
    {
        return $this->database->table(self::TABLE_NAME)->where("id", $id)->fetch();
    }

    public function changePassword(int $id, string $password): void
    {
        $this->getTable()->where("id", $id)->update([self::COLUMN_PASSWORD_HASH => $this->passwords->hash($password)]);

    }
}