<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProjectPage
 *
 * @ORM\Table(name="project_page", indexes={
 *  @ORM\Index(columns={"title", "content"}, flags={"fulltext"}),
 * })
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjectPageRepository")
 */
class ProjectPage extends \Nines\UtilBundle\Entity\AbstractEntity
{
    /**
     * Blog post title.
     *
     * @var string
     * 
     * @ORM\Column(name="title", type="string", nullable=false)
     */
    private $title;

    /**
     * An excerpt, to display in lists.
     *
     * @var string
     * 
     * @ORM\Column(name="excerpt", type="text", nullable=true)
     */
    private $excerpt;
    
    /**
     * The content of the post, as HTML.
     *
     * @var string
     * 
     * @ORM\Column(name="content", type="text", nullable=false)
     */
    private $content;

    /**
     * @var ProjectType
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="projectPages")
     * @ORM\JoinColumn(name="projecttype_id")
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
     * Set excerpt
     *
     * @param string $excerpt
     *
     * @return ProjectPage
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
     * Set content
     *
     * @param string $content
     *
     * @return ProjectPage
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
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
