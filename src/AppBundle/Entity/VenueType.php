<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

/**
 * VenueType
 *
 * @ORM\Table(name="venue_type")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VenueTypeRepository")
 */
class VenueType extends AbstractTerm
{
    /**
     * @var Collection|Venue[]
     * @ORM\OneToMany(targetEntity="Venue", mappedBy="venueType")
     */
    private $venues;

    public function __construct() {
        parent::__construct();
        $this->venues= new ArrayCollection();
    }
    
    /**
     * Add venue
     *
     * @param Venue $venue
     *
     * @return VenueType
     */
    public function addVenue(Venue $venue)
    {
        $this->venues[] = $venue;

        return $this;
    }

    /**
     * Remove venue
     *
     * @param Venue $venue
     */
    public function removeVenue(Venue $venue)
    {
        $this->venues->removeElement($venue);
    }

    /**
     * Get venues
     *
     * @return Collection
     */
    public function getVenues()
    {
        return $this->venues;
    }
}
