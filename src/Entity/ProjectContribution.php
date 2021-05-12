<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

/**
 * ProjectContribution.
 *
 * @ORM\Table(name="project_contribution")
 * @ORM\Entity(repositoryClass="App\Repository\ProjectContributionRepository")
 */
class ProjectContribution extends AbstractEntity {
    /**
     * @var Project
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="contributions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $project;

    /**
     * @var Person
     * @ORM\ManyToOne(targetEntity="Person", inversedBy="projectContributions")
     */
    private $person;

    /**
     * @var Person
     * @ORM\ManyToOne(targetEntity="Organization", inversedBy="projectContributions")
     */
    private $organization;

    /**
     * @var ProjectRole
     * @ORM\ManyToOne(targetEntity="ProjectRole", inversedBy="contributions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $projectRole;

    public function __toString() : string {
        return implode(':', [$this->project, $this->person, $this->organization, $this->projectRole]);
    }

    /**
     * Set project.
     *
     * @param Project $project
     *
     * @return ProjectContribution
     */
    public function setProject(?Project $project = null) {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project.
     *
     * @return Project
     */
    public function getProject() {
        return $this->project;
    }

    /**
     * Set person.
     *
     * @param Person $person
     *
     * @return ProjectContribution
     */
    public function setPerson(?Person $person = null) {
        $this->person = $person;

        return $this;
    }

    /**
     * Get person.
     *
     * @return Person
     */
    public function getPerson() {
        return $this->person;
    }

    /**
     * Set organization.
     *
     * @param Organization $organization
     *
     * @return ProjectContribution
     */
    public function setOrganization(?Organization $organization = null) {
        $this->organization = $organization;

        return $this;
    }

    /**
     * Get organization.
     *
     * @return Organization
     */
    public function getOrganization() {
        return $this->organization;
    }

    /**
     * Set projectRole.
     *
     * @param ProjectRole $projectRole
     *
     * @return ProjectContribution
     */
    public function setProjectRole(?ProjectRole $projectRole = null) {
        $this->projectRole = $projectRole;

        return $this;
    }

    /**
     * Get projectRole.
     *
     * @return ProjectRole
     */
    public function getProjectRole() {
        return $this->projectRole;
    }
}
