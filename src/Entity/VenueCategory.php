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
 * VenueCategory.
 *
 * @ORM\Table(name="venue_category")
 * @ORM\Entity(repositoryClass="App\Repository\VenueCategoryRepository")
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
     * @return VenueCategory
     */
    public function addVenue(Venue $venue) {
        $this->venues[] = $venue;

        return $this;
    }

    /**
     * Remove venue.
     */
    public function removeVenue(Venue $venue) : void {
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
