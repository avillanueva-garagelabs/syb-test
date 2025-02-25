<?php

namespace App\Tests\DataFixtures;

use Dsarhoya\FeatureFixtures\AbstractCase;

class ConfigurableFixture extends AbstractCase
{
  protected $config = [];

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
}
