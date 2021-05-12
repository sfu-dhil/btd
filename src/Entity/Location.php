<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

/**
 * Location.
 *
 * @ORM\Table(name="location", indexes={
 *     @ORM\Index(columns={"city", "region", "country"}, flags={"fulltext"}),
 * })
 * @ORM\Entity(repositoryClass="App\Repository\LocationRepository")
 */
class Location extends AbstractEntity {
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $city;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $region;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $country;

    /**
     * @var Collection|Venue[]
     * @ORM\OneToMany(targetEntity="Venue", mappedBy="location")
     */
    private $venues;

    /**
     * @var Collection|Organization[]
     * @ORM\OneToMany(targetEntity="Organization", mappedBy="location")
     */
    private $organizations;

    public function __construct() {
        parent::__construct();
        $this->artworks = new ArrayCollection();
        $this->projects = new ArrayCollection();
    }

    public function __toString() : string {
        return $this->city;
    }

    /**
     * Set city.
     *
     * @param string $city
     *
     * @return Location
     */
    public function setCity($city) {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city.
     *
     * @return string
     */
    public function getCity() {
        return $this->city;
    }

    /**
     * Set region.
     *
     * @param string $region
     *
     * @return Location
     */
    public function setRegion($region) {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region.
     *
     * @return string
     */
    public function getRegion() {
        return $this->region;
    }

    /**
     * Set country.
     *
     * @param string $country
     *
     * @return Location
     */
    public function setCountry($country) {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country.
     *
     * @return string
     */
    public function getCountry() {
        return $this->country;
    }

    /**
     * Add venue.
     *
     * @return Location
     */
    public function addVenue(Venue $venue) {
        $this->venues[] = $venue;

        return $this;
    }

    /**
     * Remove venue.
     */
    public function removeVenue(Venue $venue) : void {
        $this->venues->removeElement($venue);
    }

    /**
     * Get venues.
     *
     * @return Collection
     */
    public function getVenues() {
        return $this->venues;
    }

    /**
     * Add organization.
     *
     * @return Location
     */
    public function addOrganization(Organization $organization) {
        $this->organizations[] = $organization;

        return $this;
    }

    /**
     * Remove organization.
     */
    public function removeOrganization(Organization $organization) : void {
        $this->organizations->removeElement($organization);
    }

    /**
     * Get organizations.
     *
     * @return Collection
     */
    public function getOrganizations() {
        return $this->organizations;
    }
}
