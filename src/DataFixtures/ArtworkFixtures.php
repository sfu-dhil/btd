<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Artwork;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * LoadArtwork form.
 */
class ArtworkFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface {
    public static function getGroups() : array {
        return ['dev', 'test'];
    }

    public function load(ObjectManager $em) : void {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new Artwork();
            $fixture->setTitle('Title ' . $i);
            $fixture->setContent('Description ' . $i);
            $fixture->setMaterials('Materials ' . $i);
            $fixture->setCopyright('Copyright ' . $i);
            $fixture->setArtworkCategory($this->getReference('artworkcategory.1'));

            $em->persist($fixture);
            $this->setReference('artwork.' . $i, $fixture);
        }

        $em->flush();
    }

    public function getDependencies() {
        // add dependencies here, or remove this
        // function and "implements DependentFixtureInterface, FixtureGroupInterface" above
        return [
            ArtworkCategoryFixtures::class,
        ];
    }
}
