<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use Cothema\Model as PModel;

/**
 * @Secured
 * @Secured\User(loggedIn)
 * @Secured\Role(admin)
 *
 * Users Presenter
 */
class UsersPresenter extends BasePresenter
{

    public function renderChangePass()
    {
        $navbar   = [];
        $navbar[] = (object) ['name' => 'Uživatelé', 'link' => 'Users:list'];
        $navbar[] = (object) ['name' => 'Detail'];
        $navbar[] = (object) ['name' => 'Změna hesla'];

        $this->template->navbar = $navbar;
    }

    public function renderList()
    {
        $navbar   = [];
        $navbar[] = (object) ['name' => 'Uživatelé'];

        $this->template->navbar = $navbar;

        $usersDao = $this->em->getRepository(PModel\User\User::getClassName());
        $users    = $usersDao->findBy(['active' => 1]);

        $this->template->users = $users;
    }

    public function renderListUnactive()
    {
        $navbar   = [];
        $navbar[] = (object) ['name' => 'Uživatelé', 'link' => 'Users:list'];
        $navbar[] = (object) ['name' => 'Neaktivní'];

        $this->template->navbar = $navbar;

        $usersDao = $this->em->getRepository(PModel\User\User::getClassName());
        $users    = $usersDao->findBy(['active' => 0]);

        $this->template->users = $users;
    }

    /**
     * @Secured
     * @Secured\User(loggedIn)
     * @Secured\Role(usermanager)
     */
    public function renderNew()
    {
        $navbar   = [];
        $navbar[] = (object) ['name' => 'Uživatelé', 'link' => 'Users:list'];
        $navbar[] = (object) ['name' => 'Nový'];

        $this->template->navbar = $navbar;
    }

    public function renderChange()
    {
        $navbar   = [];
        $navbar[] = (object) ['name' => 'Uživatelé', 'link' => 'Users:list'];
        $navbar[] = (object) ['name' => 'Úprava'];

        $this->template->navbar = $navbar;

        $this->notYetImplemented();
    }

    public function renderProfile()
    {
        $navbar   = [];
        $navbar[] = (object) ['name' => 'Uživatelé', 'link' => 'Users:list'];
        $navbar[] = (object) ['name' => 'Profil'];

        $this->template->navbar = $navbar;

        $id = ($this->getParameter('id') !== null) ? $this->getParameter('id') : $this->getUser()->getIdentity()->id;

        $user        = $this->em->getRepository(PModel\User\User::getClassName());
        $profileUser = $user->find($id);

        $this->template->profileUser = $profileUser;

        $isActual = false;
        if ($this->getUser()->isLoggedIn() && $this->getUser()->getIdentity()->id
            == $profileUser->id) {
            $isActual = true;
        }

        $this->template->isActual = $isActual;
    }

    public function renderGroups()
    {
        $navbar   = [];
        $navbar[] = (object) ['name' => 'Uživatelé', 'link' => 'Users:list'];
        $navbar[] = (object) ['name' => 'Skupiny'];

        $this->template->navbar = $navbar;

        $userGroupsDao = $this->em->getRepository(PModel\User\Group::getClassName());
        $userGroups    = $userGroupsDao->findAll();

        $this->template->userGroups = $userGroups;
    }

    /**
     * @Secured
     * @Secured\User(loggedIn)
     * @Secured\Role(superadmin)
     */
    public function renderLogActivity()
    {
        $navbar   = [];
        $navbar[] = (object) ['link' => 'Users:list', 'name' => 'Uživatelé'];
        $navbar[] = (object) ['name' => 'Sledování přihlášení'];

        $this->template->navbar = $navbar;

        $activityDao = $this->em->getRepository(PModel\User\LogActivity::getClassName());
        $activity    = $activityDao->findBy([], ['id' => 'DESC'], 30);

        $this->template->activity = $activity;
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
            ->addRule(
                Form::EQUAL,
                "Zadaná nová hesla se neshodují.",
                $form["newpassw"]
            )
            ->getControlPrototype()->class('form-control');

        $form->addSubmit('send', 'Změnit heslo')
            ->getControlPrototype()->class('btn btn-success');

        $form->onSuccess[] = [$this, 'changePasswordFormSucceeded'];
        return $form;
    }

    protected function createComponentChangeProfileForm()
    {
        $userDao = $this->em->getRepository(PModel\User\User::getClassName());
        $user    = $userDao->find($this->getUser()->id);

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

        $form->onSuccess[] = [$this, 'changeProfileFormSucceeded'];
        return $form;
    }

