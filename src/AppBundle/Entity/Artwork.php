<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

/**
 * Artwork
 *
 * @ORM\Table(name="artwork", indexes={
 *  @ORM\Index(columns={"title", "description", "materials", "copyright"}, flags={"fulltext"}),
 * })
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArtworkRepository")
 */
class Artwork extends AbstractEntity {

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $materials;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $copyright;

    /**
     * @var ArtworkType
     * @ORM\ManyToOne(targetEntity="ArtworkType", inversedBy="artworks")
     */
    private $artworkType;
    
    /**
     * @var Collection|ArtworkContribution[]
     * @ORM\OneToMany(targetEntity="ArtworkContribution", mappedBy="artwork")
     */
    private $contributions;

    /**
     * @var Collection|MediaFile[]
     * @ORM\ManyToMany(targetEntity="MediaFile", inversedBy="artworks")
     * @ORM\JoinTable(name="artwork_mediafiles")
     */
    private $mediaFiles;

    /**
     * @var Collection|Project[]
     * @ORM\ManyToMany(targetEntity="Project", mappedBy="artworks")
     */
    private $projects;
    
    public function __construct() {
        parent::__construct();
        $this->contributions = new ArrayCollection();
        $this->mediaFiles = new ArrayCollection();
        $this->projects = new ArrayCollection();
    }

    public function __toString() {
        return $this->title;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Artwork
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Artwork
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
     * Set materials
     *
     * @param string $materials
     *
     * @return Artwork
     */
    public function setMaterials($materials) {
        $this->materials = $materials;

        return $this;
    }

    /**
     * Get materials
     *
     * @return string
     */
    public function getMaterials() {
        return $this->materials;
    }

    /**
     * Set copyright
     *
     * @param string $copyright
     *
     * @return Artwork
     */
    public function setCopyright($copyright) {
        $this->copyright = $copyright;

        return $this;
    }

    /**
     * Get copyright
     *
     * @return string
     */
    public function getCopyright() {
        return $this->copyright;
    }

    /**
     * Add contribution
     *
     * @param ArtworkContribution $contribution
     *
     * @return Artwork
     */
    public function addContribution(ArtworkContribution $contribution) {
        $this->contributions[] = $contribution;

        return $this;
    }

    /**
     * Remove contribution
     *
     * @param ArtworkContribution $contribution
     */
    public function removeContribution(ArtworkContribution $contribution) {
        $this->contributions->removeElement($contribution);
    }

    /**
     * Get contributions
     *
     * @return Collection
     */
    public function getContributions() {
        return $this->contributions;
    }

    public function hasMediaFile(MediaFile $mediaFile) {
        return $this->mediaFiles->contains($mediaFile);
    }

    /**
     * Add mediaFile
     *
     * @param MediaFile $mediaFile
     *
     * @return Artwork
     */
    public function addMediaFile(MediaFile $mediaFile) {
        if (!$this->mediaFiles->contains($mediaFile)) {
            $this->mediaFiles[] = $mediaFile;
        }

        return $this;
    }

    /**
     * Remove mediaFile
     *
     * @param MediaFile $mediaFile
     */
    public function removeMediaFile(MediaFile $mediaFile) {
        $this->mediaFiles->removeElement($mediaFile);
    }

    /**
     * Get mediaFiles
     *
     * @return Collection
     */
    public function getMediaFiles() {
        return $this->mediaFiles;
    }


    /**
     * Add project
     *
     * @param \AppBundle\Entity\Project $project
     *
     * @return Artwork
     */
    public function addProject(\AppBundle\Entity\Project $project)
    {
        $this->projects[] = $project;

        return $this;
    }

    /**
     * Remove project
     *
     * @param \AppBundle\Entity\Project $project
     */
    public function removeProject(\AppBundle\Entity\Project $project)
    {
        $this->projects->removeElement($project);
    }

    /**
     * Get projects
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProjects()
    {
        return $this->projects;
    }

    /**
     * Set artworkType
     *
     * @param \AppBundle\Entity\ArtworkType $artworkType
     *
     * @return Artwork
     */
    public function setArtworkType(\AppBundle\Entity\ArtworkType $artworkType = null)
    {
        $this->artworkType = $artworkType;

        return $this;
    }

    /**
     * Get artworkType
     *
     * @return \AppBundle\Entity\ArtworkType
     */
    public function getArtworkType()
    {
        return $this->artworkType;
    }
}
