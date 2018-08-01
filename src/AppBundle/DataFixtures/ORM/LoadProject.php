<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadProject form.
 */
class LoadProject extends Fixture implements DependentFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em)
    {
        for($i = 0; $i < 4; $i++) {
            $fixture = new Project();
            $fixture->setTitle('Title ' . $i);
            $fixture->setStartDate('StartDate ' . $i);
            $fixture->setEndDate('EndDate ' . $i);
            $fixture->setExcerpt('Excerpt ' . $i);
            $fixture->setDescription('Description ' . $i);
            $fixture->setUrl('Url ' . $i);
            $fixture->setProjectcategory($this->getReference('projectCategory.1'));
            $fixture->setVenues($this->getReference('venues.1'));
            $fixture->setMediafiles($this->getReference('mediaFiles.1'));
            $fixture->setArtworks($this->getReference('artworks.1'));

            $em->persist($fixture);
            $this->setReference('project.' . $i);
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
            LoadProjectCategory::class,
            LoadVenue::class,
            LoadMediaFile::class,
            LoadArtwork::class,
        ];
    }


}
