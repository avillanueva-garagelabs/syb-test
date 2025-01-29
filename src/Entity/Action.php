<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use dsarhoya\BaseBundle\Entity\BaseAction;

/**
* Action
* @ORM\Table()
* @ORM\Entity(repositoryClass="App\Repository\ActionRepository")
*/
class Action extends BaseAction
{
    /**
    * @ORM\ManyToMany(targetEntity="Profile", mappedBy="actions")
    */
    private $profiles;

    public function __construct()
    {
        parent::__construct();
        $this->profiles = new ArrayCollection();
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
            $profile->addAction($this);
        }

        return $this;
    }

    public function removeProfile(Profile $profile): static
    {
        if ($this->profiles->removeElement($profile)) {
            $profile->removeAction($this);
        }

        return $this;
    }
}