<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Venue;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadVenue form.
 */
class LoadVenue extends Fixture implements DependentFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em)
    {
        for($i = 0; $i < 4; $i++) {
            $fixture = new Venue();
            $fixture->setName('Name ' . $i);
            $fixture->setAddress('Address ' . $i);
            $fixture->setDescription('Description ' . $i);
            $fixture->setUrl('Url ' . $i);
            $fixture->setLocation($this->getReference('location.1'));
            $fixture->setVenueCategory($this->getReference('venuecategory.1'));

            $em->persist($fixture);
            $this->setReference('venue.' . $i, $fixture);
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
            LoadLocation::class,
            LoadVenueCategory::class,
        ];
    }


}
