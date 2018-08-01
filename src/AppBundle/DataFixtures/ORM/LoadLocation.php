<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Location;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadLocation form.
 */
class LoadLocation extends Fixture
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em)
    {
        for($i = 0; $i < 4; $i++) {
            $fixture = new Location();
            $fixture->setCity('City ' . $i);
            $fixture->setRegion('Region ' . $i);
            $fixture->setCountry('Country ' . $i);

            $em->persist($fixture);
            $this->setReference('location.' . $i);
        }

        $em->flush();

    }
    
}
