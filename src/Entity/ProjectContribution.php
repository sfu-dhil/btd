<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProjectContributionRepository;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

/**
 * ProjectContribution.
 */
#[ORM\Entity(repositoryClass: ProjectContributionRepository::class)]
#[ORM\Table(name: 'project_contribution')]
class ProjectContribution extends AbstractEntity {
    #[ORM\ManyToOne(targetEntity: Project::class, inversedBy: 'contributions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Project $project = null;

    #[ORM\ManyToOne(targetEntity: Person::class, inversedBy: 'projectContributions')]
    private ?Person $person = null;

    #[ORM\ManyToOne(targetEntity: Organization::class, inversedBy: 'projectContributions')]
    private ?Organization $organization = null;

    #[ORM\ManyToOne(targetEntity: ProjectRole::class, inversedBy: 'contributions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProjectRole $projectRole = null;

    public function __toString() : string {
        return implode(':', [$this->project, $this->person, $this->organization, $this->projectRole]);
    }

    public function setProject(?Project $project = null) : self {
        $this->project = $project;

        return $this;
    }

    public function getProject() : ?Project {
        return $this->project;
    }

    public function setPerson(?Person $person = null) : self {
        $this->person = $person;

        return $this;
    }

    public function getPerson() : ?Person {
        return $this->person;
    }

    public function setOrganization(?Organization $organization = null) : self {
        $this->organization = $organization;

        return $this;
    }

    public function getOrganization() : ?Organization {
        return $this->organization;
    }

    public function setProjectRole(?ProjectRole $projectRole = null) : self {
        $this->projectRole = $projectRole;

        return $this;
    }

    public function getProjectRole() : ?ProjectRole {
        return $this->projectRole;
    }
}
