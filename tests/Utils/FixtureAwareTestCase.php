<?php

namespace App\Tests\Utils;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader;

abstract class FixtureAwareTestCase extends WebTestCase
{
  use DSYClientTrait;

  private $fixtureExecutor;
  private $fixtureLoader;

  public function setUp(): void
  {
    self::bootKernel();
  }

  protected function addFixture(FixtureInterface $fixture)
  {
    $this->getPrivateFixtureLoader()->addFixture($fixture);
  }

  protected function executeFixtures()
  {
    $this->getFixtureExecutor()->execute($this->getPrivateFixtureLoader()->getFixtures());
  }

  private function getFixtureExecutor()
  {
    if (!$this->fixtureExecutor) {
      $entityManager = self::$kernel->getContainer()->get('doctrine')->getManager();
      $this->fixtureExecutor = new ORMExecutor($entityManager, new ORMPurger($entityManager));
    }
    return $this->fixtureExecutor;
  }

  protected function getPrivateFixtureLoader()
  {
    if (!$this->fixtureLoader) {
      $this->fixtureLoader = new ContainerAwareLoader(self::$kernel->getContainer());
    }
    return $this->fixtureLoader;
  }

  public function getReferenceRepository()
  {
    return $this->fixtureExecutor->getReferenceRepository();
  }
}
