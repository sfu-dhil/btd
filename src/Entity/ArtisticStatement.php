<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ArtisticStatementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;
use Nines\UtilBundle\Entity\ContentEntityInterface;
use Nines\UtilBundle\Entity\ContentExcerptTrait;

#[ORM\Entity(repositoryClass: ArtisticStatementRepository::class)]
#[ORM\Table(name: 'artistic_statement')]
#[ORM\Index(columns: ['title', 'excerpt', 'content'], flags: ['fulltext'])]
class ArtisticStatement extends AbstractEntity implements ContentEntityInterface {
    use ContentExcerptTrait;

    #[ORM\Column(type: Types::STRING)]
    private ?string $title = null;

    #[ORM\ManyToOne(targetEntity: Artwork::class, inversedBy: 'artisticStatements')]
    private ?Artwork $artwork = null;

    /**
     * @var Collection|Person[]
     */
    #[ORM\ManyToMany(targetEntity: Person::class, inversedBy: 'artisticStatements')]
    #[ORM\JoinTable(name: 'person_artistic_statements')]
    private Collection $people;

    public function __construct() {
        parent::__construct();
        $this->people = new ArrayCollection();
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

    public function setArtwork(?Artwork $artwork = null) : self {
        $this->artwork = $artwork;

        return $this;
    }

    public function getArtwork() : ?Artwork {
        return $this->artwork;
    }

    public function addPerson(Person $person) : self {
        $this->people[] = $person;

        return $this;
    }

    public function removePerson(Person $person) : void {
        $this->people->removeElement($person);
    }

    public function getPeople() : Collection {
        return $this->people;
    }
}
