<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\Project;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * LoadProject form.
 */
class ProjectFixtures extends Fixture implements DependentFixtureInterface {
    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
    public function getDependencies() {
        // add dependencies here, or remove this
        // function and "implements DependentFixtureInterface" above
        return [
            ProjectCategoryFixtures::class,
            VenueFixtures::class,
        ];
    }
}
