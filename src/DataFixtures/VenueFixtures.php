<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Venue;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * LoadVenue form.
 */
class VenueFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface {
    public static function getGroups() : array {
        return ['dev', 'test'];
    }
    public function load(ObjectManager $em) : void {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new Venue();
            $fixture->setName('Name ' . $i);
            $fixture->setAddress('Address ' . $i);
            $fixture->setDescription('Description ' . $i);
            $fixture->setUrl('Url ' . $i);
            $fixture->setLocation($this->getReference('location.1'));
            $fixture->setVenueCategory($this->getReference('venuecategory.1'));

            $em->persist($fixture);
            $this->setReference('venue.' . $i, $fixture);
        }

        $em->flush();
    }

    public function getDependencies() {
        // add dependencies here, or remove this
        // function and "implements DependentFixtureInterface, FixtureGroupInterface" above
        return [
            LocationFixtures::class,
            VenueCategoryFixtures::class,
        ];
    }
}
