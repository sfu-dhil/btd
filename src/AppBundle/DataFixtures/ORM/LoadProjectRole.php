<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\ProjectRole;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadProjectRole form.
 */
class LoadProjectRole extends Fixture
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em)
    {
        for($i = 0; $i < 4; $i++) {
            $fixture = new ProjectRole();

            $em->persist($fixture);
            $this->setReference('projectrole.' . $i);
        }

        $em->flush();

    }
    
}
