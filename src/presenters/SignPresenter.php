<?php

namespace App\Presenters;

use Nette;
use App;
use App\Model;
use Nette\Application\UI\Form;

/**
 * Sign in/out presenters.
 */
class SignPresenter extends BasePresenter
{

    public function renderChangePass()
    {

    }

    public function renderIn()
    {
        if ($this->getUser()->isLoggedIn()) {
            $this->redirect('Sign:profile');
        }
    }

    public function renderProfile()
    {
        $user = $this->em->getDao(App\User::getClassName());
        $profileUser = $user->find($this->getUser()->id);

        $this->template->profileUser = $profileUser;
    }

    protected function createComponentChangePasswordForm()
    {
        $form = new Nette\Application\UI\Form;
        $form->addPassword('oldpassw', 'Staré heslo:')
                ->setRequired('Prosím, zadejte své staré heslo.')
                ->getControlPrototype()->class('form-control');

        $form->addPassword('newpassw', 'Nové heslo:')
                ->setRequired('Prosím, zadejte své nové heslo.')
                ->getControlPrototype()->class('form-control');

        $form->addPassword('newpassw2', 'Nové heslo (znovu):')
                ->setRequired('Prosím, zadejte své nové heslo pro kontrolu.')
                ->addRule(Form::EQUAL, "Zadaná nová hesla se neshodují.", $form["newpassw"])
                ->getControlPrototype()->class('form-control');

        $form->addSubmit('send', 'Změnit heslo')
                ->getControlPrototype()->class('btn btn-success');

        $form->onSuccess[] = $this->changePasswordFormSucceeded;
        return $form;
    }

    protected function createComponentChangeProfileForm($form)
    {
        $userDao = $this->em->getDao(App\User::getClassName());
        $user = $userDao->find($this->getUser()->id);

        $form = new Nette\Application\UI\Form;
        $form->addText('firstname', 'Jméno:')
                ->setRequired('Prosím, zadejte své své jméno.')
                ->setDefaultValue($user->firstName)
                ->getControlPrototype()->class('form-control');

        $form->addText('lastname', 'Příjmení:')
                ->setRequired('Prosím, zadejte své nové příjmení.')
                ->setDefaultValue($user->lastName)
                ->getControlPrototype()->class('form-control');

        $form->addText('email', 'Email:')
                ->setRequired('Prosím, zadejte svou emailovou adesu.')
                ->setDefaultValue($user->email)
                ->getControlPrototype()->class('form-control');

        $form->addSubmit('send', 'Uložit změny')
                ->getControlPrototype()->class('btn btn-success');

        $form->onSuccess[] = $this->changeProfileFormSucceeded;
        return $form;
    }

    public function changeProfileFormSucceeded($form)
    {
        $values = $form->getValues(true);

        $userDao = $this->em->getDao(App\User::getClassName());
        $user = $userDao->find($this->getUser()->id);
        $user->setFirstName($values['firstname']);
        $user->setLastName($values['lastname']);
        $user->setEmail($values['email']);

        $this->em->persist($user);
        $this->em->flush();

        $this->flashMessage('Údaje o uživateli byly úspěšně uloženy.', 'success');
        $this->redirect('Sign:profile');
    }

    public function changePasswordFormSucceeded($form)
    {
        $values = $form->getValues();
        $userId = $this->getUser()->id;

        $fOldPass = $values["oldpassw"];
        $fNewPass = $values["newpassw"];

        try {
            $this->getUser()->getAuthenticator()->changePassword($userId, $fOldPass, $fNewPass);

            $this->flashMessage('Vaše heslo bylo úspěšně změněno.', 'success');
            $this->redirect('Sign:in');
        } catch (Nette\Security\AuthenticationException $e) {
            $form->addError('Zadané staré heslo není správné.');
        }
    }

    /**
     * Sign-in form factory.
     * @return Nette\Application\UI\Form
     */
    protected function createComponentSignInForm()
    {
        $form = new Nette\Application\UI\Form;
        $form->addText('username', 'Login:')
                ->setRequired('Prosím, vložte svůj login.')
                ->getControlPrototype()->class('form-control');

        $form->addPassword('password', 'Heslo:')
                ->setRequired('Prosím, vložte své heslo.')
                ->getControlPrototype()->class('form-control');

        $form->addCheckbox('remember', 'Zůstat přihlášen');

        $form->addSubmit('send', 'Přihlásit se')->getControlPrototype()->class('btn btn-lg btn-success');

        // call method signInFormSucceeded() on success
        $form->onSuccess[] = $this->signInFormSucceeded;
        return $form;
    }

    public function signInFormSucceeded($form)
    {
        $values = $form->getValues();

        if ($values->remember) {
            $this->getUser()->setExpiration('14 days', FALSE);
        } else {
            $this->getUser()->setExpiration('20 minutes', TRUE);
        }

        try {
            $this->getUser()->login($values->username, $values->password);
            $this->flashMessage('Byl/a jste úspěšně přihlášen/a jako "' . $values->username . '"', 'success');
            $this->redirect('Homepage:');
        } catch (Nette\Security\AuthenticationException $e) {
            $form->addError($e->getMessage());
        }
    }

    public function actionOut()
    {
        $this->getUser()->logout();
        $this->flashMessage('Uživatel byl úspěšně odhlášen.', 'success');
        $this->redirect('in');
    }

}
