<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\MediaFileCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadMediaFileCategory form.
 */
class LoadMediaFileCategory extends Fixture {
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
