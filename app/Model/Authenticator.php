<?php

namespace App\Model;
use Nette;

class Authenticator implements Nette\Security\IAuthenticator
{
    private $database;
    private $passwords;

    public function __construct(Nette\Database\Context $database, Nette\Security\Passwords $passwords)
    {
        $this->database = $database;
        $this->passwords = $passwords;
    }

    public function authenticate(array $credentials): Nette\Security\IIdentity
    {
        [$email, $password] = $credentials;

        $row = $this->database->table('users')
            ->where('email', $email)
            ->fetch();
        //dumpe($row);

        if (!$row) {
            throw new Nette\Security\AuthenticationException('Uzivatel nebol najdeny.');
        }

        if (!$this->passwords->verify($password, $row->password)) {
            throw new Nette\Security\AuthenticationException('Nespravne heslo.');
        }

        return new Nette\Security\Identity(
            $row->id,
//            $row->role, // nebo pole více rolí
            ['email' => $row->email]
        );
    }
}