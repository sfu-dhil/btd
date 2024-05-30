<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProjectRoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

/**
 * ProjectRole.
 */
#[ORM\Entity(repositoryClass: ProjectRoleRepository::class)]
#[ORM\Table(name: 'project_role')]
class ProjectRole extends AbstractTerm {
    /**
     * @var Collection|ProjectContribution[]
     */
    #[ORM\OneToMany(targetEntity: ProjectContribution::class, mappedBy: 'projectRole')]
    private Collection $contributions;

    public function __construct() {
        parent::__construct();
        $this->contributions = new ArrayCollection();
    }

    public function addContribution(ProjectContribution $contribution) : self {
        $this->contributions[] = $contribution;

        return $this;
    }

    public function removeContribution(ProjectContribution $contribution) : void {
        $this->contributions->removeElement($contribution);
    }

    public function getContributions() : Collection {
        return $this->contributions;
    }
}
