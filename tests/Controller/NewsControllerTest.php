<?php

namespace App\Tests\Controller;

use App\Entity\Category;
use App\Entity\News;
use App\Tests\DataFixtures\ConfigurableFixture;
use App\Tests\Utils\FixtureAwareTestCase;
use Dsarhoya\FeatureFixtures\GenericFixtureBuilder;

class NewsControllerTest extends FixtureAwareTestCase
{
  /** @var DSYClient */
  private $apiClient;

  /** @var EntityManagerInterface */
  private $em;

  public function setUp(): void
  {
    parent::setUp();

    $this->apiClient = $this->makeApiClient('');

    $this->em = self::$kernel->getContainer()->get('doctrine')->getManager();
  }

  public function testListNews(): void
  {
    // Cargar fixtures
    $builders = $this->getBuilders();
    $this->addFixture(ConfigurableFixture::new()->config($builders));
    $this->executeFixtures();

    // Simular una petición GET a la lista de noticias
    $this->apiClient->getJson('/api/1/news');

    // Verificar que la respuesta es exitosa (código 200)
    $this->assertSame(200, $this->apiClient->getResponse()->getStatusCode(), 'La respuesta de la api debe ser 200');

    // Verificar que la respuesta contiene las noticias
    $response = $this->apiClient->getResponseAsArray();
    $this->assertCount(1, $response, 'Debe haber 1 noticia en la lista');
  }

  public function testDetailNews(): void
  {
    // Cargar fixtures
    $builders = $this->getBuilders();
    $this->addFixture(ConfigurableFixture::new()->config($builders));
    $this->executeFixtures();

    /** @var News */
    $news = $this->getReferenceRepository()->getReference('news-1', News::class);

    // Simular una petición GET a los detalles de la noticia
    $this->apiClient->getJson('/api/1/news/' . $news->getId());

    // Verificar que la respuesta es exitosa (código 200)
    $this->assertSame(200, $this->apiClient->getResponse()->getStatusCode(), 'La respuesta de la api debe ser 200');

    // Verificar que la respuesta contiene los detalles de la noticia
    $response = $this->apiClient->getResponseAsArray();
    $this->assertEquals($news->getId(), $response['id'], 'El ID de la noticia debe coincidir');
  }

  public function testCreateNews(): void
  {
    // Cargar fixtures
    $builders = $this->getBuilders();
    $this->addFixture(ConfigurableFixture::new()->config($builders));
    $this->executeFixtures();

    /** @var Category */
    $category = $this->getReferenceRepository()->getReference('category-1');

    // Simular una petición POST para crear una noticia
    $data = [
      'title' => 'New News Title',
      'description' => 'New News Description',
      'category' => $category->getId(),
      'enabled' => true,
    ];

    $this->apiClient->postJson('/api/1/news', $data);
    $response = $this->apiClient->getResponse();

    // Debug the response if the status code is not 201
    if ($response->getStatusCode() !== 201) {
      var_dump($response);
    }

    
    // Verificar que la noticia se ha creado en la base de datos
    $news = $this->em->getRepository(News::class)->findOneBy(['title' => 'New News Title']);
    $this->assertNotNull($news, 'La noticia debe haberse creado en la base de datos');

    // Verificar que la respuesta es exitosa (código 201)
    $this->assertSame(201, $response->getStatusCode(), 'La respuesta de la api debe ser 201');
  }



  public function testDeleteNews(): void
  {
    $builders = $this->getBuilders();
    $this->addFixture(ConfigurableFixture::new()->config($builders));
    $this->executeFixtures();

    /** @var News */
    $news = $this->getReferenceRepository()->getReference('news-1');
    dd($builders);

    // Ensure the news entity has a valid ID
    $this->assertNotNull($news->getId(), 'La nueva entidad debe tener un ID válido antes de eliminarlo.');

    // Simular una petición DELETE para eliminar la noticia
    $this->apiClient->delete('/api/1/news/' . $news->getId());

    // Verificar que la respuesta es exitosa (código 204)
    $this->assertSame(204, $this->apiClient->getResponse()->getStatusCode(), 'La respuesta de la api debe ser 204');

    // Verificar que la noticia se ha eliminado de la base de datos
    $deletedNews = $this->em->getRepository(News::class)
      ->find($news->getId());
    $this->assertNull($deletedNews, 'La noticia debe haberse eliminado de la base de datos');
  }

  private function getBuilders(): array
  {
    return [
      GenericFixtureBuilder::config(Category::class, [
        'reference' => 'category-1',
        'name' => 'Category Name',
        'description' => 'Category Description',
      ]),
      GenericFixtureBuilder::config(News::class, [
        'reference' => 'news-1',
        'title' => 'News Title',
        'description' => 'News Description',
        'category' => 'ref-category-1',
        'enabled' => true,
      ]),
    ];
  }
}
