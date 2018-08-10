<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Person;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadPerson form.
 */
class LoadPerson extends Fixture
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
            $fixture->setUrls(array(
                'http://example.com/person/' . $i,
            ));

            $em->persist($fixture);
            $this->setReference('person.' . $i, $fixture);
        }

        $em->flush();

    }

}
