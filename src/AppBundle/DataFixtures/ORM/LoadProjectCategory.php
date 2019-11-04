<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\ProjectCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadProjectCategory form.
 */
class LoadProjectCategory extends Fixture {
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $em) {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new ProjectCategory();
            $fixture->setName('project-category-' . $i);
            $fixture->setLabel('Project Category ' . $i);
            $em->persist($fixture);
            $this->setReference('projectcategory.' . $i, $fixture);
        }

        $em->flush();
    }
}
