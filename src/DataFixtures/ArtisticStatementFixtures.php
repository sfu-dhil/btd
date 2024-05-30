<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\ArtisticStatement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * LoadArtisticStatement form.
 */
class ArtisticStatementFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface {
    public static function getGroups() : array {
        return ['dev', 'test'];
    }

    public function load(ObjectManager $em) : void {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new ArtisticStatement();
            $fixture->setTitle('Title ' . $i);
            $fixture->setExcerpt('Excerpt ' . $i);
            $fixture->setContent('Content ' . $i);
            $fixture->setArtwork($this->getReference('artwork.1'));
            $fixture->addPerson($this->getReference('person.1'));

            $em->persist($fixture);
            $this->setReference('artisticstatement.' . $i, $fixture);
        }

        $em->flush();
    }

    public function getDependencies() {
        // add dependencies here, or remove this
        // function and "implements DependentFixtureInterface, FixtureGroupInterface" above
        return [
            ArtworkFixtures::class,
            PersonFixtures::class,
        ];
    }
}
