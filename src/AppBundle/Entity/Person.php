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
 *  @ORM\Index(columns={"fullname", "biography"}, flags={"fulltext"}),
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
     * @ORM\Column(type="array")
     */
    private $urls;

    /**
     * @var Collection|ArtworkContribution[]
     * @ORM\OneToMany(targetEntity="ArtworkContribution", mappedBy="person", cascade={"persist"}, orphanRemoval=true)
     */
    private $artworkContributions;

    /**
     * @var Collection|ProjectContribution[]
     * @ORM\OneToMany(targetEntity="ProjectContribution", mappedBy="person", cascade={"persist"}, orphanRemoval=true)
     */
    private $projectContributions;
    
    /**
     * @var Collection|MediaFile[]
     * @ORM\ManyToMany(targetEntity="MediaFile", inversedBy="people")
     * @ORM\JoinTable(name="person_mediafiles")
     */
    private $mediaFiles;    

    public function __construct() {
        parent::__construct();
        $this->artworkContributions = new ArrayCollection();
        $this->projectContributions = new ArrayCollection();
        $this->urls = array();
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

    /**
     * Get the first URL. There may be more.
     * 
     * @return string|null
     */
    public function getUrl() {
        if(count($this->urls) > 0) {
            return $this->urls[0];
        }
        return null;
    }

    /**
     * Set urls
     *
     * @param array $urls
     *
     * @return Person
     */
    public function setUrls($urls)
    {
        $this->urls = $urls;

        return $this;
    }

    /**
     * Get urls
     *
     * @return array
     */
    public function getUrls()
    {
        return $this->urls;
    }

    /**
     * Check if a media file is associated with this person.
     * 
     * @param \AppBundle\Entity\MediaFile $mediaFile
     * @return boolean
     */
    public function hasMediaFile(MediaFile $mediaFile) {
        return $this->mediaFiles->contains($mediaFile);
    }
    
    /**
     * Add mediaFile
     *
     * @param \AppBundle\Entity\MediaFile $mediaFile
     *
     * @return Person
     */
    public function addMediaFile(\AppBundle\Entity\MediaFile $mediaFile)
    {
        if ( ! $this->mediaFiles->contains($mediaFile)) {
            $this->mediaFiles[] = $mediaFile;
        }

        return $this;
    }

    /**
     * Remove mediaFile
     *
     * @param \AppBundle\Entity\MediaFile $mediaFile
     */
    public function removeMediaFile(\AppBundle\Entity\MediaFile $mediaFile)
    {
        $this->mediaFiles->removeElement($mediaFile);
    }

    /**
     * Get mediaFiles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMediaFiles()
    {
        return $this->mediaFiles;
    }
}
