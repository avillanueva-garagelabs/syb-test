<?php

namespace App\Tests\DataFixtures\TestCase;

use App\Tests\DataFixtures\TestCase\BaseCase\BaseCaseFixture;

class DataFixtureBuilder
{
  private $fixtures = [];

  public static function build(): self
  {
    return new self();
  }

  public function addBaseCase(): self
  {
    $this->fixtures = array_merge($this->fixtures, BaseCaseFixture::get());
    return $this;
  }

  public function getFixtures()
  {
    return $this->fixtures;
  }

  public function addCasesConfigs(...$cases): self
  {
    foreach ($cases as $case) {
      $this->fixtures = array_merge($this->fixtures, $case);
    }
    return $this;
  }
}
