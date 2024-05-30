<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\MediaFile;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\File;

/**
 * LoadMediaFile form.
 */
class MediaFileFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface {
    public static function getGroups() : array {
        return ['dev', 'test'];
    }
    public function load(ObjectManager $em) : void {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new MediaFile();
            $fixture->setFile(new File(__FILE__, false));
            $fixture->setFilename('a/abc.php');
            $fixture->setHasThumbnail(true);
            $fixture->setOriginalName('OriginalName ' . $i);
            $fixture->setMediafilecategory($this->getReference('mediafilecategory.1'));

            $em->persist($fixture);
            $this->setReference('mediafile.' . $i, $fixture);
        }

        $em->flush();
    }

    public function getDependencies() {
        // add dependencies here, or remove this
        // function and "implements DependentFixtureInterface, FixtureGroupInterface" above
        return [
            MediaFileCategoryFixtures::class,
        ];
    }
}
