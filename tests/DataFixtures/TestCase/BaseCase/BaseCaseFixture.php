<?php

namespace App\Tests\DataFixtures\TestCase\BaseCase;

use App\Entity\Company;
use App\Entity\User;
use Dsarhoya\FeatureFixtures\GenericFixtureBuilder;

class BaseCaseFixture
{
  public const COMPANY_REF = 'company-1';
  public const USER_REF = 'user-1';
  public const BO_REF = 'branchoffice-1';
  public const SITE_REF = 'site-1';
  public const REF_PREFIX = 'ref-';

  public static function get(): array
  {
    return [
      GenericFixtureBuilder::config(Company::class, [
        'reference' => self::COMPANY_REF,
        'name' => 'dsy',
        'rut' => '1-9',
        'state' => 1,
      ]),
      GenericFixtureBuilder::config(User::class, [
        'reference' => self::USER_REF,
        'company' => self::REF_PREFIX . self::COMPANY_REF,
        'name' => 'usuario1',
        'email' => 'usuario@dsy.cl',
        'role' => User::ROLE_SUPER_ADMIN,
        'rut' => '1-9',
        'isCuasiAdmin' => true,
      ]),
    ];
  }
}
