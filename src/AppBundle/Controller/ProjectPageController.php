<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectPage;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * ProjectPage controller.
 */
class ProjectPageController extends Controller {

    /**
     * Lists all ProjectPage entities.
     *
     * @Route("/project/{projectId}/page", name="project_page_index")
     * @ParamConverter("project", class="AppBundle:Project", options={"id": "projectId"})
     * @Method("GET")
     * @Template()
     * @param Request $request
     * @param Project $project
     */
    public function indexAction(Request $request, $project) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:ProjectPage e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $projectPages = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'project' => $project,
            'projectPages' => $projectPages,
        );
    }

    /**
     * Search for ProjectPage entities.
     *
     * To make this work, add a method like this one to the 
     * AppBundle:ProjectPage repository. Replace the fieldName with
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
     * @Route("/search", name="project_page_search")
     * @Method("GET")
     * @Template()
     * @param Request $request
     */
    public function searchAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:ProjectPage');
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->searchQuery($q);
            $paginator = $this->get('knp_paginator');
            $projectPages = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $projectPages = array();
        }

        return array(
            'projectPages' => $projectPages,
            'q' => $q,
        );
    }

    /**
     * Full text search for ProjectPage entities.
     *
     * To make this work, add a method like this one to the 
     * AppBundle:ProjectPage repository. Replace the fieldName with
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
     * fulltext indexes on your ProjectPage entity.
     *     ORM\Index(name="alias_name_idx",columns="name", flags={"fulltext"})
     *
     *
     * @Route("/fulltext", name="project_page_fulltext")
     * @Method("GET")
     * @Template()
     * @param Request $request
     * @return array
     */
    public function fulltextAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:ProjectPage');
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->fulltextQuery($q);
            $paginator = $this->get('knp_paginator');
            $projectPages = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $projectPages = array();
        }

        return array(
            'projectPages' => $projectPages,
            'q' => $q,
        );
    }

    /**
     * Creates a new ProjectPage entity.
     *
     * @Route("/project/{projectId}/page/new", name="project_page_new")
     * @ParamConverter("project", class="AppBundle:Project", options={"id": "projectId"})
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     * @param Project $project
     */
    public function newAction(Request $request, Project $project) {
        $projectPage = new ProjectPage();
        $projectPage->setProject($project);
        $form = $this->createForm('AppBundle\Form\ProjectPageType', $projectPage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $text = $this->get('nines.util.text');
            if (!$projectPage->getExcerpt()) {
                $projectPage->setExcerpt($text->trim($projectPage->getContent(), $this->getParameter('nines_blog.excerpt_length')));
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($projectPage);
            $em->flush();

            $this->addFlash('success', 'The new projectPage was created.');
            return $this->redirectToRoute('project_page_show', array('projectId' => $project->getId(), 'id' => $projectPage->getId()));
        }

        return array(
            'project' => $project,
            'projectPage' => $projectPage,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a ProjectPage entity.
     *
     * @Route("/project/{projectId}/page/{id}", name="project_page_show")
     * @ParamConverter("project", class="AppBundle:Project", options={"id": "projectId"})
     * @Method("GET")
     * @Template()
     * @param Project $project
     * @param ProjectPage $projectPage
     */
    public function showAction(Project $project, ProjectPage $projectPage) {
        if($project->getId() !== $projectPage->getProject()->getId()) {
            throw new NotFoundHttpException();
        }
        return array(
            'project' => $project,
            'projectPage' => $projectPage,
        );
    }

    /**
     * Displays a form to edit an existing ProjectPage entity.
     *
     * @Route("/project/{projectId}/page/{id}/edit", name="project_page_edit")
     * @ParamConverter("project", class="AppBundle:Project", options={"id": "projectId"})
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     * @param ProjectPage $projectPage
     */
    public function editAction(Request $request, Project $project, ProjectPage $projectPage) {
        if($project->getId() !== $projectPage->getProject()->getId()) {
            throw new NotFoundHttpException();
        }
        $editForm = $this->createForm('AppBundle\Form\ProjectPageType', $projectPage);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $text = $this->get('nines.util.text');
            if (!$projectPage->getExcerpt()) {
                $projectPage->setExcerpt($text->trim($projectPage->getContent(), $this->getParameter('nines_blog.excerpt_length')));
            }
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The projectPage has been updated.');
            return $this->redirectToRoute('project_page_show', array(
                'projectId' => $project->getId(),
                'id' => $projectPage->getId()
            ));
        }

        return array(
            'project' => $project,
            'projectPage' => $projectPage,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a ProjectPage entity.
     *
     * @Route("/project/{projectId}/page/{id}/delete", name="project_page_delete")
     * @ParamConverter("project", class="AppBundle:Project", options={"id": "projectId"})
     * @Method("GET")
     * @param Request $request
     * @param ProjectPage $projectPage
     */
    public function deleteAction(Request $request, Project $project, ProjectPage $projectPage) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($projectPage);
        $em->flush();
        $this->addFlash('success', 'The projectPage was deleted.');

        return $this->redirectToRoute('project_page_index', array(
            'projectId' => $project->getId(),
        ));
    }

}
