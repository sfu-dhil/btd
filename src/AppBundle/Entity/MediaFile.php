<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

/**
 * MediaFile
 *
 * @ORM\Table(name="media_file", indexes={
 *  @ORM\Index(columns={"title", "description", "copyright"}, flags={"fulltext"}),
 * })
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MediaFileRepository")
 */
class MediaFile extends AbstractEntity {

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $path;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $size;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $mimetype;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $creator;

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
    private $copyright;

    /**
     * @var MediaFileType
     * @ORM\ManyToOne(targetEntity="MediaFileType", inversedBy="mediaFiles")
     * @ORM\JoinColumn(name="mediafiletype_id")
     */
    private $mediaFileType;

    /**
     * @var Collection|Artwork[]
     * @ORM\ManyToMany(targetEntity="Artwork", mappedBy="mediaFiles")
     */
    private $artworks;

    /**
     * @var Collection|Project[]
     * @ORM\ManyToMany(targetEntity="Project", mappedBy="mediaFiles")
     */
    private $projects;

    public function __construct() {
        parent::__construct();
        $this->artworks = new ArrayCollection();
        $this->projects = new ArrayCollection();
    }

    public function __toString() {
        return $this->title;
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return MediaFile
     */
    public function setPath($path) {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * Set size
     *
     * @param integer $size
     *
     * @return MediaFile
     */
    public function setSize($size) {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return integer
     */
    public function getSize() {
        return $this->size;
    }

    /**
     * Set mimetype
     *
     * @param string $mimetype
     *
     * @return MediaFile
     */
    public function setMimetype($mimetype) {
        $this->mimetype = $mimetype;

        return $this;
    }

    /**
     * Get mimetype
     *
     * @return string
     */
    public function getMimetype() {
        return $this->mimetype;
    }

    /**
     * Set creator
     *
     * @param string $creator
     *
     * @return MediaFile
     */
    public function setCreator($creator) {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get creator
     *
     * @return string
     */
    public function getCreator() {
        return $this->creator;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return MediaFile
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
     * @return MediaFile
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
     * Set copyright
     *
     * @param string $copyright
     *
     * @return MediaFile
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
     * Set mediaFileType
     *
     * @param MediaFileType $mediaFileType
     *
     * @return MediaFile
     */
    public function setMediaFileType(MediaFileType $mediaFileType = null) {
        $this->mediaFileType = $mediaFileType;

        return $this;
    }

    /**
     * Get mediaFileType
     *
     * @return MediaFileType
     */
    public function getMediaFileType() {
        return $this->mediaFileType;
    }

    /**
     * Add artwork
     *
     * @param Artwork $artwork
     *
     * @return MediaFile
     */
    public function addArtwork(Artwork $artwork) {
        $this->artworks[] = $artwork;

        return $this;
    }

    /**
     * Remove artwork
     *
     * @param Artwork $artwork
     */
    public function removeArtwork(Artwork $artwork) {
        $this->artworks->removeElement($artwork);
    }

    /**
     * Get artworks
     *
     * @return Collection
     */
    public function getArtworks() {
        return $this->artworks;
    }

    /**
     * Add project
     *
     * @param Project $project
     *
     * @return MediaFile
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
