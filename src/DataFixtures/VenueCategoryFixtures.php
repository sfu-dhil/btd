<?php

namespace App\DataFixtures;

use App\Entity\VenueCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * LoadVenueCategory form.
 */
class VenueCategoryFixtures extends Fixture {
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
