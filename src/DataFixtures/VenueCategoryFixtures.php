<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\VenueCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

/**
 * LoadVenueCategory form.
 */
class VenueCategoryFixtures extends Fixture implements FixtureGroupInterface {
    public static function getGroups() : array {
        return ['dev', 'test'];
    }
    public function load(ObjectManager $em) : void {
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
