<?php

namespace App\Tests\Controller;

use App\Entity\Category;
use App\Tests\DataFixtures\ConfigurableFixture;
use App\Tests\Utils\FixtureAwareTestCase;
use Dsarhoya\FeatureFixtures\GenericFixtureBuilder;

class CategoryControllerTest extends FixtureAwareTestCase
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

  public function testListCategories(): void
  {
    // Cargar fixtures
    $builders = $this->getBuilders();
    $this->addFixture(ConfigurableFixture::new()->config($builders));
    $this->executeFixtures();

    // Simular una petición GET a la lista de categorías
    $this->apiClient->getJson('/api/1/categories');

    // Verificar que la respuesta es exitosa (código 200)
    $this->assertSame(200, $this->apiClient->getResponse()->getStatusCode(), 'La respuesta de la api debe ser 200');

    // Verificar que la respuesta contiene las categorías
    $response = $this->apiClient->getResponseAsArray();
    $this->assertCount(1, $response, 'Debe haber 1 categoría en la lista');
  }

  public function testDetailCategory(): void
  {
    // Cargar fixtures
    $builders = $this->getBuilders();
    $this->addFixture(ConfigurableFixture::new()->config($builders));
    $this->executeFixtures();

    /** @var Category */
    $category = $this->getReferenceRepository()->getReference('category-1', Category::class);

    // Simular una petición GET a los detalles de la categoría
    $this->apiClient->getJson('/api/1/categories/' . $category->getId());

    // Verificar que la respuesta es exitosa (código 200)
    $this->assertSame(200, $this->apiClient->getResponse()->getStatusCode(), 'La respuesta de la api debe ser 200');

    // Verificar que la respuesta contiene los detalles de la categoría
    $response = $this->apiClient->getResponseAsArray();
    $this->assertEquals($category->getId(), $response['id'], 'El ID de la categoría debe coincidir');
  }

  public function testCreateCategory(): void
  {
    // Simular una petición POST para crear una categoría
    $data = json_encode([
      'name' => 'New Category',
      'description' => 'New Description',
    ]);

    $this->apiClient->postJson('/api/1/categories', $data);

    // Verificar que la respuesta es exitosa (código 201)
    $this->assertSame(201, $this->apiClient->getResponse()->getStatusCode(), 'La respuesta de la api debe ser 201');

    // Verificar que la categoría se ha creado en la base de datos
    $category = $this->em->getRepository(Category::class)->findOneBy(['name' => 'New Category']);
    $this->assertNotNull($category, 'La categoría debe haberse creado en la base de datos');
  }

  public function testUpdateCategory(): void
  {
    // Cargar fixtures
    $builders = $this->getBuilders();
    $this->addFixture(ConfigurableFixture::new()->config($builders));
    $this->executeFixtures();

    /** @var Category */
    $category = $this->getReferenceRepository()->getReference('category-1', Category::class);

    // Simular una petición PUT para actualizar la categoría
    $data = json_encode([
      'name' => 'Updated Category',
      'description' => 'Updated Description',
    ]);

    $this->apiClient->putJson('/api/1/categories/' . $category->getId(), $data);

    // Verificar que la respuesta es exitosa (código 200)
    $this->assertSame(200, $this->apiClient->getResponse()->getStatusCode(), 'La respuesta de la api debe ser 200');

    // Verificar que la categoría se ha actualizado en la base de datos
    $updatedCategory = $this->em->getRepository(Category::class)->find($category->getId());
    $this->assertEquals('Updated Category', $updatedCategory->getName(), 'El nombre de la categoría debe haberse actualizado');
  }

  public function testDeleteCategory(): void
  {
    // Cargar fixtures
    $builders = $this->getBuilders();
    $this->addFixture(ConfigurableFixture::new()->config($builders));
    $this->executeFixtures();

    /** @var Category */
    $category = $this->getReferenceRepository()->getReference('category-1', Category::class);

    // Simular una petición DELETE para eliminar la categoría
    $this->apiClient->delete('/api/1/categories/' . $category->getId());

    // Verificar que la respuesta es exitosa (código 204)
    $this->assertSame(204, $this->apiClient->getResponse()->getStatusCode(), 'La respuesta de la api debe ser 204');

    // Verificar que la categoría se ha eliminado de la base de datos
    $deletedCategory = $this->em->getRepository(Category::class)->find($category->getId());
    $this->assertNull($deletedCategory, 'La categoría debe haberse eliminado de la base de datos');
  }

  private function getBuilders(): array
  {
    return [
      GenericFixtureBuilder::config(Category::class, [
        'reference' => 'category-1',
        'name' => 'Category Name',
        'description' => 'Category Description',
      ]),
    ];
  }
}
