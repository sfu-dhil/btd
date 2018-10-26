<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\MediaFile;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadMediaFile form.
 */
class LoadMediaFile extends Fixture implements DependentFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em)
    {
        for($i = 0; $i < 4; $i++) {
            $fixture = new MediaFile();
            $fixture->setFile(new \Symfony\Component\HttpFoundation\File\File(__FILE__, false));
            $fixture->setFilename("a/abc.php");
            $fixture->setHasThumbnail('HasThumbnail ' . $i);
            $fixture->setOriginalName('OriginalName ' . $i);
            $fixture->setMediafilecategory($this->getReference('mediafilecategory.1'));

            $em->persist($fixture);
            $this->setReference('mediafile.' . $i, $fixture);
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
            LoadMediaFileCategory::class,
        ];
    }


}
