<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\MediaFile;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadMediaFile form.
 */
class LoadMediaFile extends Fixture implements DependentFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em)
    {
        for($i = 0; $i < 4; $i++) {
            $fixture = new MediaFile();
            $fixture->setFile('File ' . $i);
            $fixture->setHasThumbnail('HasThumbnail ' . $i);
            $fixture->setOriginalName('OriginalName ' . $i);
            $fixture->setMediafilecategory($this->getReference('mediaFileCategory.1'));
            $fixture->setArtworks($this->getReference('artworks.1'));
            $fixture->setProjects($this->getReference('projects.1'));
            $fixture->setPeople($this->getReference('people.1'));

            $em->persist($fixture);
            $this->setReference('mediafile.' . $i);
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
            LoadMediaFileCategory::class,
            LoadArtwork::class,
            LoadProject::class,
            LoadPerson::class,
        ];
    }


}
