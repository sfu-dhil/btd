<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\ArtworkCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadArtworkCategory form.
 */
class LoadArtworkCategory extends Fixture
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em)
    {
        for($i = 0; $i < 4; $i++) {
            $fixture = new ArtworkCategory();

            $em->persist($fixture);
            $this->setReference('artworkcategory.' . $i);
        }

        $em->flush();

    }
    
}
