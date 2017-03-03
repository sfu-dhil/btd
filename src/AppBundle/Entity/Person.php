<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

/**
 * Person
 *
 * @ORM\Table(name="person", indexes={
 *  @ORM\Index(columns={"fullname", "biography", "url"}, flags={"fulltext"}),
 * })
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PersonRepository")
 */
class Person extends AbstractEntity {

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $fullname;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $sortableName;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $biography;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $url;

    /**
     * @var Collection|ArtworkContribution[]
     * @ORM\OneToMany(targetEntity="ArtworkContribution", mappedBy="person")
     */
    private $artworkContributions;

    /**
     * @var Collection|ProjectContribution[]
     * @ORM\OneToMany(targetEntity="ProjectContribution", mappedBy="person")
     */
    private $projectContributions;

    public function __construct() {
        parent::__construct();
        $this->artworkContributions = new ArrayCollection();
        $this->projectContributions = new ArrayCollection();
    }

    public function __toString() {
        return $this->fullname;
    }

    /**
     * Set fullname
     *
     * @param string $fullname
     *
     * @return Person
     */
    public function setFullname($fullname) {
        $this->fullname = $fullname;

        return $this;
    }

    /**
     * Get fullname
     *
     * @return string
     */
    public function getFullname() {
        return $this->fullname;
    }

    /**
     * Set sortableName
     *
     * @param string $sortableName
     *
     * @return Person
     */
    public function setSortableName($sortableName) {
        $this->sortableName = $sortableName;

        return $this;
    }

    /**
     * Get sortableName
     *
     * @return string
     */
    public function getSortableName() {
        return $this->sortableName;
    }

    /**
     * Set biography
     *
     * @param string $biography
     *
     * @return Person
     */
    public function setBiography($biography) {
        $this->biography = $biography;

        return $this;
    }

    /**
     * Get biography
     *
     * @return string
     */
    public function getBiography() {
        return $this->biography;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Person
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
     * Add artworkContribution
     *
     * @param ArtworkContribution $artworkContribution
     *
     * @return Person
     */
    public function addArtworkContribution(ArtworkContribution $artworkContribution) {
        $this->artworkContributions[] = $artworkContribution;

        return $this;
    }

    /**
     * Remove artworkContribution
     *
     * @param ArtworkContribution $artworkContribution
     */
    public function removeArtworkContribution(ArtworkContribution $artworkContribution) {
        $this->artworkContributions->removeElement($artworkContribution);
    }

    /**
     * Get artworkContributions
     *
     * @return Collection
     */
    public function getArtworkContributions() {
        return $this->artworkContributions;
    }

    /**
     * Add projectContribution
     *
     * @param ProjectContribution $projectContribution
     *
     * @return Person
     */
    public function addProjectContribution(ProjectContribution $projectContribution) {
        $this->projectContributions[] = $projectContribution;

        return $this;
    }

    /**
     * Remove projectContribution
     *
     * @param ProjectContribution $projectContribution
     */
    public function removeProjectContribution(ProjectContribution $projectContribution) {
        $this->projectContributions->removeElement($projectContribution);
    }

    /**
     * Get projectContributions
     *
     * @return Collection
     */
    public function getProjectContributions() {
        return $this->projectContributions;
    }

}
