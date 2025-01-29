<?php

namespace App\Repository;

use App\Entity\Profile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use dsarhoya\BaseBundle\Repository\BaseProfileRepository;
use dsarhoya\BaseBundle\Services\ParametersService;

/**
 * @extends ServiceEntityRepository<Profile>
 *
 * @method Profile|null find($id, $lockMode = null, $lockVersion = null)
 * @method Profile|null findOneBy(array $criteria, array $orderBy = null)
 * @method Profile[]    findAll()
 * @method Profile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfileRepository extends BaseProfileRepository
{
    public function __construct(ManagerRegistry $registry, ParametersService $service)
    {
        parent::__construct($registry, $service);
    }
}