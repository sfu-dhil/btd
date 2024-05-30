<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

#[ORM\Entity(repositoryClass: LocationRepository::class)]
#[ORM\Table(name: 'location')]
#[ORM\Index(columns: ['city', 'region', 'country'], flags: ['fulltext'])]
class Location extends AbstractEntity {
    #[ORM\Column(type: Types::STRING)]
    private ?string $city = null;

    #[ORM\Column(type: Types::STRING)]
    private ?string $region = null;

    #[ORM\Column(type: Types::STRING)]
    private ?string $country = null;

    /**
     * @var Collection|Venue[]
     */
    #[ORM\OneToMany(targetEntity: Venue::class, mappedBy: 'location')]
    private Collection $venues;

    /**
     * @var Collection|Organization[]
     */
    #[ORM\OneToMany(targetEntity: Organization::class, mappedBy: 'location')]
    private Collection $organizations;

    public function __construct() {
        $this->venues = new ArrayCollection();
        $this->organizations = new ArrayCollection();
        parent::__construct();
    }

    public function __toString() : string {
        return $this->city;
    }

    public function setCity(?string $city) : self {
        $this->city = $city;

        return $this;
    }

    public function getCity() : ?string {
        return $this->city;
    }

    public function setRegion(?string $region) : self {
        $this->region = $region;

        return $this;
    }

    public function getRegion() : ?string {
        return $this->region;
    }

    public function setCountry(?string $country) : self {
        $this->country = $country;

        return $this;
    }

    public function getCountry() : ?string {
        return $this->country;
    }

    public function addVenue(Venue $venue) : self {
        $this->venues[] = $venue;

        return $this;
    }

    public function removeVenue(Venue $venue) : void {
        $this->venues->removeElement($venue);
    }

    public function getVenues() : Collection {
        return $this->venues;
    }

    public function addOrganization(Organization $organization) : self {
        $this->organizations[] = $organization;

        return $this;
    }

    public function removeOrganization(Organization $organization) : void {
        $this->organizations->removeElement($organization);
    }

    public function getOrganizations() : Collection {
        return $this->organizations;
    }
}
