<?php

declare(strict_types=1);

namespace App\Services\LogAnalyzer\Infrastructure\Repositories;

use App\Repositories\BaseRepository;
use App\Services\LogAnalyzer\Infrastructure\Entities\LogFile;
use App\Services\LogAnalyzer\Libraries\{
    RepositoriesInterfaces\LogFileRepositoryInterface,
    RepositoriesInterfaces\RepositoryInterface,
};

/**
 * @extends ServiceEntityRepository<LogFile>
 *
 * @method LogFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method LogFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method LogFile[]    findAll()
 * @method LogFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LogFileRepository extends BaseRepository implements LogFileRepositoryInterface, RepositoryInterface
{
    protected function entityClass(): string
    {
        return LogFile::class;
    }

    public function getByUniqueName(string $uniqueName): ?LogFile
    {
        return $this->findOneBy([
            "uniqueName" => $uniqueName,
        ]);
    }
}
