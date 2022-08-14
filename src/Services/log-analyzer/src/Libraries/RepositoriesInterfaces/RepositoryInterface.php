<?php

declare(strict_types=1);

namespace App\Services\LogAnalyzer\Libraries\RepositoriesInterfaces;

interface RepositoryInterface
{
    public function find($id, $lockMode = null, $lockVersion = null);
    public function findOneBy(array $criteria, array $orderBy = null);
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null);
    public function findAll();
    public function save($entity);
}
