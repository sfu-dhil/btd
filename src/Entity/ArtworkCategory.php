<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ArtworkCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

/**
 * ArtworkCategory.
 */
#[ORM\Entity(repositoryClass: ArtworkCategoryRepository::class)]
#[ORM\Table(name: 'artwork_category')]
class ArtworkCategory extends AbstractTerm {
    /**
     * @var Artwork[]|Collection
     */
    #[ORM\OneToMany(targetEntity: Artwork::class, mappedBy: 'artworkCategory')]
    private Collection $artworks;

    public function __construct() {
        parent::__construct();
        $this->artworks = new ArrayCollection();
    }

    public function addArtwork(Artwork $artwork) : self {
        $this->artworks[] = $artwork;

        return $this;
    }

    public function removeArtwork(Artwork $artwork) : void {
        $this->artworks->removeElement($artwork);
    }

    public function getArtworks() : Collection {
        return $this->artworks;
    }
}
