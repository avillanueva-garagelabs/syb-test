<?php

namespace App\Tests\DataFixtures;

use Dsarhoya\FeatureFixtures\DependentAbstractCase;

class ConfigurableDependentFixture extends DependentAbstractCase
{
  protected $config = [];
  protected $dependencies = [];

  public static function new()
  {
    return new static();
  }

  public function config(array $config)
  {
    $this->config = $config;
    return $this;
  }

  protected function getConfiguration()
  {
    return $this->config;
  }

  public function dependesOn(array $dependencies)
  {
    $this->dependencies = $dependencies;
    return $this;
  }

  public function getDependencies(): array
  {
    return $this->dependencies;
  }
}
