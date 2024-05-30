<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\MediaFileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;
use Symfony\Component\HttpFoundation\File\File;

#[ORM\Entity(repositoryClass: MediaFileRepository::class)]
#[ORM\Table(name: 'media_file')]
class MediaFile extends AbstractEntity {
    /**
     * A Doctrine event listener will turn the filename into a file object.
     */
    private ?File $file = null;

    #[ORM\Column(name: 'file', type: Types::STRING)]
    private ?string $filename = null;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => false])]
    private ?bool $hasThumbnail = null;

    #[ORM\Column(type: Types::STRING)]
    private ?string $originalName = null;

    #[ORM\ManyToOne(targetEntity: MediaFileCategory::class, inversedBy: 'mediaFiles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?MediaFileCategory $mediaFileCategory = null;

    /**
     * @var Collection|MediaFileField[]
     */
    #[ORM\OneToMany(targetEntity: MediaFileField::class, mappedBy: 'mediaFile')]
    private Collection $metadataFields;

    /**
     * @var Collection|MediaFileField[]
     */
    #[ORM\ManyToMany(targetEntity: Artwork::class, mappedBy: 'mediaFiles')]
    private Collection $artworks;

    /**
     * @var Collection|MediaFileField[]
     */
    #[ORM\ManyToMany(targetEntity: Project::class, mappedBy: 'mediaFiles')]
    private Collection $projects;

    /**
     * @var Collection|MediaFileField[]
     */
    #[ORM\ManyToMany(targetEntity: Person::class, mappedBy: 'mediaFiles')]
    private Collection $people;

    public function __construct() {
        $this->artworks = new ArrayCollection();
        $this->projects = new ArrayCollection();
        parent::__construct();
        $this->metadataFields = new ArrayCollection();
        $this->people = new ArrayCollection();
        $this->hasThumbnail = false;
    }

    public function __toString() : string {
        return (string) $this->getId() ?? '';
    }

    public function setFilename($filename) : void {
        $this->filename = $filename;
    }

    public function getFilename() {
        return $this->filename;
    }

    public function getFile() : ?File {
        return $this->file;
    }

    public function setFile(File $file) : self {
        $this->file = $file;

        return $this;
    }

    public function getMimeType() : ?string {
        return $this->file->getMimeType();
    }

    public function getPath() : ?string {
        return $this->file->getPath();
    }

    public function getRealPath() : ?string {
        return $this->file->getRealPath();
    }

    public function getBasename() : ?string {
        return $this->file->getBasename('.' . $this->file->getExtension());
    }

    public function getSize() : bool|int {
        return $this->file->getSize();
    }

    public function getThumbnail() : ?File {
        $base = $this->getBasename();
        $path = $this->getPath();
        $name = $base . '_tn.jpg';
        $tnPath = $path . '/' . $name;
        if (file_exists($tnPath) && is_readable($tnPath)) {
            return new File($tnPath);
        }
    }

    public function setOriginalName(string $originalName) : self {
        $this->originalName = $originalName;

        return $this;
    }

    public function getOriginalName() : ?string {
        return $this->originalName;
    }

    public function addMetadataField(MediaFileField $metadataField) : self {
        $this->metadataFields[] = $metadataField;

        return $this;
    }

    public function removeMetadataField(MediaFileField $metadataField) : void {
        $this->metadataFields->removeElement($metadataField);
    }

    public function getMetadataFields(mixed $name = null, bool $list = true) : mixed {
        if ( ! $name) {
            return $this->metadataFields;
        }
        $matches = $this->metadataFields->filter(fn (AbstractField $field) => $field->getElement()->getName() === $name);
        if ($list) {
            return $matches;
        }

        return $matches->first();
    }

    public function setMediaFileCategory(?MediaFileCategory $mediaFileCategory = null) : self {
        $this->mediaFileCategory = $mediaFileCategory;

        return $this;
    }

    public function getMediaFileCategory() : ?MediaFileCategory {
        return $this->mediaFileCategory;
    }

    public function addArtwork(Artwork $artwork) : self {
        $this->artworks[] = $artwork;

        return $this;
    }

    public function removeArtwork(Artwork $artwork) : void {
        $this->artworks->removeElement($artwork);
    }

    public function getArtworks() : Collection {
        return $this->artworks;
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

    public function hasPerson(Person $person) : bool {
        return $this->people->contains($person);
    }

    public function addPerson(Person $person) : self {
        if ( ! $this->people->contains($person)) {
            $this->people[] = $person;
        }

        return $this;
    }

    public function removePerson(Person $person) : void {
        $this->people->removeElement($person);
    }

    public function getPeople() : Collection {
        return $this->people;
    }

    public function setHasThumbnail(bool $hasThumbnail) : self {
        $this->hasThumbnail = $hasThumbnail;

        return $this;
    }

    public function getHasThumbnail() : bool {
        return $this->hasThumbnail;
    }
}
