<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProjectPageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;
use Nines\UtilBundle\Entity\ContentEntityInterface;
use Nines\UtilBundle\Entity\ContentExcerptTrait;

#[ORM\Entity(repositoryClass: ProjectPageRepository::class)]
#[ORM\Table(name: 'project_page')]
#[ORM\Index(columns: ['title', 'content'], flags: ['fulltext'])]
class ProjectPage extends AbstractEntity implements ContentEntityInterface {
    use ContentExcerptTrait;

    /**
     * Blog post title.
     */
    #[ORM\Column(name: 'title', type: Types::STRING, nullable: false)]
    private ?string $title = null;

    #[ORM\ManyToOne(targetEntity: Project::class, inversedBy: 'projectPages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Project $project = null;

    public function __construct() {
        parent::__construct();
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

    public function setProject(?Project $project = null) : self {
        $this->project = $project;

        return $this;
    }

    public function getProject() : ?Project {
        return $this->project;
    }
}
