<?php

namespace App\DataFixtures;

use App\Entity\News;
use Dsarhoya\FeatureFixtures\GenericFixtureBuilder;

class NewsFixtureBuilder extends GenericFixtureBuilder
{
  private static $index = 1;

  public function getClass()
  {
    return News::class;
  }

  public function getData()
  {
    return array_merge([
      'title' => 'News Title ' . self::$index,
      'description' => 'News Description ' . self::$index,
      'publicationDate' => new \DateTime(),
      'enabled' => true,
    ], $this->data);
  }

  protected function createInstance(): object
  {
    ++self::$index;
    return parent::createInstance();
  }
}
