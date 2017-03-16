<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

/**
 * ArtworkContribution
 *
 * @ORM\Table(name="artwork_contribution")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArtworkContributionRepository")
 */
class ArtworkContribution extends AbstractEntity {

    /**
     * @var Artwork
     * @ORM\ManyToOne(targetEntity="Artwork", inversedBy="contributions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $artwork;

    /**
     * @var Person
     * @ORM\ManyToOne(targetEntity="Person", inversedBy="artworkContributions")
     */
    private $person;

    /**
     * @var Person
     * @ORM\ManyToOne(targetEntity="Organization", inversedBy="artworkContributions")
     */
    private $organization;

    /**
     * @var ArtworkRole
     * @ORM\ManyToOne(targetEntity="ArtworkRole", inversedBy="contributions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $artworkRole;

    public function __toString() {
        
    }

    /**
     * Set artwork
     *
     * @param Artwork $artwork
     *
     * @return ArtworkContribution
     */
    public function setArtwork(Artwork $artwork = null) {
        $this->artwork = $artwork;

        return $this;
    }

    /**
     * Get artwork
     *
     * @return Artwork
     */
    public function getArtwork() {
        return $this->artwork;
    }

    /**
     * Set person
     *
     * @param Person $person
     *
     * @return ArtworkContribution
     */
    public function setPerson(Person $person = null) {
        $this->person = $person;

        return $this;
    }

    /**
     * Get person
     *
     * @return Person
     */
    public function getPerson() {
        return $this->person;
    }

    /**
     * Set organization
     *
     * @param Organization $organization
     *
     * @return ArtworkContribution
     */
    public function setOrganization(Organization $organization = null) {
        $this->organization = $organization;

        return $this;
    }

    /**
     * Get organization
     *
     * @return Organization
     */
    public function getOrganization() {
        return $this->organization;
    }

    /**
     * Set artworkRole
     *
     * @param ArtworkRole $artworkRole
     *
     * @return ArtworkContribution
     */
    public function setArtworkRole(ArtworkRole $artworkRole = null) {
        $this->artworkRole = $artworkRole;

        return $this;
    }

    /**
     * Get artworkRole
     *
     * @return ArtworkRole
     */
    public function getArtworkRole() {
        return $this->artworkRole;
    }

}
