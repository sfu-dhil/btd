<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

/**
 * MediaFileType
 *
 * @ORM\Table(name="media_file_type")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MediaFileTypeRepository")
 */
class MediaFileType extends AbstractTerm {

    /**
     * @var Collection|MediaFile[]
     * @ORM\OneToMany(targetEntity="MediaFile", mappedBy="mediaFileType")
     */
    private $mediaFiles;

    public function __construct() {
        parent::__construct();
        $this->mediaFiles = new ArrayCollection();
    }

    /**
     * Add mediaFile
     *
     * @param MediaFile $mediaFile
     *
     * @return MediaFileType
     */
    public function addMediaFile(MediaFile $mediaFile) {
        $this->mediaFiles[] = $mediaFile;

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

}
