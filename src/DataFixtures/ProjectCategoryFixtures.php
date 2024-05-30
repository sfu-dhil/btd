<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\ProjectCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

/**
 * LoadProjectCategory form.
 */
class ProjectCategoryFixtures extends Fixture implements FixtureGroupInterface {
    public static function getGroups() : array {
        return ['dev', 'test'];
    }
    public function load(ObjectManager $em) : void {
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
