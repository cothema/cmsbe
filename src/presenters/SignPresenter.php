<?php

namespace App\Presenters;

use Nette;
use Cothema\Model\User\User;
use Nette\Application\UI\Form;

/**
 *
 * Sign in/out presenters.
 *
 * @author Miloš Havlíček <miloshavlicek@gmail.com>
 * @property $signInFormSucceeded
 */
class SignPresenter extends BasePresenter
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return void
     */
    public function renderIn()
    {
        if ($this->user->isLoggedIn() && $this->user->isInRole('admin')) {
            $params = $this->request->getParameters();
            if (!empty($params['backSignInUrl'])) {
                $this->redirectUrl($params['backSignInUrl']);
            } else {
                $this->redirect('Homepage:');
            }
        }
    }

    /**
     * Sign-in form factory.
     * @return Nette\Application\UI\Form
     */
    protected function createComponentSignInForm()
    {
        $form = new Form;
        $form->addText('username', 'Login:')
            ->setRequired('Prosím, vložte svůj login.')
            ->getControlPrototype()
            ->class('form-control');

        $form->addPassword('password', 'Heslo:')
            ->setRequired('Prosím, vložte své heslo.')
            ->getControlPrototype()
            ->class('form-control');

        $form->addHidden('backSignInUrl', $this->getParameter('backSignInUrl'));

        $form->addCheckbox('remember', 'Zůstat přihlášen');

        $form->addSubmit('send', 'Přihlásit se')
            ->getControlPrototype()
            ->class('btn btn-lg btn-success');

        $form->onSuccess[] = [$this, 'signInFormSucceeded'];
        return $form;
    }

    private function getActiveUserByUsername($username)
    {
        $rep   = $this->em->getRepository(User::class);
        $users = $rep->findOneBy(['username' => $username, 'active' => 1]);

        return isset($users) ? $users : null;
    }

    /**
     *
     * @param Nette\Application\UI\Form $form
     * @throws \Exception
     */
    public function signInFormSucceeded($form)
    {
        $values = $form->getValues();

        if ($values->remember) {
            $this->user->setExpiration('14 days', false);
        } else {
            $this->user->setExpiration('20 minutes', true);
        }

        try {
            $user = $this->getActiveUserByUsername($values->username);
            if (!$user) {
                throw new \Exception('Uživatel není aktivní nebo neexistuje.');
            }

            $this->user->login($user->username, $values->password);

            $this->flashMessage(
                'Byl/a jste úspěšně přihlášen/a jako "'.$user->username.'"',
                'success'
            );

            if (!empty($values['backSignInUrl'])) {
                $redirectToUrl = $values['backSignInUrl'];
            } else {
                $this->redirect('Homepage:');
            }
        } catch (Nette\Security\AuthenticationException $e) {
            $form->addError($e->getMessage());
        } catch (\Exception $e) {
            $form->addError($e->getMessage());
        }

        if (!empty($redirectToUrl)) {
            $this->redirectUrl($redirectToUrl);
        }
    }

    /**
     * @return void
     */
    public function actionOut()
    {
        $this->user->logout();
        $this->flashMessage('Uživatel byl úspěšně odhlášen.', 'success');
        $this->redirect('in');
    }

}
