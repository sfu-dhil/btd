<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\ProjectContribution;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * LoadProjectContribution form.
 */
class ProjectContributionFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface {
    public static function getGroups() : array {
        return ['dev', 'test'];
    }
    public function load(ObjectManager $em) : void {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new ProjectContribution();
            $fixture->setProject($this->getReference('project.1'));
            $fixture->setPerson($this->getReference('person.1'));
            $fixture->setOrganization($this->getReference('organization.1'));
            $fixture->setProjectRole($this->getReference('projectrole.1'));

            $em->persist($fixture);
            $this->setReference('projectcontribution.' . $i, $fixture);
        }

        $em->flush();
    }

    public function getDependencies() {
        // add dependencies here, or remove this
        // function and "implements DependentFixtureInterface, FixtureGroupInterface" above
        return [
            PersonFixtures::class,
            ProjectFixtures::class,
            OrganizationFixtures::class,
            ProjectRoleFixtures::class,
        ];
    }
}
