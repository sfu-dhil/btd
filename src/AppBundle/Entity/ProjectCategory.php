<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

/**
 * ProjectCategory.
 *
 * @ORM\Table(name="project_category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjectCategoryRepository")
 */
class ProjectCategory extends AbstractTerm {
    /**
     * @var Collection|Project[]
     * @ORM\OneToMany(targetEntity="Project", mappedBy="projectCategory")
     */
    private $projects;

    public function __construct() {
        parent::__construct();
        $this->projects = new ArrayCollection();
    }

    /**
     * Add project.
     *
     * @param Project $project
     *
     * @return ProjectCategory
     */
    public function addProject(Project $project) {
        $this->projects[] = $project;

        return $this;
    }

    /**
     * Remove project.
     *
     * @param Project $project
     */
    public function removeProject(Project $project) {
        $this->projects->removeElement($project);
    }

    /**
     * Get projects.
     *
     * @return Collection
     */
    public function getProjects() {
        return $this->projects;
    }
}
