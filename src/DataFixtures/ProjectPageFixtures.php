<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\ProjectPage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * LoadProjectPage form.
 */
class ProjectPageFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface {
    public static function getGroups() : array {
        return ['dev', 'test'];
    }
    public function load(ObjectManager $em) : void {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new ProjectPage();
            $fixture->setTitle('Title ' . $i);
            $fixture->setExcerpt('Excerpt ' . $i);
            $fixture->setContent('Content ' . $i);
            $fixture->setProject($this->getReference('project.1'));

            $em->persist($fixture);
            $this->setReference('projectpage.' . $i, $fixture);
        }

        $em->flush();
    }

    public function getDependencies() {
        // add dependencies here, or remove this
        // function and "implements DependentFixtureInterface, FixtureGroupInterface" above
        return [
            ProjectFixtures::class,
        ];
    }
}
