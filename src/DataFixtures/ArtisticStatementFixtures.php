<?php

namespace App\DataFixtures;

use App\Entity\ArtisticStatement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * LoadArtisticStatement form.
 */
class ArtisticStatementFixtures extends Fixture implements DependentFixtureInterface {
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $em) {
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

    /**
     * {@inheritdoc}
     */
    public function getDependencies() {
        // add dependencies here, or remove this
        // function and "implements DependentFixtureInterface" above
        return array(
            ArtworkFixtures::class,
            PersonFixtures::class,
        );
    }
}
