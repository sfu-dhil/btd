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
            $fixture->setStartDate(new \DateTime('2010-01-01'));
            $fixture->setEndDate(new \DateTime('2010-01-02'));
            $fixture->setExcerpt('Excerpt ' . $i);
            $fixture->setContent('Description ' . $i);
            $fixture->setUrl('http://example.com/project/' . $i);
            $fixture->setProjectCategory($this->getReference('projectcategory.1'));
            $fixture->addVenue($this->getReference('venue.1'));

            $em->persist($fixture);
            $this->setReference('project.' . $i, $fixture);
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
        ];
    }


}
