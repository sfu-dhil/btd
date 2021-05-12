<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\Location;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * LoadLocation form.
 */
class LocationFixtures extends Fixture {
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $em) : void {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new Location();
            $fixture->setCity('City ' . $i);
            $fixture->setRegion('Region ' . $i);
            $fixture->setCountry('Country ' . $i);

            $em->persist($fixture);
            $this->setReference('location.' . $i, $fixture);
        }

        $em->flush();
    }
}
