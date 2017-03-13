<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Nines\DublinCoreBundle\Entity\AbstractField;

/**
 * MediaFileField
 *
 * @ORM\Table(name="media_file_field", indexes={
 *   @ORM\Index(columns={"value"}, flags={"fulltext"})
 * })
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MediaFileFieldRepository")
 */
class MediaFileField extends AbstractField
{
    /**
     * @var MediaFile
     * @ORM\ManyToOne(targetEntity="MediaFile", inversedBy="metadataFields")
     * @ORM\JoinColumn(nullable=false)
     */
    private $mediaFile;
    
    /**
     * Set mediaFile
     *
     * @param \AppBundle\Entity\MediaFile $mediaFile
     *
     * @return MediaFileField
     */
    public function setMediaFile(\AppBundle\Entity\MediaFile $mediaFile)
    {
        $this->mediaFile = $mediaFile;

        return $this;
    }

    /**
     * Get mediaFile
     *
     * @return \AppBundle\Entity\MediaFile
     */
    public function getMediaFile()
    {
        return $this->mediaFile;
    }
}
