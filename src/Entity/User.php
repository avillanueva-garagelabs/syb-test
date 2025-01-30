<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use dsarhoya\BaseBundle\Entity\BaseUser;
use dsarhoya\BaseBundle\Entity\UserKey;
use dsarhoya\BaseBundle\Model\EntityMappers\BaseUserInterface;

#[ORM\Table]
#[ORM\Entity(repositoryClass: 'App\Repository\UserRepository')]
class User extends BaseUser implements BaseUserInterface
{
    #[ORM\OneToMany(targetEntity: UserKey::class, mappedBy: 'user')]
    private Collection $keys;

    #[ORM\ManyToOne(targetEntity: Company::class, inversedBy: 'users')]
    #[ORM\JoinColumn(name: 'company_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private ?Company $company = null; // Initialize to null

    public function __construct()
    {
        parent::__construct();
        $this->keys = new ArrayCollection();
    }

    /**
     * @return Collection<int, UserKey>
     */
    public function getKeys(): Collection
    {
        return $this->keys;
    }

    public function addKey(UserKey $key): static
    {
        if (!$this->keys->contains($key)) {
            $this->keys->add($key);
            $key->setUser($this);
        }

        return $this;
    }

    public function removeKey(UserKey $key): static
    {
        if ($this->keys->removeElement($key)) {
            if ($key->getUser() === $this) {
                $key->setUser(null);
            }
        }

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): static
    {
        $this->company = $company;

        return $this;
    }
}
