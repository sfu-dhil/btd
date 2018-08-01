<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\ArtisticStatement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadArtisticStatement form.
 */
class LoadArtisticStatement extends Fixture implements DependentFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em)
    {
        for($i = 0; $i < 4; $i++) {
            $fixture = new ArtisticStatement();
            $fixture->setTitle('Title ' . $i);
            $fixture->setExcerpt('Excerpt ' . $i);
            $fixture->setContent('Content ' . $i);
            $fixture->setArtwork($this->getReference('artwork.1'));
            $fixture->setPeople($this->getReference('people.1'));

            $em->persist($fixture);
            $this->setReference('artisticstatement.' . $i);
        }

        $em->flush();

    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies() {
        // add dependencies here, or remove this
        // function and "implements DependentFixtureInterface" above
        return [
            LoadArtwork::class,
            LoadPerson::class,
        ];
    }


}
