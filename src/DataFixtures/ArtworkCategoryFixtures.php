<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\ArtworkCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

/**
 * LoadArtworkCategory form.
 */
class ArtworkCategoryFixtures extends Fixture implements FixtureGroupInterface {
    public static function getGroups() : array {
        return ['dev', 'test'];
    }

    public function load(ObjectManager $em) : void {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new ArtworkCategory();
            $fixture->setName('artwork-category-' . $i);
            $fixture->setLabel('Artwork Category ' . $i);
            $em->persist($fixture);
            $this->setReference('artworkcategory.' . $i, $fixture);
        }

        $em->flush();
    }
}
