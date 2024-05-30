<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Location;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

/**
 * LoadLocation form.
 */
class LocationFixtures extends Fixture implements FixtureGroupInterface {
    public static function getGroups() : array {
        return ['dev', 'test'];
    }

    public function load(ObjectManager $em) : void {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new Location();
            $fixture->setCity('City ' . $i);
            $fixture->setRegion('Region ' . $i);
            $fixture->setCountry('Country ' . $i);

            $em->persist($fixture);
            $this->setReference('location.' . $i, $fixture);
        }

        $em->flush();
    }
}
