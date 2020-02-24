<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

/**
 * Organization.
 *
 * @ORM\Table(name="organization", indexes={
 *  @ORM\Index(columns={"name", "address", "description", "contact"}, flags={"fulltext"}),
 * })
 * @ORM\Entity(repositoryClass="App\Repository\OrganizationRepository")
 */
class Organization extends AbstractEntity {
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $address;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @var string
     * @ORM\Column(type="array")
     */
    private $urls;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $contact;

    /**
     * @var Location
     * @ORM\ManyToOne(targetEntity="Location", inversedBy="organizations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $location;

    /**
     * @var ArtworkContribution[]|Collection
     * @ORM\OneToMany(targetEntity="ArtworkContribution", mappedBy="organization", cascade={"persist"}, orphanRemoval=true)
     */
    private $artworkContributions;

    /**
     * @var Collection|ProjectContribution[]
     * @ORM\OneToMany(targetEntity="ProjectContribution", mappedBy="organization", cascade={"persist"}, orphanRemoval=true)
     */
    private $projectContributions;

    public function __construct() {
        parent::__construct();
        $this->artworkContributions = new ArrayCollection();
        $this->projectContributions = new ArrayCollection();
    }

    public function __toString() {
        return $this->name;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Organization
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set address.
     *
     * @param string $address
     *
     * @return Organization
     */
    public function setAddress($address) {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address.
     *
     * @return string
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Organization
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set contact.
     *
     * @param string $contact
     *
     * @return Organization
     */
    public function setContact($contact) {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact.
     *
     * @return string
     */
    public function getContact() {
        return $this->contact;
    }

    /**
     * Set location.
     *
     * @param Location $location
     *
     * @return Organization
     */
    public function setLocation(Location $location = null) {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location.
     *
     * @return Location
     */
    public function getLocation() {
        return $this->location;
    }

    /**
     * Add artworkContribution.
     *
     * @param ArtworkContribution $artworkContribution
     *
     * @return Organization
     */
    public function addArtworkContribution(ArtworkContribution $artworkContribution) {
        $this->artworkContributions[] = $artworkContribution;

        return $this;
    }

    /**
     * Remove artworkContribution.
     *
     * @param ArtworkContribution $artworkContribution
     */
    public function removeArtworkContribution(ArtworkContribution $artworkContribution) {
        $this->artworkContributions->removeElement($artworkContribution);
    }

    /**
     * Get artworkContributions.
     *
     * @return Collection
     */
    public function getArtworkContributions() {
        return $this->artworkContributions;
    }

    /**
     * Add projectContribution.
     *
     * @param ProjectContribution $projectContribution
     *
     * @return Organization
     */
    public function addProjectContribution(ProjectContribution $projectContribution) {
        $this->projectContributions[] = $projectContribution;

        return $this;
    }

    /**
     * Remove projectContribution.
     *
     * @param ProjectContribution $projectContribution
     */
    public function removeProjectContribution(ProjectContribution $projectContribution) {
        $this->projectContributions->removeElement($projectContribution);
    }

    /**
     * Get projectContributions.
     *
     * @return Collection
     */
    public function getProjectContributions() {
        return $this->projectContributions;
    }

    /**
     * Set urls.
     *
     * @param array $urls
     *
     * @return Organization
     */
    public function setUrls($urls) {
        $this->urls = $urls;

        return $this;
    }

    /**
     * Get urls.
     *
     * @return array
     */
    public function getUrls() {
        return $this->urls;
    }
}
