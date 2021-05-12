<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\VenueCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * LoadVenueCategory form.
 */
class VenueCategoryFixtures extends Fixture {
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $em) : void {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new VenueCategory();
            $fixture->setName('venue-category-' . $i);
            $fixture->setLabel('Venue Category ' . $i);
            $em->persist($fixture);
            $this->setReference('venuecategory.' . $i, $fixture);
        }

        $em->flush();
    }
}
