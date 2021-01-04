<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

/**
 * ProjectRole.
 *
 * @ORM\Table(name="project_role")
 * @ORM\Entity(repositoryClass="App\Repository\ProjectRoleRepository")
 */
class ProjectRole extends AbstractTerm {
    /**
     * @var Collection|ProjectContribution[]
     * @ORM\OneToMany(targetEntity="ProjectContribution", mappedBy="projectRole")
     */
    private $contributions;

    public function __construct() {
        parent::__construct();
        $this->contributions = new ArrayCollection();
    }

    /**
     * Add contribution.
     *
     * @return ProjectRole
     */
    public function addContribution(ProjectContribution $contribution) {
        $this->contributions[] = $contribution;

        return $this;
    }

    /**
     * Remove contribution.
     */
    public function removeContribution(ProjectContribution $contribution) : void {
        $this->contributions->removeElement($contribution);
    }

    /**
     * Get contributions.
     *
     * @return Collection
     */
    public function getContributions() {
        return $this->contributions;
    }
}
