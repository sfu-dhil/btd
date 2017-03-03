<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

/**
 * ArtworkRole
 *
 * @ORM\Table(name="artwork_role")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArtworkRoleRepository")
 */
class ArtworkRole extends AbstractTerm {

    /**
     * @var Collection|ArtworkContribution[]
     * @ORM\OneToMany(targetEntity="ArtworkContribution", mappedBy="artworkRole")
     */
    private $contributions;

    public function __construct() {
        parent::__construct();
        $this->contributions = new ArrayCollection();
    }

    /**
     * Add contribution
     *
     * @param ArtworkContribution $contribution
     *
     * @return ArtworkRole
     */
    public function addContribution(ArtworkContribution $contribution) {
        $this->contributions[] = $contribution;

        return $this;
    }

    /**
     * Remove contribution
     *
     * @param ArtworkContribution $contribution
     */
    public function removeContribution(ArtworkContribution $contribution) {
        $this->contributions->removeElement($contribution);
    }

    /**
     * Get contributions
     *
     * @return Collection
     */
    public function getContributions() {
        return $this->contributions;
    }

}
