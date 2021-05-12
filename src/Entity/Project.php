<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;
use Nines\UtilBundle\Entity\ContentEntityInterface;
use Nines\UtilBundle\Entity\ContentExcerptTrait;

/**
 * Project.
 *
 * @ORM\Table(name="project", indexes={
 *     @ORM\Index(columns={"title", "content"}, flags={"fulltext"}),
 * })
 * @ORM\Entity(repositoryClass="App\Repository\ProjectRepository")
 */
class Project extends AbstractEntity implements ContentEntityInterface {
    use ContentExcerptTrait;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @var DateTime
     * @ORM\Column(type="date")
     */
    private $startDate;

    /**
     * @var DateTime
     * @ORM\Column(type="date")
     */
    private $endDate;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $url;

    /**
     * @var Project
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="children")
     */
    private $parent;

    /**
     * @var Collection|Project[]
     * @ORM\OneToMany(targetEntity="Project", mappedBy="parent")
     */
    private $children;

    /**
     * @var ProjectCategory
     * @ORM\ManyToOne(targetEntity="ProjectCategory", inversedBy="projects")
     * @ORM\JoinColumn(nullable=false)
     */
    private $projectCategory;

    /**
     * @var Collection|Venue[]
     * @ORM\ManyToMany(targetEntity="Venue", inversedBy="projects")
     * @ORM\JoinTable(name="project_venues")
     */
    private $venues;

    /**
     * @var Collection|ProjectContribution[]
     * @ORM\OneToMany(targetEntity="ProjectContribution", mappedBy="project", cascade={"persist"}, orphanRemoval=true)
     */
    private $contributions;

    /**
     * @var Collection|ProjectPage[]
     * @ORM\OneToMany(targetEntity="ProjectPage", mappedBy="project")
     */
    private $projectPages;

    /**
     * @var Collection|MediaFile[]
     * @ORM\ManyToMany(targetEntity="MediaFile", inversedBy="projects")
     * @ORM\JoinTable(name="project_mediafiles")
     */
    private $mediaFiles;

    /**
     * @var Artwork[]|Collection
     * @ORM\ManyToMany(targetEntity="Artwork", inversedBy="projects")
     * @ORM\JoinTable(name="project_artworks")
     */
    private $artworks;

    public function __construct() {
        parent::__construct();
        $this->venues = new ArrayCollection();
        $this->contributions = new ArrayCollection();
        $this->mediaFiles = new ArrayCollection();
        $this->artworks = new ArrayCollection();
        $this->children = new ArrayCollection();
    }

    public function __toString() : string {
        return $this->title;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return Project
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set url.
     *
     * @param string $url
     *
     * @return Project
     */
    public function setUrl($url) {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url.
     *
     * @return string
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * Set projectCategory.
     *
     * @param ProjectCategory $projectCategory
     *
     * @return Project
     */
    public function setProjectCategory(?ProjectCategory $projectCategory = null) {
        $this->projectCategory = $projectCategory;

        return $this;
    }

    /**
     * Get projectCategory.
     *
     * @return ProjectCategory
     */
    public function getProjectCategory() {
        return $this->projectCategory;
    }

    /**
     * Add venue.
     *
     * @return Project
     */
    public function addVenue(Venue $venue) {
        $this->venues[] = $venue;

        return $this;
    }

    /**
     * Remove venue.
     */
    public function removeVenue(Venue $venue) : void {
        $this->venues->removeElement($venue);
    }

    /**
     * Get venues.
     *
     * @return Collection
     */
    public function getVenues() {
        return $this->venues;
    }

    /**
     * Add contribution.
     *
     * @return Project
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

    /**
     * Check if a media file is associated with this project.
     *
     * @return bool
     */
    public function hasMediaFile(MediaFile $mediaFile) {
        return $this->mediaFiles->contains($mediaFile);
    }

    /**
     * Add mediaFile.
     *
     * @return Project
     */
    public function addMediaFile(MediaFile $mediaFile) {
        if ( ! $this->mediaFiles->contains($mediaFile)) {
            $this->mediaFiles[] = $mediaFile;
        }

        return $this;
    }

    /**
     * Remove mediaFile.
     */
    public function removeMediaFile(MediaFile $mediaFile) : void {
        $this->mediaFiles->removeElement($mediaFile);
    }

    /**
     * Get mediaFiles.
     *
     * @return Collection
     */
    public function getMediaFiles() {
        return $this->mediaFiles;
    }

    /**
     * Set startDate.
     *
     * @param DateTime $startDate
     *
     * @return Project
     */
    public function setStartDate($startDate) {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate.
     *
     * @return DateTime
     */
    public function getStartDate() {
        return $this->startDate;
    }

    /**
     * Set endDate.
     *
     * @param DateTime $endDate
     *
     * @return Project
     */
    public function setEndDate($endDate) {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate.
     *
     * @return DateTime
     */
    public function getEndDate() {
        return $this->endDate;
    }

    /**
     * Add projectPage.
     *
     * @return Project
     */
    public function addProjectPage(ProjectPage $projectPage) {
        $this->projectPages[] = $projectPage;

        return $this;
    }

    /**
     * Remove projectPage.
     */
    public function removeProjectPage(ProjectPage $projectPage) : void {
        $this->projectPages->removeElement($projectPage);
    }

    /**
     * Get projectPages.
     *
     * @return Collection
     */
    public function getProjectPages() {
        return $this->projectPages;
    }

    /**
     * Add artwork.
     *
     * @return Project
     */
    public function addArtwork(Artwork $artwork) {
        $this->artworks[] = $artwork;

        return $this;
    }

    public function hasArtwork(Artwork $artwork) {
        return $this->artworks->contains($artwork);
    }

    /**
     * Remove artwork.
     */
    public function removeArtwork(Artwork $artwork) : void {
        $this->artworks->removeElement($artwork);
    }

    /**
     * Get artworks.
     *
     * @return Collection
     */
    public function getArtworks() {
        return $this->artworks;
    }

    /**
     * Set parent.
     *
     * @param ?self $parent
     *
     * @return Project
     */
    public function setParent(?self $parent = null) {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent.
     *
     * @return null|Project
     */
    public function getParent() {
        return $this->parent;
    }

    /**
     * Add child.
     *
     * @return Project
     */
    public function addChild(self $child) {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child.
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeChild(self $child) {
        return $this->children->removeElement($child);
    }

    /**
     * Get children.
     *
     * @return Collection
     */
    public function getChildren() {
        return $this->children;
    }
}
