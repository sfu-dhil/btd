<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\OrganizationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

#[ORM\Entity(repositoryClass: OrganizationRepository::class)]
#[ORM\Table(name: 'organization')]
#[ORM\Index(columns: ['name', 'address', 'description', 'contact'], flags: ['fulltext'])]
class Organization extends AbstractEntity {
    #[ORM\Column(type: Types::STRING)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $address = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $urls = [];

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $contact = null;

    #[ORM\ManyToOne(targetEntity: Location::class, inversedBy: 'organizations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Location $location = null;

    /**
     * @var ArtworkContribution[]|Collection
     */
    #[ORM\OneToMany(targetEntity: ArtworkContribution::class, mappedBy: 'organization', cascade: ['persist'], orphanRemoval: true)]
    private Collection $artworkContributions;

    /**
     * @var Collection|ProjectContribution[]
     */
    #[ORM\OneToMany(targetEntity: ProjectContribution::class, mappedBy: 'organization', cascade: ['persist'], orphanRemoval: true)]
    private Collection $projectContributions;

    public function __construct() {
        parent::__construct();
        $this->artworkContributions = new ArrayCollection();
        $this->projectContributions = new ArrayCollection();
    }

    public function __toString() : string {
        return $this->name;
    }

    public function setName(?string $name) : self {
        $this->name = $name;

        return $this;
    }

    public function getName() : ?string {
        return $this->name;
    }

    public function setAddress(?string $address) : self {
        $this->address = $address;

        return $this;
    }

    public function getAddress() : ?string {
        return $this->address;
    }

    public function setDescription(?string $description) : self {
        $this->description = $description;

        return $this;
    }

    public function getDescription() : ?string {
        return $this->description;
    }

    public function setContact(?string $contact) : self {
        $this->contact = $contact;

        return $this;
    }

    public function getContact() : ?string {
        return $this->contact;
    }

    public function setLocation(?Location $location = null) : self {
        $this->location = $location;

        return $this;
    }

    public function getLocation() : ?Location {
        return $this->location;
    }

    public function addArtworkContribution(ArtworkContribution $artworkContribution) : self {
        $this->artworkContributions[] = $artworkContribution;

        return $this;
    }

    public function removeArtworkContribution(ArtworkContribution $artworkContribution) : void {
        $this->artworkContributions->removeElement($artworkContribution);
    }

    public function getArtworkContributions() : Collection {
        return $this->artworkContributions;
    }

    public function addProjectContribution(ProjectContribution $projectContribution) : self {
        $this->projectContributions[] = $projectContribution;

        return $this;
    }

    public function removeProjectContribution(ProjectContribution $projectContribution) : void {
        $this->projectContributions->removeElement($projectContribution);
    }

    public function getProjectContributions() : Collection {
        return $this->projectContributions;
    }

    public function setUrls(array $urls) : self {
        $this->urls = $urls;

        return $this;
    }

    public function getUrls() : array {
        return $this->urls;
    }
}
