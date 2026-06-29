<?php

namespace App\Domain\Equipment\Repository;

use App\Domain\Equipment\Entity\Battery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Battery>
 */
class BatteryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Battery::class);
    }

    /**
     * All active battery settings (the command then filters on the per-row
     * frequency to decide which reminders are due).
     *
     * @return Battery[]
     */
    public function findActive(): array
    {
        return $this->createQueryBuilder("b")
            ->andWhere("b.isActive = true")
            ->getQuery()
            ->getResult();
    }
}
