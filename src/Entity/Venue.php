<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\VenueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

#[ORM\Entity(repositoryClass: VenueRepository::class)]
#[ORM\Table(name: 'venue')]
#[ORM\Index(columns: ['name', 'address', 'description', 'url'], flags: ['fulltext'])]
class Venue extends AbstractEntity {
    #[ORM\Column(type: Types::STRING)]
    private ?string $name = null;

    #[ORM\Column(type: Types::STRING)]
    private ?string $address = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $url = null;

    #[ORM\ManyToOne(targetEntity: Location::class, inversedBy: 'venues')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Location $location = null;

    #[ORM\ManyToOne(targetEntity: VenueCategory::class, inversedBy: 'venues')]
    #[ORM\JoinColumn(nullable: false)]
    private ?VenueCategory $venueCategory = null;

    /**
     * @var Collection|Project[]
     */
    #[ORM\ManyToMany(targetEntity: Project::class, mappedBy: 'venues')]
    private Collection $projects;

    public function __construct() {
        parent::__construct();
        $this->projects = new ArrayCollection();
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

    public function setUrl($url) : self {
        $this->url = $url;

        return $this;
    }

    public function getUrl() : ?string {
        return $this->url;
    }

    public function setLocation(?Location $location = null) : self {
        $this->location = $location;

        return $this;
    }

    public function getLocation() : ?Location {
        return $this->location;
    }

    public function setVenueCategory(?VenueCategory $venueCategory = null) : self {
        $this->venueCategory = $venueCategory;

        return $this;
    }

    public function getVenueCategory() : ?VenueCategory {
        return $this->venueCategory;
    }

    public function addProject(Project $project) : self {
        $this->projects[] = $project;

        return $this;
    }

    public function removeProject(Project $project) : void {
        $this->projects->removeElement($project);
    }

    public function getProjects() : Collection {
        return $this->projects;
    }
}
