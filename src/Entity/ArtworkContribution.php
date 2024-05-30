<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ArtworkContributionRepository;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

/**
 * ArtworkContribution.
 */
#[ORM\Entity(repositoryClass: ArtworkContributionRepository::class)]
#[ORM\Table(name: 'artwork_contribution')]
class ArtworkContribution extends AbstractEntity {
    #[ORM\ManyToOne(targetEntity: Artwork::class, inversedBy: 'contributions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Artwork $artwork = null;

    #[ORM\ManyToOne(targetEntity: Person::class, inversedBy: 'artworkContributions')]
    private ?Person $person = null;

    #[ORM\ManyToOne(targetEntity: Organization::class, inversedBy: 'artworkContributions')]
    private ?Organization $organization = null;

    #[ORM\ManyToOne(targetEntity: ArtworkRole::class, inversedBy: 'contributions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ArtworkRole $artworkRole = null;

    public function __toString() : string {
        return implode(':', [$this->artwork, $this->person, $this->organization, $this->artworkRole]);
    }

    public function setArtwork(?Artwork $artwork = null) : self {
        $this->artwork = $artwork;

        return $this;
    }

    public function getArtwork() : ?Artwork {
        return $this->artwork;
    }

    public function setPerson(?Person $person = null) : self {
        $this->person = $person;

        return $this;
    }

    public function getPerson() : ?Person {
        return $this->person;
    }

    public function setOrganization(?Organization $organization = null) : self {
        $this->organization = $organization;

        return $this;
    }

    public function getOrganization() : ?Organization {
        return $this->organization;
    }

    public function setArtworkRole(?ArtworkRole $artworkRole = null) : self {
        $this->artworkRole = $artworkRole;

        return $this;
    }

    public function getArtworkRole() : ?ArtworkRole {
        return $this->artworkRole;
    }
}
