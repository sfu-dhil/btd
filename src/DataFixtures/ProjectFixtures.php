<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Project;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * LoadProject form.
 */
class ProjectFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface {
    public static function getGroups() : array {
        return ['dev', 'test'];
    }
    public function load(ObjectManager $em) : void {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new Project();
            $fixture->setTitle('Title ' . $i);
            $fixture->setStartDate(new DateTimeImmutable('2010-01-01'));
            $fixture->setEndDate(new DateTimeImmutable('2010-01-02'));
            $fixture->setExcerpt('Excerpt ' . $i);
            $fixture->setContent('Description ' . $i);
            $fixture->setUrl('http://example.com/project/' . $i);
            $fixture->setProjectCategory($this->getReference('projectcategory.1'));
            $fixture->addVenue($this->getReference('venue.1'));

            $em->persist($fixture);
            $this->setReference('project.' . $i, $fixture);
        }

        $em->flush();
    }

    public function getDependencies() {
        // add dependencies here, or remove this
        // function and "implements DependentFixtureInterface, FixtureGroupInterface" above
        return [
            ProjectCategoryFixtures::class,
            VenueFixtures::class,
        ];
    }
}
