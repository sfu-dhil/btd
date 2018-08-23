<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\MediaFileField;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Nines\DublinCoreBundle\DataFixtures\ORM\LoadElement;

/**
 * LoadMediaFileField form.
 */
class LoadMediaFileField extends Fixture implements DependentFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em)
    {
        for($i = 0; $i < 4; $i++) {
            $fixture = new MediaFileField();
            $fixture->setValue('Media file field value ' . $i);
            $fixture->setElement($this->getReference('dc_title'));
            $fixture->setMediafile($this->getReference('mediafile.1'));

            $em->persist($fixture);
            $this->setReference('mediafilefield.' . $i, $fixture);
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
            LoadMediaFile::class,
            LoadElement::class,
        ];
    }


}
