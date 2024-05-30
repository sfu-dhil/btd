<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\MediaFileFieldRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MediaFileFieldRepository::class)]
#[ORM\Table(name: 'media_file_field')]
#[ORM\Index(columns: ['value'], flags: ['fulltext'])]
class MediaFileField extends AbstractField {
    #[ORM\ManyToOne(targetEntity: MediaFile::class, inversedBy: 'metadataFields')]
    #[ORM\JoinColumn(nullable: false)]
    private ?MediaFile $mediaFile = null;

    public function setMediaFile(MediaFile $mediaFile) : self {
        $this->mediaFile = $mediaFile;

        return $this;
    }

    public function getMediaFile() : ?MediaFile {
        return $this->mediaFile;
    }
}
