<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository {
  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, Category::class);
  }

  // Aquí puedes agregar métodos personalizados para realizar consultas más complejas
  public function findWithMostNews() {
    return $this->createQueryBuilder('c')
      ->leftJoin('c.news', 'n')
      ->groupBy('c')
      ->orderBy('COUNT(n)', 'DESC')
      ->getQuery()
      ->getResult();
  }
}
