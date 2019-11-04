<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

/**
 * VenueCategory.
 *
 * @ORM\Table(name="venue_category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VenueCategoryRepository")
 */
class VenueCategory extends AbstractTerm {
    /**
     * @var Collection|Venue[]
     * @ORM\OneToMany(targetEntity="Venue", mappedBy="venueCategory")
     */
    private $venues;

    public function __construct() {
        parent::__construct();
        $this->venues = new ArrayCollection();
    }

    /**
     * Add venue.
     *
     * @param Venue $venue
     *
     * @return VenueCategory
     */
    public function addVenue(Venue $venue) {
        $this->venues[] = $venue;

        return $this;
    }

    /**
     * Remove venue.
     *
     * @param Venue $venue
     */
    public function removeVenue(Venue $venue) {
        $this->venues->removeElement($venue);
    }

    /**
     * Get venues.
     *
     * @return Collection
     */
    public function getVenues() {
        return $this->venues;
    }
}
