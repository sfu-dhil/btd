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
use Nines\DublinCoreBundle\Entity\AbstractField;
use Nines\UtilBundle\Entity\AbstractEntity;
use Symfony\Component\HttpFoundation\File\File;

/**
 * MediaFile.
 *
 * @ORM\Table(name="media_file", indexes={
 * })
 * @ORM\Entity(repositoryClass="App\Repository\MediaFileRepository")
 */
class MediaFile extends AbstractEntity {
    /**
     * A Doctrine event listener will turn the filename into a file object.
     *
     * @var File
     */
    private $file;

    /**
     * @var string
     * @ORM\Column(name="file", type="string")
     */
    private $filename;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $hasThumbnail;

    /**
     * @ORM\Column(type="string")
     */
    private $originalName;

    /**
     * @var MediaFileCategory
     * @ORM\ManyToOne(targetEntity="MediaFileCategory", inversedBy="mediaFiles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $mediaFileCategory;

    /**
     * @var Collection|MediaFileField[]
     * @ORM\OneToMany(targetEntity="MediaFileField", mappedBy="mediaFile")
     */
    private $metadataFields;

    /**
     * @var Collection|MediaFileField[]
     * @ORM\ManyToMany(targetEntity="Artwork", mappedBy="mediaFiles")
     */
    private $artworks;

    /**
     * @var Collection|MediaFileField[]
     * @ORM\ManyToMany(targetEntity="Project", mappedBy="mediaFiles")
     */
    private $projects;

    /**
     * @var Collection|MediaFileField[]
     * @ORM\ManyToMany(targetEntity="Person", mappedBy="mediaFiles")
     */
    private $people;

    public function __construct() {
        parent::__construct();
        $this->metadataFields = new ArrayCollection();
        $this->people = new ArrayCollection();
        $this->hasThumbnail = false;
    }

    public function __toString() : string {
        return $this->getId();
    }

    public function setFilename($filename) : void {
        $this->filename = $filename;
    }

    public function getFilename() {
        return $this->filename;
    }

    /**
     * @return File
     */
    public function getFile() {
        return $this->file;
    }

    public function setFile(File $file) {
        $this->file = $file;

        return $this;
    }

    public function getMimeType() {
        return $this->file->getMimeType();
    }

    public function getPath() {
        return $this->file->getPath();
    }

    public function getRealPath() {
        return $this->file->getRealPath();
    }

    public function getBasename() {
        return $this->file->getBasename('.' . $this->file->getExtension());
    }

    public function getSize() {
        return $this->file->getSize();
    }

    public function getThumbnail() {
        $base = $this->getBasename();
        $path = $this->getPath();
        $name = $base . '_tn.jpg';
        $tnPath = $path . '/' . $name;
        if (file_exists($tnPath) && is_readable($tnPath)) {
            return new File($tnPath);
        }
    }

    /**
     * Set originalName.
     *
     * @param string $originalName
     *
     * @return MediaFile
     */
    public function setOriginalName($originalName) {
        $this->originalName = $originalName;

        return $this;
    }

    /**
     * Get originalName.
     *
     * @return string
     */
    public function getOriginalName() {
        return $this->originalName;
    }

    /**
     * Add metadataField.
     *
     * @return MediaFile
     */
    public function addMetadataField(MediaFileField $metadataField) {
        $this->metadataFields[] = $metadataField;

        return $this;
    }

    /**
     * Remove metadataField.
     */
    public function removeMetadataField(MediaFileField $metadataField) : void {
        $this->metadataFields->removeElement($metadataField);
    }

    /**
     * Get metadataFields.
     *
     * @param null|mixed $name
     * @param mixed $list
     *
     * @return Collection
     */
    public function getMetadataFields($name = null, $list = true) {
        if ( ! $name) {
            return $this->metadataFields;
        }
        $matches = $this->metadataFields->filter(fn (AbstractField $field) => $field->getElement()->getName() === $name);
        if ($list) {
            return $matches;
        }

        return $matches->first();
    }

    /**
     * Set mediaFileCategory.
     *
     * @param MediaFileCategory $mediaFileCategory
     *
     * @return MediaFile
     */
    public function setMediaFileCategory(?MediaFileCategory $mediaFileCategory = null) {
        $this->mediaFileCategory = $mediaFileCategory;

        return $this;
    }

    /**
     * Get mediaFileCategory.
     *
     * @return MediaFileCategory
     */
    public function getMediaFileCategory() {
        return $this->mediaFileCategory;
    }

    /**
     * Add artwork.
     *
     * @return MediaFile
     */
    public function addArtwork(Artwork $artwork) {
        $this->artworks[] = $artwork;

        return $this;
    }

    /**
     * Remove artwork.
     */
    public function removeArtwork(Artwork $artwork) : void {
        $this->artworks->removeElement($artwork);
    }

    /**
     * Get artworks.
     *
     * @return Collection
     */
    public function getArtworks() {
        return $this->artworks;
    }

    /**
     * Add project.
     *
     * @return MediaFile
     */
    public function addProject(Project $project) {
        $this->projects[] = $project;

        return $this;
    }

    /**
     * Remove project.
     */
    public function removeProject(Project $project) : void {
        $this->projects->removeElement($project);
    }

    /**
     * Get projects.
     *
     * @return Collection
     */
    public function getProjects() {
        return $this->projects;
    }

    public function hasPerson(Person $person) {
        return $this->people->contains($person);
    }

    /**
     * Add person.
     *
     * @return MediaFile
     */
    public function addPerson(Person $person) {
        if ( ! $this->people->contains($person)) {
            $this->people[] = $person;
        }

        return $this;
    }

    /**
     * Remove person.
     */
    public function removePerson(Person $person) : void {
        $this->people->removeElement($person);
    }

    /**
     * Get people.
     *
     * @return Collection
     */
    public function getPeople() {
        return $this->people;
    }

    /**
     * Set hasThumbnail.
     *
     * @param bool $hasThumbnail
     *
     * @return MediaFile
     */
    public function setHasThumbnail($hasThumbnail) {
        $this->hasThumbnail = $hasThumbnail;

        return $this;
    }

    /**
     * Get hasThumbnail.
     *
     * @return bool
     */
    public function getHasThumbnail() {
        return $this->hasThumbnail;
    }
}
