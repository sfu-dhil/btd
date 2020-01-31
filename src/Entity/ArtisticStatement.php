<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection as Collection2;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;
use Nines\UtilBundle\Entity\ContentEntityInterface;
use Nines\UtilBundle\Entity\ContentExcerptTrait;
use Symfony\Component\Validator\Constraints\Collection;

/**
 * ArtisticStatement.
 *
 * @ORM\Table(name="artistic_statement", indexes={
 *  @ORM\Index(columns={"title", "excerpt", "content"}, flags={"fulltext"}),
 * })
 * @ORM\Entity(repositoryClass="App\Repository\PersonRepository")
 */
class ArtisticStatement extends AbstractEntity implements ContentEntityInterface {
    use ContentExcerptTrait;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @var Artwork
     * @ORM\ManyToOne(targetEntity="Artwork")
     */
    private $artwork;

    /**
     * @var Collection|Person[]
     * @ORM\ManyToMany(targetEntity="Person", inversedBy="artisticStatements")
     * @ORM\JoinTable(name="person_artistic_statements")
     */
    private $people;

    public function __construct() {
        parent::__construct();
        $this->people = new ArrayCollection();
    }

    public function __toString() {
        return $this->title;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return ArtisticStatement
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
     * Set artwork.
     *
     * @param Artwork $artwork
     *
     * @return ArtisticStatement
     */
    public function setArtwork(Artwork $artwork = null) {
        $this->artwork = $artwork;

        return $this;
    }

    /**
     * Get artwork.
     *
     * @return Artwork
     */
    public function getArtwork() {
        return $this->artwork;
    }

    /**
     * Add person.
     *
     * @param Person $person
     *
     * @return ArtisticStatement
     */
    public function addPerson(Person $person) {
        $this->people[] = $person;

        return $this;
    }

    /**
     * Remove person.
     *
     * @param Person $person
     */
    public function removePerson(Person $person) {
        $this->people->removeElement($person);
    }

    /**
     * Get people.
     *
     * @return Collection2
     */
    public function getPeople() {
        return $this->people;
    }
}
