<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\ProjectType;
use AppBundle\Form\ProjectTypeType;

/**
 * ProjectType controller.
 *
 * @Route("/project_type")
 */
class ProjectTypeController extends Controller {

    /**
     * Lists all ProjectType entities.
     *
     * @Route("/", name="project_type_index")
     * @Method("GET")
     * @Template()
     * @param Request $request
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:ProjectType e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $projectTypes = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'projectTypes' => $projectTypes,
        );
    }

    /**
     * Creates a new ProjectType entity.
     *
     * @Route("/new", name="project_type_new")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     */
    public function newAction(Request $request) {
        $projectType = new ProjectType();
        $form = $this->createForm('AppBundle\Form\ProjectTypeType', $projectType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($projectType);
            $em->flush();

            $this->addFlash('success', 'The new projectType was created.');
            return $this->redirectToRoute('project_type_show', array('id' => $projectType->getId()));
        }

        return array(
            'projectType' => $projectType,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a ProjectType entity.
     *
     * @Route("/{id}", name="project_type_show")
     * @Method("GET")
     * @Template()
     * @param ProjectType $projectType
     */
    public function showAction(ProjectType $projectType) {

        return array(
            'projectType' => $projectType,
        );
    }

    /**
     * Displays a form to edit an existing ProjectType entity.
     *
     * @Route("/{id}/edit", name="project_type_edit")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     * @param ProjectType $projectType
     */
    public function editAction(Request $request, ProjectType $projectType) {
        $editForm = $this->createForm('AppBundle\Form\ProjectTypeType', $projectType);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The projectType has been updated.');
            return $this->redirectToRoute('project_type_show', array('id' => $projectType->getId()));
        }

        return array(
            'projectType' => $projectType,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a ProjectType entity.
     *
     * @Route("/{id}/delete", name="project_type_delete")
     * @Method("GET")
     * @param Request $request
     * @param ProjectType $projectType
     */
    public function deleteAction(Request $request, ProjectType $projectType) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($projectType);
        $em->flush();
        $this->addFlash('success', 'The projectType was deleted.');

        return $this->redirectToRoute('project_type_index');
    }

}
