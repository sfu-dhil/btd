<?php

namespace App\DataFixtures;

use App\Entity\MediaFileCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * LoadMediaFileCategory form.
 */
class MediaFileCategoryFixtures extends Fixture {
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $em) {
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
