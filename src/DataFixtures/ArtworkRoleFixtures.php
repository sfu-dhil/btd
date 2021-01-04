<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\ArtworkRole;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * LoadArtworkRole form.
 */
class ArtworkRoleFixtures extends Fixture {
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $em) : void {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new ArtworkRole();
            $fixture->setName('artwork-role-' . $i);
            $fixture->setLabel('Artwork Role ' . $i);
            $em->persist($fixture);
            $this->setReference('artworkrole.' . $i, $fixture);
        }

        $em->flush();
    }
}
