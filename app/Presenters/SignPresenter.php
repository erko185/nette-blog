<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use App\Forms;
use Nette\Application\UI\Form;


final class SignPresenter extends Nette\Application\UI\Presenter
{
    /** @persistent */
    public $backlink = '';

    private Forms\SignInFormFactory $signInFactory;

    private Forms\SignUpFormFactory $signUpFactory;

    private Forms\SignChangeFormFactory $signChangeFactory;


    public function __construct(Forms\SignInFormFactory $signInFactory, Forms\SignUpFormFactory $signUpFactory, Forms\SignChangeFormFactory $signChangeFactory)
    {
        $this->signInFactory = $signInFactory;
        $this->signUpFactory = $signUpFactory;
        $this->signChangeFactory = $signChangeFactory;
    }


    /**
     * Sign-in form factory.
     */
    protected function createComponentSignInForm(): Form
    {
        return $this->signInFactory->create(function (): void {
            $this->restoreRequest($this->backlink);
            $this->redirect('Homepage:');
        });
    }


    /**
     * Sign-up form factory.
     */
    protected function createComponentSignUpForm(): Form
    {
        return $this->signUpFactory->create(function (): void {
            $this->redirect('Homepage:');
        });
    }


    public function actionOut(): void
    {
        $this->getUser()->logout();
        $this->redirect('Homepage:');
    }


    public function renderChange(){
     if (!$this->getUser()->isLoggedIn()) {
            $this->redirect('Homepage:');
        }
    }


    /**
     * Sign-change form factory.
     */
    protected function createComponentSignChangeForm(): Form
    {

            return $this->signChangeFactory->create(function (): void {
                $this->flashMessage("Heslo uspesne zmenene",'success');
                $this->redirect('Sign:change');
            });

    }


}
