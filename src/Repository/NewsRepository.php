<?php

namespace App\Repository;

use App\Entity\News;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method News|null find($id, $lockMode = null, $lockVersion = null)
 * @method News|null findOneBy(array $criteria, array $orderBy = null)
 * @method News[]    findAll()
 * @method News[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsRepository extends ServiceEntityRepository {
  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, News::class);
  }

  // Aquí puedes agregar métodos personalizados para realizar consultas más complejas
  public function findPublishedNews() {
    return $this->createQueryBuilder('n')
      ->where('n.publishedAt <= :now')
      ->andWhere('n.enabled = true')
      ->setParameter('now', new \DateTimeImmutable())
      ->orderBy('n.publishedAt', 'DESC')
      ->getQuery()
      ->getResult();
  }
}
