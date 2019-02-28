<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\ContentEntityInterface;
use Nines\UtilBundle\Entity\ContentExcerptTrait;

/**
 * ProjectPage
 *
 * @ORM\Table(name="project_page", indexes={
 *  @ORM\Index(columns={"title", "content"}, flags={"fulltext"}),
 * })
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjectPageRepository")
 */
class ProjectPage extends \Nines\UtilBundle\Entity\AbstractEntity implements ContentEntityInterface {

    use ContentExcerptTrait;

    /**
     * Blog post title.
     *
     * @var string
     * 
     * @ORM\Column(name="title", type="string", nullable=false)
     */
    private $title;

    /**
     * @var Project
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="projectPages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $project;
    
    public function __construct() {
        parent::__construct();
    }
    
    public function __toString() {
        return $this->title;
    }


    /**
     * Set title
     *
     * @param string $title
     *
     * @return ProjectPage
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set project
     *
     * @param \AppBundle\Entity\Project $project
     *
     * @return ProjectPage
     */
    public function setProject(\AppBundle\Entity\Project $project = null)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return \AppBundle\Entity\Project
     */
    public function getProject()
    {
        return $this->project;
    }
}
