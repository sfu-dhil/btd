<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Organization;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadOrganization form.
 */
class LoadOrganization extends Fixture implements DependentFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em)
    {
        for($i = 0; $i < 4; $i++) {
            $fixture = new Organization();
            $fixture->setName('Name ' . $i);
            $fixture->setAddress('Address ' . $i);
            $fixture->setDescription('Description ' . $i);
            $fixture->setUrls(array(
                'http://example.com/organization/' . $i,
            ));
            $fixture->setContact('Contact ' . $i);
            $fixture->setLocation($this->getReference('location.1'));

            $em->persist($fixture);
            $this->setReference('organization.' . $i, $fixture);
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
        ];
    }


}
