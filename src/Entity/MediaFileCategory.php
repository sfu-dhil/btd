<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\MediaFileCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

/**
 * MediaFileCategory.
 */
#[ORM\Entity(repositoryClass: MediaFileCategoryRepository::class)]
#[ORM\Table(name: 'media_file_category')]
class MediaFileCategory extends AbstractTerm {
    /**
     * @var Collection|MediaFile[]
     */
    #[ORM\OneToMany(targetEntity: MediaFile::class, mappedBy: 'mediaFileCategory')]
    private Collection $mediaFiles;

    public function __construct() {
        parent::__construct();
        $this->mediaFiles = new ArrayCollection();
    }

    public function addMediaFile(MediaFile $mediaFile) : self {
        $this->mediaFiles[] = $mediaFile;

        return $this;
    }

    public function removeMediaFile(MediaFile $mediaFile) : void {
        $this->mediaFiles->removeElement($mediaFile);
    }

    public function getMediaFiles() : Collection {
        return $this->mediaFiles;
    }
}
