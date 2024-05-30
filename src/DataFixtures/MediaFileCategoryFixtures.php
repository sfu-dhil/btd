<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\MediaFileCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

/**
 * LoadMediaFileCategory form.
 */
class MediaFileCategoryFixtures extends Fixture implements FixtureGroupInterface {
    public static function getGroups() : array {
        return ['dev', 'test'];
    }

    public function load(ObjectManager $em) : void {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new MediaFileCategory();
            $fixture->setName('mediafile-category-' . $i);
            $fixture->setLabel('MediaFile Category ' . $i);
            $em->persist($fixture);
            $this->setReference('mediafilecategory.' . $i, $fixture);
        }

        $em->flush();
    }
}
