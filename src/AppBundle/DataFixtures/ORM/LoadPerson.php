<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Person;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadPerson form.
 */
class LoadPerson extends Fixture implements DependentFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em)
    {
        for($i = 0; $i < 4; $i++) {
            $fixture = new Person();
            $fixture->setFullname('Fullname ' . $i);
            $fixture->setSortableName('SortableName ' . $i);
            $fixture->setBiography('Biography ' . $i);
            $fixture->setUrls('Urls ' . $i);
            $fixture->setArtisticstatements($this->getReference('artisticStatements.1'));
            $fixture->setMediafiles($this->getReference('mediaFiles.1'));

            $em->persist($fixture);
            $this->setReference('person.' . $i);
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
            LoadArtisticStatement::class,
            LoadMediaFile::class,
        ];
    }


}
