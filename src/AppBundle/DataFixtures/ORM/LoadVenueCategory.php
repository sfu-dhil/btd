<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\VenueCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadVenueCategory form.
 */
class LoadVenueCategory extends Fixture {
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $em) {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new VenueCategory();
            $fixture->setName('venue-category-' . $i);
            $fixture->setLabel('Venue Category ' . $i);
            $em->persist($fixture);
            $this->setReference('venuecategory.' . $i, $fixture);
        }

        $em->flush();
    }
}
