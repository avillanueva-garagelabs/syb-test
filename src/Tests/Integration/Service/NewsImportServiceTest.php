<?php

namespace App\Tests\Integration\Service;

use App\Entity\Category;
use App\Entity\News;
use App\Service\NewsImportService;
use App\Tests\Utils\FixtureAwareTestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class NewsImportServiceTest extends FixtureAwareTestCase
{
  /* private NewsImportService $newsImportService;
  private HttpClientInterface $httpClient;

  public function setUp(): void
  {
    parent::setUp();

    $this->httpClient = static::getContainer()->get(HttpClientInterface::class);
    $this->newsImportService = new NewsImportService($this->httpClient, $this->getEntityManager());

    // Cargar la categoría Deportes
    $sportsCategory = new Category();
    $sportsCategory->setName('Deportes');
    $sportsCategory->setDescription('Noticias de deportes');

    $this->getEntityManager()->persist($sportsCategory);
    $this->getEntityManager()->flush();
  }

  public function testImportSportsNews(): void
  {
    $sportsCategory = $this->getEntityManager()->getRepository(Category::class)->findOneBy(['name' => 'Deportes']);

    $this->newsImportService->importSportsNews($sportsCategory);

    $news = $this->getEntityManager()->getRepository(News::class)->findAll();
    $this->assertCount(1, $news);
    $this->assertEquals('Noticia 1', $news[0]->getTitle());
  } */
}
