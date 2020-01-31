<?php

namespace App\DataFixtures;

use App\Entity\Artwork;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * LoadArtwork form.
 */
class ArtworkFixtures extends Fixture implements DependentFixtureInterface {
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $em) {
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

    /**
     * {@inheritdoc}
     */
    public function getDependencies() {
        // add dependencies here, or remove this
        // function and "implements DependentFixtureInterface" above
        return array(
            ArtworkCategoryFixtures::class,
        );
    }
}
