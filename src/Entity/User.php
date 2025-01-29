<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use dsarhoya\BaseBundle\Entity\BaseUser;
use dsarhoya\BaseBundle\Entity\UserKey;
use dsarhoya\BaseBundle\Model\EntityMappers\BaseUserInterface;

/**
* User.
*
* @ORM\Table()
* @ORM\Entity(repositoryClass="App\Repository\UserRepository")
*/
class User extends BaseUser implements BaseUserInterface
{
    /**
    * @ORM\OneToMany(targetEntity="dsarhoya\BaseBundle\Entity\UserKey", mappedBy="user")
    */
    private $keys;

    /**
    * @ORM\ManyToOne(targetEntity="Company", inversedBy="users")
    * @ORM\JoinColumn(name="company_id", referencedColumnName="id", onDelete="CASCADE")
    */
    private $company;

    /**
    * @ORM\ManyToOne(targetEntity="Profile", inversedBy="users")
    * @ORM\JoinColumn(name="profile_id", referencedColumnName="id", onDelete="SET NULL")
    */
    protected $profile;

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
            // set the owning side to null (unless already changed)
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

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(?Profile $profile): static
    {
        $this->profile = $profile;

        return $this;
    }
}