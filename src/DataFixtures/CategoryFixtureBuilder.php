<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Dsarhoya\FeatureFixtures\GenericFixtureBuilder;

class CategoryFixtureBuilder extends GenericFixtureBuilder
{
  private static $index = 1;

  public function getClass()
  {
    return Category::class;
  }

  public function getData()
  {
    return array_merge([
      'name' => 'Category Name ' . self::$index,
      'description' => 'Category Description ' . self::$index,
    ], $this->data);
  }

  protected function createInstance(): object
  {
    ++self::$index;
    return parent::createInstance();
  }
}
