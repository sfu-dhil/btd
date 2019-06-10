<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ProjectRole;
use AppBundle\Form\Project\ProjectRoleType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * ProjectRole controller.
 *
 * @Route("/project_role")
 */
class ProjectRoleController extends Controller {

    /**
     * Lists all ProjectRole entities.
     *
     * @Route("/", name="project_role_index", methods={"GET"})

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
     * Creates a new ProjectRole entity.
     *
     * @Route("/new", name="project_role_new", methods={"GET","POST"})
     * @IsGranted("ROLE_CONTENT_ADMIN")

     * @Template()
     * @param Request $request
     */
    public function newAction(Request $request) {

        $projectRole = new ProjectRole();
        $form = $this->createForm(ProjectRoleType::class, $projectRole);
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
     * @Route("/{id}", name="project_role_show", methods={"GET"})

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
     * @Route("/{id}/edit", name="project_role_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_CONTENT_ADMIN")

     * @Template()
     * @param Request $request
     * @param ProjectRole $projectRole
     */
    public function editAction(Request $request, ProjectRole $projectRole) {

        $editForm = $this->createForm(ProjectRoleType::class, $projectRole);
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
     * @Route("/{id}/delete", name="project_role_delete", methods={"GET"})
     * @IsGranted("ROLE_CONTENT_ADMIN")

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
