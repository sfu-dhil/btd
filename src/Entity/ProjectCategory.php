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
 * ProjectCategory.
 *
 * @ORM\Table(name="project_category")
 * @ORM\Entity(repositoryClass="App\Repository\ProjectCategoryRepository")
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
     * @return ProjectCategory
     */
    public function addProject(Project $project) {
        $this->projects[] = $project;

        return $this;
    }

    /**
     * Remove project.
     */
    public function removeProject(Project $project) : void {
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
