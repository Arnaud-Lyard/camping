<?php

namespace App\Domain\Equipment\Repository;

use App\Domain\Auth\Entity\User;
use App\Domain\Equipment\Entity\Equipment;
use App\Domain\Equipment\Enum\EquipmentStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Equipment>
 */
class EquipmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Equipment::class);
    }

    /**
     * Items of the given owner, ordered with "in progress" first, "completed"
     * last, then by the manual order (ordre), then newest first as a tie-breaker.
     *
     * @return Equipment[]
     */
    public function findOrdered(User $owner, ?EquipmentStatus $status = null): array
    {
        $qb = $this->createQueryBuilder("e")
            ->addSelect("CASE WHEN e.status = :inProgress THEN 0 ELSE 1 END AS HIDDEN statusOrder")
            ->andWhere("e.owner = :owner")
            ->setParameter("owner", $owner)
            ->setParameter("inProgress", EquipmentStatus::InProgress)
            ->orderBy("statusOrder", "ASC")
            ->addOrderBy("e.ordre", "ASC")
            ->addOrderBy("e.createdAt", "DESC");

        if (null !== $status) {
            $qb->andWhere("e.status = :status")->setParameter("status", $status);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Shift every item of the owner down by $by positions, freeing the top
     * slots (0..$by-1) for newly created items. Keeps "ordre" a positive,
     * contiguous sequence where 0 is the top of the list.
     */
    public function shiftDown(User $owner, int $by = 1): void
    {
        $this->getEntityManager()
            ->createQueryBuilder()
            ->update(Equipment::class, "e")
            ->set("e.ordre", "e.ordre + :by")
            ->where("e.owner = :owner")
            ->setParameter("by", $by)
            ->setParameter("owner", $owner)
            ->getQuery()
            ->execute();
    }
}
