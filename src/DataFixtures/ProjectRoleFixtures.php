<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\ProjectRole;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

/**
 * LoadProjectRole form.
 */
class ProjectRoleFixtures extends Fixture implements FixtureGroupInterface {
    public static function getGroups() : array {
        return ['dev', 'test'];
    }
    public function load(ObjectManager $em) : void {
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
