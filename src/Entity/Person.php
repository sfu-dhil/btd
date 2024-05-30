<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PersonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

#[ORM\Entity(repositoryClass: PersonRepository::class)]
#[ORM\Table(name: 'person')]
#[ORM\Index(columns: ['fullname', 'biography'], flags: ['fulltext'])]
class Person extends AbstractEntity {
    #[ORM\Column(type: Types::STRING)]
    private ?string $fullname = null;

    #[ORM\Column(type: Types::STRING)]
    private ?string $sortableName = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $biography = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $urls = [];

    /**
     * @var ArtworkContribution[]|Collection
     */
    #[ORM\OneToMany(targetEntity: ArtworkContribution::class, mappedBy: 'person', cascade: ['persist'], orphanRemoval: true)]
    private Collection $artworkContributions;

    /**
     * @var ArtisticStatement[]|Collection
     */
    #[ORM\ManyToMany(targetEntity: ArtisticStatement::class, mappedBy: 'people')]
    private Collection $artisticStatements;

    /**
     * @var Collection|ProjectContribution[]
     */
    #[ORM\OneToMany(targetEntity: ProjectContribution::class, mappedBy: 'person', cascade: ['persist'], orphanRemoval: true)]
    private Collection $projectContributions;

    /**
     * @var Collection|MediaFile[]
     */
    #[ORM\ManyToMany(targetEntity: MediaFile::class, inversedBy: 'people')]
    #[ORM\JoinTable(name: 'person_mediafiles')]
    private Collection $mediaFiles;

    public function __construct() {
        $this->mediaFiles = new ArrayCollection();
        parent::__construct();
        $this->artworkContributions = new ArrayCollection();
        $this->projectContributions = new ArrayCollection();
        $this->artisticStatements = new ArrayCollection();
        $this->urls = [];
    }

    public function __toString() : string {
        return $this->fullname;
    }

    public function setFullname(?string $fullname) : self {
        $this->fullname = $fullname;

        return $this;
    }

    public function getFullname() : ?string {
        return $this->fullname;
    }

    public function setSortableName(?string $sortableName) : self {
        $this->sortableName = $sortableName;

        return $this;
    }

    public function getSortableName() : ?string {
        return $this->sortableName;
    }

    public function setBiography(?string $biography) : self {
        $this->biography = $biography;

        return $this;
    }

    public function getBiography() : ?string {
        return $this->biography;
    }

    public function addArtworkContribution(ArtworkContribution $artworkContribution) : self {
        $this->artworkContributions[] = $artworkContribution;

        return $this;
    }

    public function removeArtworkContribution(ArtworkContribution $artworkContribution) : void {
        $this->artworkContributions->removeElement($artworkContribution);
    }

    public function getArtworkContributions() : Collection {
        return $this->artworkContributions;
    }

    public function addProjectContribution(ProjectContribution $projectContribution) : self {
        $this->projectContributions[] = $projectContribution;

        return $this;
    }

    public function removeProjectContribution(ProjectContribution $projectContribution) : void {
        $this->projectContributions->removeElement($projectContribution);
    }

    public function getProjectContributions() : Collection {
        return $this->projectContributions;
    }

    public function getUrl() : ?string {
        if (count($this->urls) > 0) {
            return $this->urls[0];
        }
    }

    public function setUrls(array $urls) : self {
        $this->urls = $urls;

        return $this;
    }

    public function getUrls() : array {
        return $this->urls;
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

    public function addArtisticStatement(ArtisticStatement $artisticStatement) : self {
        $this->artisticStatements[] = $artisticStatement;

        return $this;
    }

    /**
     * Remove artisticStatement.
     */
    public function removeArtisticStatement(ArtisticStatement $artisticStatement) : void {
        $this->artisticStatements->removeElement($artisticStatement);
    }

    public function getArtisticStatements() : Collection {
        return $this->artisticStatements;
    }
}
