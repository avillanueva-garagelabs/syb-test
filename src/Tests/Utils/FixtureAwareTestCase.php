<?php

namespace App\Tests\Utils;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader;

abstract class FixtureAwareTestCase extends WebTestCase
{
  use DSYClientTrait;

  /**
   * @var ORMExecutor
   */
  private $fixtureExecutor;

  /**
   * @var ContainerAwareLoader
   */
  private $fixtureLoader;

  public function setUp(): void
  {
    self::bootKernel();
  }

  /**
   * Adds a new fixture to be loaded.
   */
  protected function addFixture(FixtureInterface $fixture)
  {
    $this->getPrivateFixtureLoader()->addFixture($fixture);
  }

  /**
   * Executes all the fixtures that have been loaded so far.
   */
  protected function executeFixtures()
  {
    $this->getFixtureExecutor()->execute($this->getPrivateFixtureLoader()->getFixtures());
  }

  /**
   * @return ORMExecutor
   */
  private function getFixtureExecutor()
  {
    if (!$this->fixtureExecutor) {
      /** @var \Doctrine\ORM\EntityManager $entityManager */
      $entityManager = self::$kernel->getContainer()->get('doctrine')->getManager();

      $this->fixtureExecutor = new ORMExecutor($entityManager, new ORMPurger($entityManager));
    }

    return $this->fixtureExecutor;
  }

  /**
   * @return ContainerAwareLoader
   */
  protected function getPrivateFixtureLoader()
  {
    if (!$this->fixtureLoader) {
      $this->fixtureLoader = new ContainerAwareLoader(self::$kernel->getContainer());
    }

    return $this->fixtureLoader;
  }

  /**
   * @return ReferenceRepository
   */
  public function getReferenceRepository()
  {
    return $this->fixtureExecutor->getReferenceRepository();
  }
}
