<?php

namespace App\DataFixtures;

use App\Entity\ArtworkCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * LoadArtworkCategory form.
 */
class ArtworkCategoryFixtures extends Fixture {
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $em) {
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
