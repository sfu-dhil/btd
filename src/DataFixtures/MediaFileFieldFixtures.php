<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\MediaFileField;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * LoadMediaFileField form.
 */
class MediaFileFieldFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface {

    public static function getGroups() : array {
        return ['dev', 'test'];
    }

    public function load(ObjectManager $em) : void {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new MediaFileField();
            $fixture->setValue('Media file field value ' . $i);
            $fixture->setElement($this->getReference('dc_title'));
            $fixture->setMediafile($this->getReference('mediafile.1'));

            $em->persist($fixture);
            $this->setReference('mediafilefield.' . $i, $fixture);
        }

        $em->flush();
    }

    public function getDependencies() {
        // add dependencies here, or remove this
        // function and "implements DependentFixtureInterface, FixtureGroupInterface" above
        return [
            MediaFileFixtures::class,
            ElementFixtures::class,
        ];
    }
}
