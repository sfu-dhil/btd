<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProjectRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;
use Nines\UtilBundle\Entity\ContentEntityInterface;
use Nines\UtilBundle\Entity\ContentExcerptTrait;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
#[ORM\Table(name: 'project')]
#[ORM\Index(columns: ['title', 'content'], flags: ['fulltext'])]
class Project extends AbstractEntity implements ContentEntityInterface {
    use ContentExcerptTrait;

    #[ORM\Column(type: Types::STRING)]
    private ?string $title = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?DateTimeInterface $endDate = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $url = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'children')]
    private ?Project $parent = null;

    /**
     * @var Collection|Project[]
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'parent')]
    private Collection $children;

    #[ORM\ManyToOne(targetEntity: ProjectCategory::class, inversedBy: 'projects')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProjectCategory $projectCategory = null;

    /**
     * @var Collection|Venue[]
     */
    #[ORM\ManyToMany(targetEntity: Venue::class, inversedBy: 'projects')]
    #[ORM\JoinTable(name: 'project_venues')]
    private Collection $venues;

    /**
     * @var Collection|ProjectContribution[]
     */
    #[ORM\OneToMany(targetEntity: ProjectContribution::class, mappedBy: 'project', cascade: ['persist'], orphanRemoval: true)]
    private Collection $contributions;

    /**
     * @var Collection|ProjectPage[]
     */
    #[ORM\OneToMany(targetEntity: ProjectPage::class, mappedBy: 'project')]
    private Collection $projectPages;

    /**
     * @var Collection|MediaFile[]
     */
    #[ORM\ManyToMany(targetEntity: MediaFile::class, inversedBy: 'projects')]
    #[ORM\JoinTable(name: 'project_mediafiles')]
    private Collection $mediaFiles;

    /**
     * @var Artwork[]|Collection
     */
    #[ORM\ManyToMany(targetEntity: Artwork::class, inversedBy: 'projects')]
    #[ORM\JoinTable(name: 'project_artworks')]
    private Collection $artworks;

    public function __construct() {
        $this->projectPages = new ArrayCollection();
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

    public function setTitle(?string $title) : self {
        $this->title = $title;

        return $this;
    }

    public function getTitle() : ?string {
        return $this->title;
    }

    public function setUrl(?string $url) : self {
        $this->url = $url;

        return $this;
    }

    public function getUrl() : ?string {
        return $this->url;
    }

    public function setProjectCategory(?ProjectCategory $projectCategory = null) : self {
        $this->projectCategory = $projectCategory;

        return $this;
    }

    public function getProjectCategory() : ?ProjectCategory {
        return $this->projectCategory;
    }

    public function addVenue(Venue $venue) : self {
        $this->venues[] = $venue;

        return $this;
    }

    public function removeVenue(Venue $venue) : void {
        $this->venues->removeElement($venue);
    }

    public function getVenues() : Collection {
        return $this->venues;
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

    public function hasMediaFile(MediaFile $mediaFile) : bool {
        return $this->mediaFiles->contains($mediaFile);
    }

    public function addMediaFile(MediaFile $mediaFile) : self {
        if ( ! $this->mediaFiles->contains($mediaFile)) {
            $this->mediaFiles[] = $mediaFile;
        }

        return $this;
    }

    public function removeMediaFile(MediaFile $mediaFile) : void {
        $this->mediaFiles->removeElement($mediaFile);
    }

    public function getMediaFiles() : Collection {
        return $this->mediaFiles;
    }

    public function setStartDate(?DateTimeInterface $startDate) : self {
        $this->startDate = $startDate;

        return $this;
    }

    public function getStartDate() : ?DateTimeInterface {
        return $this->startDate;
    }

    public function setEndDate(?DateTimeInterface $endDate) : self {
        $this->endDate = $endDate;

        return $this;
    }

    public function getEndDate() : ?DateTimeInterface {
        return $this->endDate;
    }

    public function addProjectPage(ProjectPage $projectPage) : self {
        $this->projectPages[] = $projectPage;

        return $this;
    }

    public function removeProjectPage(ProjectPage $projectPage) : void {
        $this->projectPages->removeElement($projectPage);
    }

    public function getProjectPages() : Collection {
        return $this->projectPages;
    }

    public function addArtwork(Artwork $artwork) : self {
        $this->artworks[] = $artwork;

        return $this;
    }

    public function hasArtwork(Artwork $artwork) {
        return $this->artworks->contains($artwork);
    }

    public function removeArtwork(Artwork $artwork) : void {
        $this->artworks->removeElement($artwork);
    }

    public function getArtworks() : Collection {
        return $this->artworks;
    }

    public function setParent(?self $parent = null) : self {
        $this->parent = $parent;

        return $this;
    }

    public function getParent() : ?self {
        return $this->parent;
    }

    public function addChild(self $child) : self {
        $this->children[] = $child;

        return $this;
    }

    public function removeChild(self $child) : bool {
        return $this->children->removeElement($child);
    }

    public function getChildren() : Collection {
        return $this->children;
    }
}
