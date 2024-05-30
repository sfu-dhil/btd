<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ArtworkRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;
use Nines\UtilBundle\Entity\ContentEntityInterface;
use Nines\UtilBundle\Entity\ContentExcerptTrait;

#[ORM\Entity(repositoryClass: ArtworkRepository::class)]
#[ORM\Table(name: 'artwork')]
#[ORM\Index(columns: ['title', 'content', 'materials', 'copyright'], flags: ['fulltext'])]
class Artwork extends AbstractEntity implements ContentEntityInterface {
    use ContentExcerptTrait;

    #[ORM\Column(type: Types::STRING)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $materials = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $copyright = null;

    #[ORM\ManyToOne(targetEntity: ArtworkCategory::class, inversedBy: 'artworks')]
    private ?ArtworkCategory $artworkCategory = null;

    /**
     * @var ArtworkContribution[]|Collection
     */
    #[ORM\OneToMany(targetEntity: ArtworkContribution::class, mappedBy: 'artwork', cascade: ['persist'], orphanRemoval: true)]
    private Collection $contributions;

    /**
     * @var ArtisticStatement[]|Collection
     */
    #[ORM\OneToMany(targetEntity: ArtisticStatement::class, mappedBy: 'artwork')]
    private Collection $artisticStatements;

    /**
     * @var Collection|MediaFile[]
     */
    #[ORM\ManyToMany(targetEntity: MediaFile::class, inversedBy: 'artworks')]
    #[ORM\JoinTable(name: 'artwork_mediafiles')]
    private Collection $mediaFiles;

    /**
     * @var Collection|Project[]
     */
    #[ORM\ManyToMany(targetEntity: Project::class, mappedBy: 'artworks')]
    private Collection $projects;

    public function __construct() {
        parent::__construct();
        $this->contributions = new ArrayCollection();
        $this->mediaFiles = new ArrayCollection();
        $this->projects = new ArrayCollection();
        $this->artisticStatements = new ArrayCollection();
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

    public function setMaterials(?string $materials) : self {
        $this->materials = $materials;

        return $this;
    }

    public function getMaterials() : ?string {
        return $this->materials;
    }

    public function setCopyright(?string $copyright) : self {
        $this->copyright = $copyright;

        return $this;
    }

    public function getCopyright() : ?string {
        return $this->copyright;
    }

    public function addContribution(ArtworkContribution $contribution) : self {
        $this->contributions[] = $contribution;

        return $this;
    }

    public function removeContribution(ArtworkContribution $contribution) : void {
        $this->contributions->removeElement($contribution);
    }

    public function getContributions() : Collection {
        return $this->contributions;
    }

    public function hasMediaFile(MediaFile $mediaFile) {
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

    public function setArtworkCategory(?ArtworkCategory $artworkCategory = null) : self {
        $this->artworkCategory = $artworkCategory;

        return $this;
    }

    public function getArtworkCategory() : ?ArtworkCategory {
        return $this->artworkCategory;
    }

    public function addArtisticStatement(ArtisticStatement $artisticStatement) : self {
        $this->artisticStatements[] = $artisticStatement;

        return $this;
    }

    public function removeArtisticStatement(ArtisticStatement $artisticStatement) : bool {
        return $this->artisticStatements->removeElement($artisticStatement);
    }

    public function getArtisticStatements() : Collection {
        return $this->artisticStatements;
    }
}
