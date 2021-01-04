<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

/**
 * ArtworkCategory.
 *
 * @ORM\Table(name="artwork_category")
 * @ORM\Entity(repositoryClass="App\Repository\ArtworkCategoryRepository")
 */
class ArtworkCategory extends AbstractTerm {
    /**
     * @var Artwork[]|Collection
     * @ORM\OneToMany(targetEntity="Artwork", mappedBy="artworkCategory")
     */
    private $artworks;

    public function __construct() {
        parent::__construct();
        $this->artworks = new ArrayCollection();
    }

    /**
     * Add artwork.
     *
     * @return ArtworkCategory
     */
    public function addArtwork(Artwork $artwork) {
        $this->artworks[] = $artwork;

        return $this;
    }

    /**
     * Remove artwork.
     */
    public function removeArtwork(Artwork $artwork) : void {
        $this->artworks->removeElement($artwork);
    }

    /**
     * Get artworks.
     *
     * @return Collection
     */
    public function getArtworks() {
        return $this->artworks;
    }
}
