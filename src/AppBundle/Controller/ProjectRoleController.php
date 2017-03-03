<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\ProjectRole;
use AppBundle\Form\ProjectRoleType;

/**
 * ProjectRole controller.
 *
 * @Route("/project_role")
 */
class ProjectRoleController extends Controller {

    /**
     * Lists all ProjectRole entities.
     *
     * @Route("/", name="project_role_index")
     * @Method("GET")
     * @Template()
     * @param Request $request
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:ProjectRole e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $projectRoles = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'projectRoles' => $projectRoles,
        );
    }

    /**
     * Search for ProjectRole entities.
     *
     * To make this work, add a method like this one to the 
     * AppBundle:ProjectRole repository. Replace the fieldName with
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
     * @Route("/search", name="project_role_search")
     * @Method("GET")
     * @Template()
     * @param Request $request
     */
    public function searchAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:ProjectRole');
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->searchQuery($q);
            $paginator = $this->get('knp_paginator');
            $projectRoles = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $projectRoles = array();
        }

        return array(
            'projectRoles' => $projectRoles,
            'q' => $q,
        );
    }

    /**
     * Full text search for ProjectRole entities.
     *
     * To make this work, add a method like this one to the 
     * AppBundle:ProjectRole repository. Replace the fieldName with
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
     * fulltext indexes on your ProjectRole entity.
     *     ORM\Index(name="alias_name_idx",columns="name", flags={"fulltext"})
     *
     *
     * @Route("/fulltext", name="project_role_fulltext")
     * @Method("GET")
     * @Template()
     * @param Request $request
     * @return array
     */
    public function fulltextAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:ProjectRole');
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->fulltextQuery($q);
            $paginator = $this->get('knp_paginator');
            $projectRoles = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $projectRoles = array();
        }

        return array(
            'projectRoles' => $projectRoles,
            'q' => $q,
        );
    }

    /**
     * Creates a new ProjectRole entity.
     *
     * @Route("/new", name="project_role_new")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     */
    public function newAction(Request $request) {
        $projectRole = new ProjectRole();
        $form = $this->createForm('AppBundle\Form\ProjectRoleType', $projectRole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($projectRole);
            $em->flush();

            $this->addFlash('success', 'The new projectRole was created.');
            return $this->redirectToRoute('project_role_show', array('id' => $projectRole->getId()));
        }

        return array(
            'projectRole' => $projectRole,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a ProjectRole entity.
     *
     * @Route("/{id}", name="project_role_show")
     * @Method("GET")
     * @Template()
     * @param ProjectRole $projectRole
     */
    public function showAction(ProjectRole $projectRole) {

        return array(
            'projectRole' => $projectRole,
        );
    }

    /**
     * Displays a form to edit an existing ProjectRole entity.
     *
     * @Route("/{id}/edit", name="project_role_edit")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     * @param ProjectRole $projectRole
     */
    public function editAction(Request $request, ProjectRole $projectRole) {
        $editForm = $this->createForm('AppBundle\Form\ProjectRoleType', $projectRole);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The projectRole has been updated.');
            return $this->redirectToRoute('project_role_show', array('id' => $projectRole->getId()));
        }

        return array(
            'projectRole' => $projectRole,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a ProjectRole entity.
     *
     * @Route("/{id}/delete", name="project_role_delete")
     * @Method("GET")
     * @param Request $request
     * @param ProjectRole $projectRole
     */
    public function deleteAction(Request $request, ProjectRole $projectRole) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($projectRole);
        $em->flush();
        $this->addFlash('success', 'The projectRole was deleted.');

        return $this->redirectToRoute('project_role_index');
    }

}
