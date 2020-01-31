<?php

namespace App\DataFixtures;

use App\Entity\ProjectCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * LoadProjectCategory form.
 */
class ProjectCategoryFixtures extends Fixture {
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
