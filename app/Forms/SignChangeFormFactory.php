<?php

declare(strict_types=1);

namespace App\Forms;

use App\Model;
use Nette;
use Nette\Application\UI\Form;
use Nette\Security\Passwords;


final class SignChangeFormFactory
{
	use Nette\SmartObject;

	private const PASSWORD_MIN_LENGTH = 7;

	private FormFactory $factory;

	private Model\UserManager $userManager;

	private Nette\Security\User $user;

    private Passwords $passwords;

	public function __construct(FormFactory $factory, Model\UserManager $userManager, Nette\Security\User $user,Passwords $passwords)
	{
		$this->factory = $factory;
		$this->userManager = $userManager;
		$this->userManager = $userManager;
		$this->user = $user;
		$this->passwords = $passwords;
	}


	public function create(callable $onSuccess): Form
	{
		$form = $this->factory->create();

		$form->addPassword('actual', 'Aktualne heslo:')
			->setRequired('Vlozte aktualne heslo');

		$form->addPassword('password', 'Nove heslo:')
			->setRequired('Vlozte nove heslo');

		$form->addPassword('password_again', 'Nove heslo:')
            ->setRequired('Vlozte znova heslo');

		$form->addSubmit('send', 'Zmena hesla');

		$form->onSuccess[] = function (Form $form, \stdClass $values) use ($onSuccess): void {
            $pass=$this->userManager->getPassword( $this->user->getId());



            if (!$this->passwords->verify($values->actual,$pass->password)) {
                $form->addError('Neplatne aktualne heslo');
                return;
            }
            else if ($values->password_again!=$values->password) {
                $form->addError('Hesla sa nezhoduju');
                return;
            }

            $this->userManager->changePassword( $this->user->getId(),$values->password);


            $onSuccess();
		};

		return $form;
	}
}
