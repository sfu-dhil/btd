<?php

namespace App\DataFixtures;

use App\Entity\Person;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * LoadPerson form.
 */
class PersonFixtures extends Fixture {
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $em) {
        for ($i = 0; $i < 4; $i++) {
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
