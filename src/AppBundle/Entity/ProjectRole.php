<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

/**
 * ProjectRole
 *
 * @ORM\Table(name="project_role")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjectRoleRepository")
 */
class ProjectRole extends AbstractTerm
{
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
     * Add contribution
     *
     * @param ProjectContribution $contribution
     *
     * @return ProjectRole
     */
    public function addContribution(ProjectContribution $contribution)
    {
        $this->contributions[] = $contribution;

        return $this;
    }

    /**
     * Remove contribution
     *
     * @param ProjectContribution $contribution
     */
    public function removeContribution(ProjectContribution $contribution)
    {
        $this->contributions->removeElement($contribution);
    }

    /**
     * Get contributions
     *
     * @return Collection
     */
    public function getContributions()
    {
        return $this->contributions;
    }
}
