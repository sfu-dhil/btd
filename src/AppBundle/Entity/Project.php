<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

/**
 * Project
 *
 * @ORM\Table(name="project", indexes={
 *  @ORM\Index(columns={"title", "description"}, flags={"fulltext"}),
 * })
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjectRepository")
 */
class Project extends AbstractEntity {

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $title;
    
    /**
     * @var DateTime
     * @ORM\Column(type="datetimetz")
     */
    private $startDate;
    
    /**
     * @var DateTime
     * @ORM\Column(type="datetimetz")
     */
    private $endDate;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $excerpt;
    
    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $url;

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
     * @var Collection|Artwork[]
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
    }

    public function __toString() {
        return $this->title;
    }

    /**
     * Set title
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
     * Get title
     *
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Project
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set url
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
     * Get url
     *
     * @return string
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * Set projectCategory
     *
     * @param ProjectCategory $projectCategory
     *
     * @return Project
     */
    public function setProjectCategory(ProjectCategory $projectCategory = null) {
        $this->projectCategory = $projectCategory;

        return $this;
    }

    /**
     * Get projectCategory
     *
     * @return ProjectCategory
     */
    public function getProjectCategory() {
        return $this->projectCategory;
    }

    /**
     * Add venue
     *
     * @param Venue $venue
     *
     * @return Project
     */
    public function addVenue(Venue $venue) {
        $this->venues[] = $venue;

        return $this;
    }

    /**
     * Remove venue
     *
     * @param Venue $venue
     */
    public function removeVenue(Venue $venue) {
        $this->venues->removeElement($venue);
    }

    /**
     * Get venues
     *
     * @return Collection
     */
    public function getVenues() {
        return $this->venues;
    }

    /**
     * Add contribution
     *
     * @param ProjectContribution $contribution
     *
     * @return Project
     */
    public function addContribution(ProjectContribution $contribution) {
        $this->contributions[] = $contribution;

        return $this;
    }

    /**
     * Remove contribution
     *
     * @param ProjectContribution $contribution
     */
    public function removeContribution(ProjectContribution $contribution) {
        $this->contributions->removeElement($contribution);
    }

    /**
     * Get contributions
     *
     * @return Collection
     */
    public function getContributions() {
        return $this->contributions;
    }

    /**
     * Check if a media file is associated with this project.
     * 
     * @param MediaFile $mediaFile
     * @return boolean
     */
    public function hasMediaFile(MediaFile $mediaFile) {
        return $this->mediaFiles->contains($mediaFile);
    }
    
    /**
     * Add mediaFile
     *
     * @param MediaFile $mediaFile
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
     * Remove mediaFile
     *
     * @param MediaFile $mediaFile
     */
    public function removeMediaFile(MediaFile $mediaFile) {
        $this->mediaFiles->removeElement($mediaFile);
    }

    /**
     * Get mediaFiles
     *
     * @return Collection
     */
    public function getMediaFiles() {
        return $this->mediaFiles;
    }


    /**
     * Set startDate
     *
     * @param DateTime $startDate
     *
     * @return Project
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param DateTime $endDate
     *
     * @return Project
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set excerpt
     *
     * @param string $excerpt
     *
     * @return Project
     */
    public function setExcerpt($excerpt)
    {
        $this->excerpt = $excerpt;

        return $this;
    }

    /**
     * Get excerpt
     *
     * @return string
     */
    public function getExcerpt()
    {
        return $this->excerpt;
    }

    /**
     * Add projectPage
     *
     * @param ProjectPage $projectPage
     *
     * @return Project
     */
    public function addProjectPage(ProjectPage $projectPage)
    {
        $this->projectPages[] = $projectPage;

        return $this;
    }

    /**
     * Remove projectPage
     *
     * @param ProjectPage $projectPage
     */
    public function removeProjectPage(ProjectPage $projectPage)
    {
        $this->projectPages->removeElement($projectPage);
    }

    /**
     * Get projectPages
     *
     * @return Collection
     */
    public function getProjectPages()
    {
        return $this->projectPages;
    }

    /**
     * Add artwork
     *
     * @param Artwork $artwork
     *
     * @return Project
     */
    public function addArtwork(Artwork $artwork)
    {
        $this->artworks[] = $artwork;

        return $this;
    }
    
    public function hasArtwork(Artwork $artwork) {
        return $this->artworks->contains($artwork);
    }

    /**
     * Remove artwork
     *
     * @param Artwork $artwork
     */
    public function removeArtwork(Artwork $artwork)
    {
        $this->artworks->removeElement($artwork);
    }

    /**
     * Get artworks
     *
     * @return Collection
     */
    public function getArtworks()
    {
        return $this->artworks;
    }
}
