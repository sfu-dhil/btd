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
     * @var Collection|MediaFileField[]
     * @ORM\OneToMany(targetEntity="MediaFileField", mappedBy="mediaFile")
     */
    private $metadataFields;

    public function __construct() {
        parent::__construct();
        $this->metadataFields = new ArrayCollection();
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

}
