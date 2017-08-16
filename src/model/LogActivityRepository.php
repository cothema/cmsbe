<?php

namespace App\Cothema\Admin;

/**
 *
 * @author Miloš Havlíček <miloshavlicek@gmail.com>
 */
class LogActivityRepository
{

    public static function logActivity($em, $userId)
    {
        $activity = self::getActivityIfExists($em, $userId);

        if (isset($activity[0])) {
            $activity[0]->timeTo = new \DateTime();
            $em->persist($activity[0]);
        } else {
            $newActivity = new LogActivity;
            $newActivity->user = $userId;
            $newActivity->timeFrom = new \DateTime();
            $newActivity->timeTo = new \DateTime();
            $em->persist($newActivity);
        }

        $em->flush();
    }

    private static function getActivityIfExists($em, $userId)
    {
        $qb = $em->createQueryBuilder();
        $qb->select('a');
        $qb->from(LogActivity::class, 'a');
        $qb->where('a.user = :user AND a.timeTo >= :timeTo');
        $qb->orderBy('a.id', 'DESC');
        $qb->setMaxResults(1);
        $qb->setParameter('user', $userId);
        $qb->setParameter('timeTo', new \DateTime('-10 minutes'));

        return $qb->getQuery()->getResult();
    }
}
