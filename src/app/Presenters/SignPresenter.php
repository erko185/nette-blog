<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use App\Forms;
use Nette\Application\UI\Form;
use App\Forms\SignInFormFactory;
use App\Forms\SignUpFormFactory;
use App\Forms\SignChangeFormFactory;

final class SignPresenter extends Nette\Application\UI\Presenter
{
    /** @persistent */
    public $backlink = '';

    private SignInFormFactory $signInFactory;

    private SignUpFormFactory $signUpFactory;

    private SignChangeFormFactory $signChangeFactory;


    public function __construct(SignInFormFactory $signInFactory, SignUpFormFactory $signUpFactory, SignChangeFormFactory $signChangeFactory)
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
