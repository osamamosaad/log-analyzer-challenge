<?php

declare(strict_types=1);

namespace App\Services\LogAnalyzer\Infrastructure\Repositories;

use App\Repositories\BaseRepository;
use App\Services\LogAnalyzer\Infrastructure\Entities\TransactionLog;
use App\Services\LogAnalyzer\Libraries\{
    RepositoriesInterfaces\RepositoryInterface,
    RepositoriesInterfaces\TransactionLogRepositoryInterface,
};

/**
 * @extends ServiceEntityRepository<TransactionLog>
 *
 * @method TransactionLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method TransactionLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method TransactionLog[]    findAll()
 * @method TransactionLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionLogRepository extends BaseRepository implements TransactionLogRepositoryInterface, RepositoryInterface
{
    protected function entityClass(): string
    {
        return TransactionLog::class;
    }

    public function findByFilter(array $filter): ?array
    {
        $qb = $this->createQueryBuilder('t');
        $qb->select("count(t) as counter");

        if (!empty($filter["serviceNames"])) {
            $qb->andWhere("t.serviceName in(:serviceNames)")
                ->setParameter("serviceNames", $filter["serviceNames"]);
        }

        if (!empty($filter["statusCode"])) {
            $qb->andWhere("t.statusCode = :statusCode")
                ->setParameter("statusCode", $filter["statusCode"]);
        }

        if (!empty($filter["startDate"]) && !empty($filter["endDate"])) {
            $qb->andWhere("t.logDate BETWEEN :startDate AND :endDate")
                ->setParameter("startDate", $filter["startDate"])
                ->setParameter("endDate", $filter["endDate"]);
        } else if (!empty($filter["endDate"])) {
            $qb->andWhere("t.logDate <= :endDate")
                ->setParameter("endDate", $filter["endDate"]);
        } else if (!empty($filter["startDate"])) {
            $qb->andWhere("t.logDate >= :startDate")
                ->setParameter("startDate", $filter["startDate"]);
        }

        return $qb->getQuery()->getOneOrNullResult();
    }
}