    public function createComponentUsersNewForm()
    {
        $form = new Form;

        $form->addText('login', 'Přihlašovací jméno:')
            ->setRequired('Prosím, zadejte přihlašovací jméno.')
            ->getControlPrototype()->class('form-control');

        $form->addText('firstname', 'Jméno:')
            ->setRequired('Prosím, zadejte jméno.')
            ->getControlPrototype()->class('form-control');

        $form->addText('lastname', 'Příjmení:')
            ->setRequired('Prosím, zadejte příjmení.')
            ->getControlPrototype()->class('form-control');

        $form->addText('email', 'Email:')
            ->setRequired('Prosím, zadejte emailovou adesu.')
            ->getControlPrototype()->class('form-control mediumwidth');

        $form->addPassword('password', 'Heslo:')
            ->setRequired('Prosím, zadejte heslo.')
            ->getControlPrototype()->class('form-control mediumwidth');

        $form->addPassword('passwordValid', 'Heslo znovu:')
            ->setRequired('Prosím, zadejte heslo pro kontrolu.')
            ->getControlPrototype()->class('form-control mediumwidth');

        $form->addSubmit('send', 'Vytvořit uživatelský profil')
            ->getControlPrototype()->class('btn btn-success');

        $form->onSuccess[] = [$this, 'usersNewFormSucceeded'];
        return $form;
    }

    public function createComponentUsersChangeForm()
    {
        $userDao = $this->em->getRepository(PModel\User\User::getClassName());
        $user    = $userDao->find($this->getUser()->id);

        $form = new Form;

        $form->addText('login', 'Přihlašovací jméno:')
            ->setRequired('Prosím, zadejte přihlašovací jméno.')
            ->setDisabled()
            ->setValue($user->username)
            ->getControlPrototype()->class('form-control');

        $form->addText('firstname', 'Jméno:')
            ->setRequired('Prosím, zadejte jméno.')
            ->setDefaultValue($user->firstName)
            ->getControlPrototype()->class('form-control');

        $form->addText('lastname', 'Příjmení:')
            ->setRequired('Prosím, zadejte příjmení.')
            ->setDefaultValue($user->lastName)
            ->getControlPrototype()->class('form-control');

        $form->addText('email', 'Email:')
            ->setRequired('Prosím, zadejte emailovou adesu.')
            ->setDefaultValue($user->email)
            ->getControlPrototype()->class('form-control mediumwidth');

        $form->addSubmit('send', 'Upravit uživatelský profil')
            ->getControlPrototype()->class('btn btn-warning');

        $form->onSuccess[] = [$this, 'usersNewFormSucceeded'];
        return $form;
    }

    public function usersNewFormSucceeded()
    {
    }

    public function handleActive($id)
    {
        $this->setActiveStatus($id, true);

        $this->flashMessage(
            'Uživatel s ID '.$id.' byl úspěšně aktivován.',
            'success'
        );
        $this->redirect('this');
    }

    public function handleDeactive($id)
    {
        $this->setActiveStatus($id, false);

        $this->flashMessage(
            'Uživatel s ID '.$id.' byl úspěšně deaktivován.',
            'success'
        );
        $this->redirect('this');
    }

    /**
     * @param boolean $status
     */
    private function setActiveStatus($id, $status)
    {
        $userDao      = $this->em->getRepository(PModel\User\User::getClassName());
        $user         = $userDao->find($id);
        $user->active = (bool) $status;

        $this->em->persist($user);
        $this->em->flush();

        return true;
    }

    public function changeProfileFormSucceeded($form)
    {
        $values = $form->getValues(true);

        $userDao         = $this->em->getRepository(PModel\User\User::getClassName());
        $user            = $userDao->find($this->getUser()->id);
        $user->firstName = $values['firstname'];
        $user->lastName  = $values['lastname'];
        $user->email     = $values['email'];

        $this->em->persist($user);
        $this->em->flush();

        $this->flashMessage('Údaje o uživateli byly úspěšně uloženy.', 'success');
        $this->redirect('Users:profile');
    }

    public function changePasswordFormSucceeded($form)
    {
        $values = $form->getValues();
        $userId = $this->getUser()->id;

        $fOldPass = $values["oldpassw"];
        $fNewPass = $values["newpassw"];

        try {
            $this->getUser()->getAuthenticator()->changePassword(
                $userId,
                $fOldPass,
                $fNewPass
            );

            $this->flashMessage('Vaše heslo bylo úspěšně změněno.', 'success');
            $this->redirect('Users:profile');
        } catch (Nette\Security\AuthenticationException $e) {
            $form->addError('Zadané staré heslo není správné.');
        }
    }
}
