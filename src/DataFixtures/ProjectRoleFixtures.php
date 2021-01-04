<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\ProjectRole;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * LoadProjectRole form.
 */
class ProjectRoleFixtures extends Fixture {
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $em) : void {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new ProjectRole();
            $fixture->setName('project-role-' . $i);
            $fixture->setLabel('Project Role ' . $i);
            $em->persist($fixture);
            $this->setReference('projectrole.' . $i, $fixture);
        }

        $em->flush();
    }
}
