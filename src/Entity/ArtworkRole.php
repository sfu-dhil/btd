<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ArtworkRoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

/**
 * ArtworkRole.
 */
#[ORM\Entity(repositoryClass: ArtworkRoleRepository::class)]
#[ORM\Table(name: 'artwork_role')]
class ArtworkRole extends AbstractTerm {
    /**
     * @var ArtworkContribution[]|Collection
     */
    #[ORM\OneToMany(targetEntity: ArtworkContribution::class, mappedBy: 'artworkRole')]
    private Collection $contributions;

    public function __construct() {
        parent::__construct();
        $this->contributions = new ArrayCollection();
    }

    public function addContribution(ArtworkContribution $contribution) : self {
        $this->contributions[] = $contribution;

        return $this;
    }

    public function removeContribution(ArtworkContribution $contribution) : void {
        $this->contributions->removeElement($contribution);
    }

    public function getContributions() : Collection {
        return $this->contributions;
    }
}
