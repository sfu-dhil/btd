<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\ArtworkRole;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

/**
 * LoadArtworkRole form.
 */
class ArtworkRoleFixtures extends Fixture implements FixtureGroupInterface {
    public static function getGroups() : array {
        return ['dev', 'test'];
    }

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
