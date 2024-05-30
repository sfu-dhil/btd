<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Person;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

/**
 * LoadPerson form.
 */
class PersonFixtures extends Fixture implements FixtureGroupInterface {
    public static function getGroups() : array {
        return ['dev', 'test'];
    }
    public function load(ObjectManager $em) : void {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new Person();
            $fixture->setFullname('Fullname ' . $i);
            $fixture->setSortableName('SortableName ' . $i);
            $fixture->setBiography('Biography ' . $i);
            $fixture->setUrls([
                'http://example.com/person/' . $i,
            ]);

            $em->persist($fixture);
            $this->setReference('person.' . $i, $fixture);
        }

        $em->flush();
    }
}
