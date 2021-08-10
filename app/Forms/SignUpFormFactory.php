<?php

declare(strict_types=1);

namespace App\Forms;

use App\Model;
use Nette;
use Nette\Application\UI\Form;
use App\Model\UserManager;


final class SignUpFormFactory
{
	use Nette\SmartObject;

	private const PASSWORD_MIN_LENGTH = 7;

	private FormFactory $factory;

	private UserManager $userManager;


	public function __construct(FormFactory $factory, UserManager $userManager)
	{
		$this->factory = $factory;
		$this->userManager = $userManager;
	}


	public function create(callable $onSuccess): Form
	{
		$form = $this->factory->create();

		$form->addEmail('email', 'Email:')
			->setRequired('Vlozte heslo');

		$form->addPassword('password', 'Vytvorit heslo:')
			->setOption('description', sprintf('minimalne %d pismen', self::PASSWORD_MIN_LENGTH))

			->setRequired('Vytvorte heslo')
			->addRule($form::MIN_LENGTH, null, self::PASSWORD_MIN_LENGTH);

		$form->addSubmit('send', 'Zaregistrovat sa');

		$form->onSuccess[] = function (Form $form, \stdClass $values) use ($onSuccess): void {

				$value =$this->userManager->add($values->email, $values->password);

				if ($value=="DuplicateNameException"){
                    $form->addError('Email sa uz pouziva');
                    return;
                }
			$onSuccess();
		};

		return $form;
	}
}
