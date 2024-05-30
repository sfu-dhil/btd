<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\VenueCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

/**
 * VenueCategory.
 */
#[ORM\Entity(repositoryClass: VenueCategoryRepository::class)]
#[ORM\Table(name: 'venue_category')]
class VenueCategory extends AbstractTerm {
    /**
     * @var Collection|Venue[]
     */
    #[ORM\OneToMany(targetEntity: Venue::class, mappedBy: 'venueCategory')]
    private Collection $venues;

    public function __construct() {
        parent::__construct();
        $this->venues = new ArrayCollection();
    }

    public function addVenue(Venue $venue) : self {
        $this->venues[] = $venue;

        return $this;
    }

    public function removeVenue(Venue $venue) : void {
        $this->venues->removeElement($venue);
    }

    public function getVenues() : Collection {
        return $this->venues;
    }
}
