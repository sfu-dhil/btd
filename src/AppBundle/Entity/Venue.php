<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

/**
 * Venue
 *
 * @ORM\Table(name="venue", indexes={
 *  @ORM\Index(columns={"name", "address", "description", "url"}, flags={"fulltext"}),
 * })
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VenueRepository")
 */
class Venue extends AbstractEntity {

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $address;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $url;

    /**
     * @var Location
     * @ORM\ManyToOne(targetEntity="Location", inversedBy="venues")
     * @ORM\JoinColumn(name="location_id")
     */
    private $location;

    /**
     * @var VenueType
     * @ORM\ManyToOne(targetEntity="VenueType", inversedBy="venues")
     * @ORM\JoinColumn(name="venuetype_id")
     */
    private $venueType;

    /**
     * @var Collection|Project[]
     * @ORM\ManyToMany(targetEntity="Project", mappedBy="venues")
     */
    private $projects;

    public function __construct() {
        parent::__construct();
        $this->projects = new ArrayCollection();
    }

    public function __toString() {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Venue
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Venue
     */
    public function setAddress($address) {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Venue
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Venue
     */
    public function setUrl($url) {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * Set location
     *
     * @param Location $location
     *
     * @return Venue
     */
    public function setLocation(Location $location = null) {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return Location
     */
    public function getLocation() {
        return $this->location;
    }

    /**
     * Set venueType
     *
     * @param VenueType $venueType
     *
     * @return Venue
     */
    public function setVenueType(VenueType $venueType = null) {
        $this->venueType = $venueType;

        return $this;
    }

    /**
     * Get venueType
     *
     * @return VenueType
     */
    public function getVenueType() {
        return $this->venueType;
    }

    /**
     * Add project
     *
     * @param Project $project
     *
     * @return Venue
     */
    public function addProject(Project $project) {
        $this->projects[] = $project;

        return $this;
    }

    /**
     * Remove project
     *
     * @param Project $project
     */
    public function removeProject(Project $project) {
        $this->projects->removeElement($project);
    }

    /**
     * Get projects
     *
     * @return Collection
     */
    public function getProjects() {
        return $this->projects;
    }

}
