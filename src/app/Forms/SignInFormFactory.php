<?php

declare(strict_types=1);

namespace App\Forms;

use Nette;
use Nette\Application\UI\Form;
use Nette\Security\User;


final class SignInFormFactory
{
	use Nette\SmartObject;

	private FormFactory $factory;

	private User $user;


	public function __construct(FormFactory $factory, User $user)
	{
		$this->factory = $factory;
		$this->user = $user;
	}


	public function create(callable $onSuccess): Form
	{
		$form = $this->factory->create();
		$form->addText('email', 'Email:')
			->setRequired('Vlozte meno');

		$form->addPassword('password', 'Heslo:')
			->setRequired('Vlozte heslo');

		$form->addSubmit('send', 'Prihlasit sa');

		$form->onSuccess[] = function (Form $form, \stdClass $values) use ($onSuccess): void {
			try {
				$this->user->login($values->email, $values->password);
			} catch (Nette\Security\AuthenticationException $e) {
				$form->addError('Email alebo heslo je nespravne');
				return;
			}
			$onSuccess();
		};

		return $form;
	}
}
