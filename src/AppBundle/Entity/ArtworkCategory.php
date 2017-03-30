<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

/**
 * ArtworkCategory
 *
 * @ORM\Table(name="artwork_category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArtworkCategoryRepository")
 */
class ArtworkCategory extends AbstractTerm
{
    /**
     * @var Collection|Artwork[]
     * @ORM\OneToMany(targetEntity="Artwork", mappedBy="artworkCategory")
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
     * @return ArtworkCategory
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
