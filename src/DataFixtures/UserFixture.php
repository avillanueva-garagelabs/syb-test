<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Company;
use App\Entity\Profile;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Crear una compañía (si no existe)
        $company = new Company();
        $company->setName('Super Company');
        $company->setState(1); // Estado activo
        $manager->persist($company);

        // Crear un perfil de super administrador
        $profile = new Profile();
        $profile->setName('Super Admin');
        $profile->setIsAdmin(true);
        $profile->setCompany($company);
        $manager->persist($profile);

        // Crear el super usuario
        $user = new User();
        $user->setName('superadmin');
        $user->setEmail('superadmin@example.com');
        // $user->setRoles(['ROLE_SUPER_ADMIN']);
        $user->setState(User::ESTADO_ACTIVO);
        $user->setAccountValidated(User::ACCOUNT_VALIDATED);
        $user->setCompany($company);

        // Hashear la contraseña
        $password = $this->passwordHasher->hashPassword($user, 'password');
        $user->setPassword($password);

        // Guardar el usuario
        $manager->persist($user);
        $manager->flush();
    }
}
