<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Project;
use AppBundle\Form\ProjectType;

/**
 * Project controller.
 *
 * @Route("/project")
 */
class ProjectController extends Controller {

    /**
     * Lists all Project entities.
     *
     * @Route("/", name="project_index")
     * @Method("GET")
     * @Template()
     * @param Request $request
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:Project e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $projects = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'projects' => $projects,
        );
    }

    /**
     * Search for Project entities.
     *
     * To make this work, add a method like this one to the 
     * AppBundle:Project repository. Replace the fieldName with
     * something appropriate, and adjust the generated search.html.twig
     * template.
     * 
      //    public function searchQuery($q) {
      //        $qb = $this->createQueryBuilder('e');
      //        $qb->where("e.fieldName like '%$q%'");
      //        return $qb->getQuery();
      //    }
     *
     *
     * @Route("/search", name="project_search")
     * @Method("GET")
     * @Template()
     * @param Request $request
     */
    public function searchAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Project');
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->searchQuery($q);
            $paginator = $this->get('knp_paginator');
            $projects = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $projects = array();
        }

        return array(
            'projects' => $projects,
            'q' => $q,
        );
    }

    /**
     * Full text search for Project entities.
     *
     * To make this work, add a method like this one to the 
     * AppBundle:Project repository. Replace the fieldName with
     * something appropriate, and adjust the generated fulltext.html.twig
     * template.
     * 
      //    public function fulltextQuery($q) {
      //        $qb = $this->createQueryBuilder('e');
      //        $qb->addSelect("MATCH_AGAINST (e.name, :q 'IN BOOLEAN MODE') as score");
      //        $qb->add('where', "MATCH_AGAINST (e.name, :q 'IN BOOLEAN MODE') > 0.5");
      //        $qb->orderBy('score', 'desc');
      //        $qb->setParameter('q', $q);
      //        return $qb->getQuery();
      //    }
     * 
     * Requires a MatchAgainst function be added to doctrine, and appropriate
     * fulltext indexes on your Project entity.
     *     ORM\Index(name="alias_name_idx",columns="name", flags={"fulltext"})
     *
     *
     * @Route("/fulltext", name="project_fulltext")
     * @Method("GET")
     * @Template()
     * @param Request $request
     * @return array
     */
    public function fulltextAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Project');
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->fulltextQuery($q);
            $paginator = $this->get('knp_paginator');
            $projects = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $projects = array();
        }

        return array(
            'projects' => $projects,
            'q' => $q,
        );
    }

    /**
     * Creates a new Project entity.
     *
     * @Route("/new", name="project_new")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     */
    public function newAction(Request $request) {
        $project = new Project();
        $form = $this->createForm('AppBundle\Form\ProjectType', $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $text = $this->get('nines.util.text');
            if( ! $project->getExcerpt()) {
                $project->setExcerpt($text->trim($project->getDescription(), $this->getParameter('nines_blog.excerpt_length')));
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();

            $this->addFlash('success', 'The new project was created.');
            return $this->redirectToRoute('project_show', array('id' => $project->getId()));
        }

        return array(
            'project' => $project,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Project entity.
     *
     * @Route("/{id}", name="project_show")
     * @Method("GET")
     * @Template()
     * @param Project $project
     */
    public function showAction(Project $project) {

        return array(
            'project' => $project,
        );
    }

    /**
     * Displays a form to edit an existing Project entity.
     *
     * @Route("/{id}/edit", name="project_edit")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     * @param Project $project
     */
    public function editAction(Request $request, Project $project) {
        $editForm = $this->createForm('AppBundle\Form\ProjectType', $project);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $text = $this->get('nines.util.text');
            if( ! $project->getExcerpt()) {
                $project->setExcerpt($text->trim($project->getDescription(), $this->getParameter('nines_blog.excerpt_length')));
            }
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The project has been updated.');
            return $this->redirectToRoute('project_show', array('id' => $project->getId()));
        }

        return array(
            'project' => $project,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Project entity.
     *
     * @Route("/{id}/delete", name="project_delete")
     * @Method("GET")
     * @param Request $request
     * @param Project $project
     */
    public function deleteAction(Request $request, Project $project) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($project);
        $em->flush();
        $this->addFlash('success', 'The project was deleted.');

        return $this->redirectToRoute('project_index');
    }

}
