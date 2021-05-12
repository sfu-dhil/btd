<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\Venue;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * LoadVenue form.
 */
class VenueFixtures extends Fixture implements DependentFixtureInterface {
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $em) : void {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new Venue();
            $fixture->setName('Name ' . $i);
            $fixture->setAddress('Address ' . $i);
            $fixture->setDescription('Description ' . $i);
            $fixture->setUrl('Url ' . $i);
            $fixture->setLocation($this->getReference('location.1'));
            $fixture->setVenueCategory($this->getReference('venuecategory.1'));

            $em->persist($fixture);
            $this->setReference('venue.' . $i, $fixture);
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
            LocationFixtures::class,
            VenueCategoryFixtures::class,
        ];
    }
}
