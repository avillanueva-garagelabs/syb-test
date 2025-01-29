<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use dsarhoya\BaseBundle\Entity\BaseCompany;

/**
* Company
*
* @ORM\Table()
* @ORM\Entity(repositoryClass="App\Repository\CompanyRepository")
*/
class Company extends BaseCompany
{
    /**
    * @ORM\OneToMany(targetEntity="Profile", mappedBy="company")
    */
    private $profiles;

    /**
    * @ORM\OneToMany(targetEntity="User", mappedBy="company")
    */
    private $users;

    public function __construct()
    {
        parent::__construct();
        $this->profiles = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    /**
     * @return Collection<int, Profile>
     */
    public function getProfiles(): Collection
    {
        return $this->profiles;
    }

    public function addProfile(Profile $profile): static
    {
        if (!$this->profiles->contains($profile)) {
            $this->profiles->add($profile);
            $profile->setCompany($this);
        }

        return $this;
    }

    public function removeProfile(Profile $profile): static
    {
        if ($this->profiles->removeElement($profile)) {
            // set the owning side to null (unless already changed)
            if ($profile->getCompany() === $this) {
                $profile->setCompany(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setCompany($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getCompany() === $this) {
                $user->setCompany(null);
            }
        }

        return $this;
    }
}