<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * ProjectRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProjectRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Project::class);
    }

    public function fulltextQuery($q) {
        $qb = $this->createQueryBuilder('e');
        $qb->addSelect('MATCH_AGAINST (e.title, e.content) AGAINST(:q BOOLEAN) as HIDDEN score');
        $qb->andWhere('MATCH_AGAINST (e.title, e.content) AGAINST(:q BOOLEAN) > 0');

        $qb->innerJoin('e.projectPages', 'p');
        $qb->orWhere('MATCH_AGAINST (p.title, p.content) AGAINST(:q BOOLEAN) > 0');

        $qb->orderBy('score', 'desc');
        $qb->setParameter('q', $q);

        return $qb->getQuery();
    }

    public function typeaheadQuery($q) {
        $qb = $this->createQueryBuilder('e');
        $qb->andWhere('e.title LIKE :q');
        $qb->orderBy('e.title');
        $qb->setParameter('q', "{$q}%");

        return $qb->getQuery()->execute();
    }
}
