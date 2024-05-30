<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Organization;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * LoadOrganization form.
 */
class OrganizationFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface {
    public static function getGroups() : array {
        return ['dev', 'test'];
    }
    public function load(ObjectManager $em) : void {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new Organization();
            $fixture->setName('Name ' . $i);
            $fixture->setAddress('Address ' . $i);
            $fixture->setDescription('Description ' . $i);
            $fixture->setUrls([
                'http://example.com/organization/' . $i,
            ]);
            $fixture->setContact('Contact ' . $i);
            $fixture->setLocation($this->getReference('location.1'));

            $em->persist($fixture);
            $this->setReference('organization.' . $i, $fixture);
        }

        $em->flush();
    }

    public function getDependencies() {
        // add dependencies here, or remove this
        // function and "implements DependentFixtureInterface, FixtureGroupInterface" above
        return [
            LocationFixtures::class,
        ];
    }
}
