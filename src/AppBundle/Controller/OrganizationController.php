<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Organization;
use AppBundle\Form\Organization\ArtworkContributionsType;
use AppBundle\Form\Organization\OrganizationType;
use AppBundle\Form\Organization\ProjectContributionsType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Organization controller.
 *
 * @Route("/organization")
 */
class OrganizationController extends Controller {

    /**
     * Lists all Organization entities.
     *
     * @Route("/", name="organization_index", methods={"GET"})

     * @Template()
     * @param Request $request
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:Organization e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $organizations = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'organizations' => $organizations,
        );
    }

    /**
     * Full text search for Organization entities.
     *
     * @Route("/search", name="organization_search", methods={"GET"})

     * @Template()
     * @param Request $request
     * @return array
     */
    public function searchAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Organization');
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->fulltextQuery($q);
            $paginator = $this->get('knp_paginator');
            $organizations = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $organizations = array();
        }

        return array(
            'organizations' => $organizations,
            'q' => $q,
        );
    }

    /**
     * Creates a new Organization entity.
     *
     * @Route("/new", name="organization_new", methods={"GET","POST"})
     * @IsGranted("ROLE_CONTENT_ADMIN")

     * @Template()
     * @param Request $request
     */
    public function newAction(Request $request) {

        $organization = new Organization();
        $form = $this->createForm(OrganizationType::class, $organization);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($organization);
            $em->flush();

            $this->addFlash('success', 'The new organization was created.');
            return $this->redirectToRoute('organization_show', array('id' => $organization->getId()));
        }

        return array(
            'organization' => $organization,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Organization entity.
     *
     * @Route("/{id}", name="organization_show", methods={"GET"})

     * @Template()
     * @param Organization $organization
     */
    public function showAction(Organization $organization) {

        return array(
            'organization' => $organization,
        );
    }

    /**
     * Displays a form to edit an existing Organization entity.
     *
     * @Route("/{id}/edit", name="organization_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_CONTENT_ADMIN")

     * @Template()
     * @param Request $request
     * @param Organization $organization
     */
    public function editAction(Request $request, Organization $organization) {

        $editForm = $this->createForm(OrganizationType::class, $organization);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The organization has been updated.');
            return $this->redirectToRoute('organization_show', array('id' => $organization->getId()));
        }

        return array(
            'organization' => $organization,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Organization entity.
     *
     * @Route("/{id}/delete", name="organization_delete", methods={"GET"})
     * @IsGranted("ROLE_CONTENT_ADMIN")

     * @param Request $request
     * @param Organization $organization
     */
    public function deleteAction(Request $request, Organization $organization) {

        $em = $this->getDoctrine()->getManager();
        $em->remove($organization);
        $em->flush();
        $this->addFlash('success', 'The organization was deleted.');

        return $this->redirectToRoute('organization_index');
    }

    /**
     * @Route("/{id}/project_contributions", name="organization_project_contributions", methods={"GET","POST"})
     * @IsGranted("ROLE_CONTENT_ADMIN")

     * @Template()
     * 
     * @param Request $request
     * @param Organization $organization
     */
    public function projectContributionsAction(Request $request, Organization $organization) {

        $form = $this->createForm(ProjectContributionsType::class, $organization, array(
            'organization' => $organization,
        ));
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The contributions have been updated.');
            return $this->redirectToRoute('organization_show', array('id' => $organization->getId()));
        }
        
        return array(
            'organization' => $organization,
            'edit_form' => $form->createView(),
        );
    }

    /**
     * @Route("/{id}/artwork_contributions", name="organization_artwork_contributions", methods={"GET","POST"})
     * @IsGranted("ROLE_CONTENT_ADMIN")

     * @Template()
     * 
     * @param Request $request
     * @param Organization $organization
     */
    public function artworkContributionsAction(Request $request, Organization $organization) {

        $form = $this->createForm(ArtworkContributionsType::class, $organization, array(
            'organization' => $organization,
        ));
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The contributions have been updated.');
            return $this->redirectToRoute('organization_show', array('id' => $organization->getId()));
        }
        
        return array(
            'organization' => $organization,
            'edit_form' => $form->createView(),
        );
    }
}
