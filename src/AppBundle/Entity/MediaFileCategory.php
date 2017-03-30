<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

/**
 * MediaFileCategory
 *
 * @ORM\Table(name="media_file_category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MediaFileCategoryRepository")
 */
class MediaFileCategory extends AbstractTerm {

    /**
     * @var Collection|MediaFile[]
     * @ORM\OneToMany(targetEntity="MediaFile", mappedBy="mediaFileCategory")
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
     * @return MediaFileCategory
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
