<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\ProjectContribution;
use AppBundle\Form\ProjectContributionType;

/**
 * ProjectContribution controller.
 *
 * @Route("/project_contribution")
 */
class ProjectContributionController extends Controller {

    /**
     * Lists all ProjectContribution entities.
     *
     * @Route("/", name="project_contribution_index")
     * @Method("GET")
     * @Template()
     * @param Request $request
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:ProjectContribution e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $projectContributions = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'projectContributions' => $projectContributions,
        );
    }

    /**
     * Creates a new ProjectContribution entity.
     *
     * @Route("/new", name="project_contribution_new")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     */
    public function newAction(Request $request) {
        $projectContribution = new ProjectContribution();
        $form = $this->createForm('AppBundle\Form\ProjectContributionType', $projectContribution);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($projectContribution);
            $em->flush();

            $this->addFlash('success', 'The new projectContribution was created.');
            return $this->redirectToRoute('project_contribution_show', array('id' => $projectContribution->getId()));
        }

        return array(
            'projectContribution' => $projectContribution,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a ProjectContribution entity.
     *
     * @Route("/{id}", name="project_contribution_show")
     * @Method("GET")
     * @Template()
     * @param ProjectContribution $projectContribution
     */
    public function showAction(ProjectContribution $projectContribution) {

        return array(
            'projectContribution' => $projectContribution,
        );
    }

    /**
     * Displays a form to edit an existing ProjectContribution entity.
     *
     * @Route("/{id}/edit", name="project_contribution_edit")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     * @param ProjectContribution $projectContribution
     */
    public function editAction(Request $request, ProjectContribution $projectContribution) {
        $editForm = $this->createForm('AppBundle\Form\ProjectContributionType', $projectContribution);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The projectContribution has been updated.');
            return $this->redirectToRoute('project_contribution_show', array('id' => $projectContribution->getId()));
        }

        return array(
            'projectContribution' => $projectContribution,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a ProjectContribution entity.
     *
     * @Route("/{id}/delete", name="project_contribution_delete")
     * @Method("GET")
     * @param Request $request
     * @param ProjectContribution $projectContribution
     */
    public function deleteAction(Request $request, ProjectContribution $projectContribution) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($projectContribution);
        $em->flush();
        $this->addFlash('success', 'The projectContribution was deleted.');

        return $this->redirectToRoute('project_contribution_index');
    }

}
