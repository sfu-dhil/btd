<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\ArtworkContribution;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ArtworkContributionFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface {
    public static function getGroups() : array {
        return ['dev', 'test'];
    }

    public function load(ObjectManager $em) : void {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new ArtworkContribution();
            $fixture->setArtwork($this->getReference('artwork.1'));
            $fixture->setPerson($this->getReference('person.1'));
            $fixture->setOrganization($this->getReference('organization.1'));
            $fixture->setArtworkrole($this->getReference('artworkrole.1'));

            $em->persist($fixture);
            $this->setReference('artworkcontribution.' . $i, $fixture);
        }

        $em->flush();
    }

    public function getDependencies() {
        // add dependencies here, or remove this
        // function and "implements DependentFixtureInterface, FixtureGroupInterface" above
        return [
            ArtworkFixtures::class,
            PersonFixtures::class,
            OrganizationFixtures::class,
            ArtworkRoleFixtures::class,
        ];
    }
}
