<?php

namespace App\DataFixtures;

use App\Entity\MediaFileField;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Nines\DublinCoreBundle\DataFixtures\LoadElement;

/**
 * LoadMediaFileField form.
 */
class MediaFileFieldFixtures extends Fixture implements DependentFixtureInterface {
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $em) {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new MediaFileField();
            $fixture->setValue('Media file field value ' . $i);
            $fixture->setElement($this->getReference('dc_title'));
            $fixture->setMediafile($this->getReference('mediafile.1'));

            $em->persist($fixture);
            $this->setReference('mediafilefield.' . $i, $fixture);
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
            MediaFileFixtures::class,
            ElementFixtures::class,
        );
    }
}
