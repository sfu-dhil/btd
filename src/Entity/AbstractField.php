<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Nines\DublinCoreBundle\Entity\Element;
use Nines\UtilBundle\Entity\AbstractEntity;

/**
 * Field.
 */
#[ORM\MappedSuperclass]
abstract class AbstractField extends AbstractEntity {
    #[ORM\Column(type: Types::TEXT)]
    private ?string $value = null;

    #[ORM\ManyToOne(targetEntity: Element::class, inversedBy: 'values')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Element $element = null;

    public function __toString() : string {
        return $this->value;
    }

    public function setValue(?string $value) : self {
        $this->value = $value;

        return $this;
    }

    public function getValue() : ?string {
        return $this->value;
    }

    public function setElement(?Element $element = null) : self {
        $this->element = $element;

        return $this;
    }

    public function getElement() : ?Element {
        return $this->element;
    }
}
