<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use dsarhoya\BaseBundle\Repository\BaseUserRepository;
use dsarhoya\BaseBundle\Services\ParametersService;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends BaseUserRepository
{
    public function __construct(ManagerRegistry $registry, ParametersService $service)
    {
        parent::__construct($registry, $service);
    }
}