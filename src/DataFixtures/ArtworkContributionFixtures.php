<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\ArtworkContribution;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ArtworkContributionFixtures extends Fixture implements DependentFixtureInterface {
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $em) : void {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new ArtworkContribution();
            $fixture->setArtwork($this->getReference('artwork.1'));
            $fixture->setPerson($this->getReference('person.1'));
            $fixture->setOrganization($this->getReference('organization.1'));
            $fixture->setArtworkrole($this->getReference('artworkrole.1'));

            $em->persist($fixture);
            $this->setReference('artworkcontribution.' . $i, $fixture);
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
            ArtworkFixtures::class,
            PersonFixtures::class,
            OrganizationFixtures::class,
            ArtworkRoleFixtures::class,
        ];
    }
}
