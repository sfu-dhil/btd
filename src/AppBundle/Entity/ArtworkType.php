<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

/**
 * ArtworkType
 *
 * @ORM\Table(name="artwork_type")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArtworkTypeRepository")
 */
class ArtworkType extends AbstractTerm
{
    /**
     * @var Collection|Artwork[]
     * @ORM\OneToMany(targetEntity="Artwork", mappedBy="artworkType")
     */
    private $artworks;
    
    public function __construct() {
        parent::__construct();
        $this->artworks = new ArrayCollection();
    }

    /**
     * Add artwork
     *
     * @param Artwork $artwork
     *
     * @return ArtworkType
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
}
