<?php

namespace App\Presenters;

use App\ORM\Sys\Pinned;

/**
 * @Secured
 * @Secured\User(loggedIn)
 * @Secured\Role(admin)
 * @author     Milos Havlicek <miloshavlicek@gmail.com>
 *
 * Homepage Presenter
 */
final class HomepagePresenter extends BasePresenter
{

    public function renderDefault()
    {
        $this->template->pins = $this->getAllPins();
    }

    private function getAllPins(): array
    {
        $dao = $this->em->getRepository(Pinned::class);
        $pinned = $dao->findBy(['user' => $this->user->id]);

        $out = [];
        foreach ($pinned as $pinnedOne) {
            try {
                $this->getPresenter()->createRequest($this, $pinnedOne->page, [], 'link');

                $out[] = $pinnedOne;
            } catch (\Exception $e) {
                if (DEV_MODE) {
                    $this->flashMessage(sprintf('%s: %s', 'getAllPins', $e->getMessage()), 'warning');
                } else {
                    // TODO: log and send notification
                }
            }
        }

        return $out;
    }
}
