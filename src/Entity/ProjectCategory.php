<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProjectCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

/**
 * ProjectCategory.
 */
#[ORM\Entity(repositoryClass: ProjectCategoryRepository::class)]
#[ORM\Table(name: 'project_category')]
class ProjectCategory extends AbstractTerm {
    /**
     * @var Collection|Project[]
     */
    #[ORM\OneToMany(targetEntity: Project::class, mappedBy: 'projectCategory')]
    private Collection $projects;

    public function __construct() {
        parent::__construct();
        $this->projects = new ArrayCollection();
    }

    public function addProject(Project $project) : self {
        $this->projects[] = $project;

        return $this;
    }

    public function removeProject(Project $project) : void {
        $this->projects->removeElement($project);
    }

    public function getProjects() : Collection {
        return $this->projects;
    }
}
