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
use Nines\UtilBundle\Entity\AbstractTerm;

/**
 * MediaFileCategory.
 *
 * @ORM\Table(name="media_file_category")
 * @ORM\Entity(repositoryClass="App\Repository\MediaFileCategoryRepository")
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
     * Add mediaFile.
     *
     * @return MediaFileCategory
     */
    public function addMediaFile(MediaFile $mediaFile) {
        $this->mediaFiles[] = $mediaFile;

        return $this;
    }

    /**
     * Remove mediaFile.
     */
    public function removeMediaFile(MediaFile $mediaFile) : void {
        $this->mediaFiles->removeElement($mediaFile);
    }

    /**
     * Get mediaFiles.
     *
     * @return Collection
     */
    public function getMediaFiles() {
        return $this->mediaFiles;
    }
}
