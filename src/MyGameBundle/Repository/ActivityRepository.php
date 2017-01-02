<?php

namespace MyGameBundle\Repository;

/**
 * ActivityRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ActivityRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllActivity($id, $limit = null){
        return $this->getEntityManager()->createQuery(
            'SELECT a, w, l
            FROM MyGameBundle\Entity\Activity a
            INNER JOIN a.winner w
            INNER JOIN a.loser l
            WHERE w.id = :id OR l.id = :id
            ORDER BY a.time ASC'
        )->setParameter('id', $id)->setMaxResults($limit)->getResult();
    }
}