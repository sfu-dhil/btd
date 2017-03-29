<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\ArtworkContribution;
use AppBundle\Form\ArtworkContributionType;

/**
 * ArtworkContribution controller.
 *
 * @Route("/artwork_contribution")
 */
class ArtworkContributionController extends Controller {

    /**
     * Lists all ArtworkContribution entities.
     *
     * @Route("/", name="artwork_contribution_index")
     * @Method("GET")
     * @Template()
     * @param Request $request
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:ArtworkContribution e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $artworkContributions = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'artworkContributions' => $artworkContributions,
        );
    }

    /**
     * Creates a new ArtworkContribution entity.
     *
     * @Route("/new", name="artwork_contribution_new")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     */
    public function newAction(Request $request) {
        $artworkContribution = new ArtworkContribution();
        $form = $this->createForm('AppBundle\Form\ArtworkContributionType', $artworkContribution);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($artworkContribution);
            $em->flush();

            $this->addFlash('success', 'The new artworkContribution was created.');
            return $this->redirectToRoute('artwork_contribution_show', array('id' => $artworkContribution->getId()));
        }

        return array(
            'artworkContribution' => $artworkContribution,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a ArtworkContribution entity.
     *
     * @Route("/{id}", name="artwork_contribution_show")
     * @Method("GET")
     * @Template()
     * @param ArtworkContribution $artworkContribution
     */
    public function showAction(ArtworkContribution $artworkContribution) {

        return array(
            'artworkContribution' => $artworkContribution,
        );
    }

    /**
     * Displays a form to edit an existing ArtworkContribution entity.
     *
     * @Route("/{id}/edit", name="artwork_contribution_edit")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     * @param ArtworkContribution $artworkContribution
     */
    public function editAction(Request $request, ArtworkContribution $artworkContribution) {
        $editForm = $this->createForm('AppBundle\Form\ArtworkContributionType', $artworkContribution);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The artworkContribution has been updated.');
            return $this->redirectToRoute('artwork_contribution_show', array('id' => $artworkContribution->getId()));
        }

        return array(
            'artworkContribution' => $artworkContribution,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a ArtworkContribution entity.
     *
     * @Route("/{id}/delete", name="artwork_contribution_delete")
     * @Method("GET")
     * @param Request $request
     * @param ArtworkContribution $artworkContribution
     */
    public function deleteAction(Request $request, ArtworkContribution $artworkContribution) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($artworkContribution);
        $em->flush();
        $this->addFlash('success', 'The artworkContribution was deleted.');

        return $this->redirectToRoute('artwork_contribution_index');
    }

}
