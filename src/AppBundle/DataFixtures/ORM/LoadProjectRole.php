<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\ProjectRole;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadProjectRole form.
 */
class LoadProjectRole extends Fixture {
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $em) {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new ProjectRole();
            $fixture->setName('project-role-' . $i);
            $fixture->setLabel('Project Role ' . $i);
            $em->persist($fixture);
            $this->setReference('projectrole.' . $i, $fixture);
        }

        $em->flush();
    }
}
