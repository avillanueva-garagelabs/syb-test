<?php

namespace App\Service;

use App\Entity\News;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class NewsImportService
{
  private HttpClientInterface $httpClient;
  private EntityManagerInterface $entityManager;

  public function __construct(HttpClientInterface $httpClient, EntityManagerInterface $entityManager)
  {
    $this->httpClient = $httpClient;
    $this->entityManager = $entityManager;
  }

  public function importSportsNews(Category $sportsCategory): void
  {
    $response = $this->httpClient->request('GET', 'http://wiremock:8080/api/posts', [
      'query' => ['category_name' => 'sports']
    ]);

    $posts = $response->toArray();

    foreach ($posts as $post) {
      $news = new News();
      $news->setTitle($post['title']);
      $news->setPublicationDate(new \DateTime($post['date']));
      $news->setDescription($post['summary']);
      $news->setCategory($sportsCategory);

      $this->entityManager->persist($news);
    }

    $this->entityManager->flush();
  }
}
