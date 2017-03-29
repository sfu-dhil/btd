<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\DublinCoreBundle\Entity\AbstractField;
use Nines\UtilBundle\Entity\AbstractEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * MediaFile
 *
 * @ORM\Table(name="media_file", indexes={
 * })
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MediaFileRepository")
 */
class MediaFile extends AbstractEntity {

    /**
     * In the database, this is the path to the file. Outside the database,
     * a Doctrine event listener will turn the path into a file object.
     * 
     * @ORM\Column(type="string")
     * @Assert\File()
     * @var File
     */
    private $file;

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
    }

    public function __toString() {
        return $this->getId();
    }

    public function getFile() {
        return $this->file;
    }
    
    public function setFile($file) {
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

    public function getFilename() {
        return $this->file->getFilename();
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
        if(file_exists($tnPath) && is_readable($tnPath)) {
            return new File($tnPath);
        }
        return null;
    }

    /**
     * Set originalName
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
     * Get originalName
     *
     * @return string
     */
    public function getOriginalName() {
        return $this->originalName;
    }

    /**
     * Add metadataField
     *
     * @param MediaFileField $metadataField
     *
     * @return MediaFile
     */
    public function addMetadataField(MediaFileField $metadataField) {
        $this->metadataFields[] = $metadataField;

        return $this;
    }

    /**
     * Remove metadataField
     *
     * @param MediaFileField $metadataField
     */
    public function removeMetadataField(MediaFileField $metadataField) {
        $this->metadataFields->removeElement($metadataField);
    }

    /**
     * Get metadataFields
     *
     * @return Collection
     */
    public function getMetadataFields($name = null, $list = true) {
        if (!$name) {
            return $this->metadataFields;
        }
        $matches = $this->metadataFields->filter(function(AbstractField $field) use ($name) {
            return $field->getElement()->getName() === $name;
        });
        if($list) {
            return $matches;
        }
        return $matches->first();
    }


    /**
     * Set mediaFileCategory
     *
     * @param MediaFileCategory $mediaFileCategory
     *
     * @return MediaFile
     */
    public function setMediaFileCategory(MediaFileCategory $mediaFileCategory = null)
    {
        $this->mediaFileCategory = $mediaFileCategory;

        return $this;
    }

    /**
     * Get mediaFileCategory
     *
     * @return MediaFileCategory
     */
    public function getMediaFileCategory()
    {
        return $this->mediaFileCategory;
    }

    /**
     * Add artwork
     *
     * @param Artwork $artwork
     *
     * @return MediaFile
     */
    public function addArtwork(Artwork $artwork)
    {
        $this->artworks[] = $artwork;

        return $this;
    }

    /**
     * Remove artwork
     *
     * @param Artwork $artwork
     */
    public function removeArtwork(Artwork $artwork)
    {
        $this->artworks->removeElement($artwork);
    }

    /**
     * Get artworks
     *
     * @return Collection
     */
    public function getArtworks()
    {
        return $this->artworks;
    }

    /**
     * Add project
     *
     * @param Project $project
     *
     * @return MediaFile
     */
    public function addProject(Project $project)
    {
        $this->projects[] = $project;

        return $this;
    }

    /**
     * Remove project
     *
     * @param Project $project
     */
    public function removeProject(Project $project)
    {
        $this->projects->removeElement($project);
    }

    /**
     * Get projects
     *
     * @return Collection
     */
    public function getProjects()
    {
        return $this->projects;
    }

    public function hasPerson(Person $person) {
        return $this->people->contains($person);
    }
    
    /**
     * Add person
     *
     * @param \AppBundle\Entity\Person $person
     *
     * @return MediaFile
     */
    public function addPerson(\AppBundle\Entity\Person $person)
    {
        if( ! $this->people->contains($person)) {
            $this->people[] = $person;
        }

        return $this;
    }

    /**
     * Remove person
     *
     * @param \AppBundle\Entity\Person $person
     */
    public function removePerson(\AppBundle\Entity\Person $person)
    {
        $this->people->removeElement($person);
    }

    /**
     * Get people
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPeople()
    {
        return $this->people;
    }
}
