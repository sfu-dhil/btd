<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\ArtworkRole;
use AppBundle\Form\ArtworkRoleType;

/**
 * ArtworkRole controller.
 *
 * @Route("/artwork_role")
 */
class ArtworkRoleController extends Controller {

    /**
     * Lists all ArtworkRole entities.
     *
     * @Route("/", name="artwork_role_index")
     * @Method("GET")
     * @Template()
     * @param Request $request
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:ArtworkRole e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $artworkRoles = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'artworkRoles' => $artworkRoles,
        );
    }

    /**
     * Search for ArtworkRole entities.
     *
     * To make this work, add a method like this one to the 
     * AppBundle:ArtworkRole repository. Replace the fieldName with
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
     * @Route("/search", name="artwork_role_search")
     * @Method("GET")
     * @Template()
     * @param Request $request
     */
    public function searchAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:ArtworkRole');
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->searchQuery($q);
            $paginator = $this->get('knp_paginator');
            $artworkRoles = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $artworkRoles = array();
        }

        return array(
            'artworkRoles' => $artworkRoles,
            'q' => $q,
        );
    }

    /**
     * Full text search for ArtworkRole entities.
     *
     * To make this work, add a method like this one to the 
     * AppBundle:ArtworkRole repository. Replace the fieldName with
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
     * fulltext indexes on your ArtworkRole entity.
     *     ORM\Index(name="alias_name_idx",columns="name", flags={"fulltext"})
     *
     *
     * @Route("/fulltext", name="artwork_role_fulltext")
     * @Method("GET")
     * @Template()
     * @param Request $request
     * @return array
     */
    public function fulltextAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:ArtworkRole');
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->fulltextQuery($q);
            $paginator = $this->get('knp_paginator');
            $artworkRoles = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $artworkRoles = array();
        }

        return array(
            'artworkRoles' => $artworkRoles,
            'q' => $q,
        );
    }

    /**
     * Creates a new ArtworkRole entity.
     *
     * @Route("/new", name="artwork_role_new")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     */
    public function newAction(Request $request) {
        $artworkRole = new ArtworkRole();
        $form = $this->createForm('AppBundle\Form\ArtworkRoleType', $artworkRole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($artworkRole);
            $em->flush();

            $this->addFlash('success', 'The new artworkRole was created.');
            return $this->redirectToRoute('artwork_role_show', array('id' => $artworkRole->getId()));
        }

        return array(
            'artworkRole' => $artworkRole,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a ArtworkRole entity.
     *
     * @Route("/{id}", name="artwork_role_show")
     * @Method("GET")
     * @Template()
     * @param ArtworkRole $artworkRole
     */
    public function showAction(ArtworkRole $artworkRole) {

        return array(
            'artworkRole' => $artworkRole,
        );
    }

    /**
     * Displays a form to edit an existing ArtworkRole entity.
     *
     * @Route("/{id}/edit", name="artwork_role_edit")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     * @param ArtworkRole $artworkRole
     */
    public function editAction(Request $request, ArtworkRole $artworkRole) {
        $editForm = $this->createForm('AppBundle\Form\ArtworkRoleType', $artworkRole);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The artworkRole has been updated.');
            return $this->redirectToRoute('artwork_role_show', array('id' => $artworkRole->getId()));
        }

        return array(
            'artworkRole' => $artworkRole,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a ArtworkRole entity.
     *
     * @Route("/{id}/delete", name="artwork_role_delete")
     * @Method("GET")
     * @param Request $request
     * @param ArtworkRole $artworkRole
     */
    public function deleteAction(Request $request, ArtworkRole $artworkRole) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($artworkRole);
        $em->flush();
        $this->addFlash('success', 'The artworkRole was deleted.');

        return $this->redirectToRoute('artwork_role_index');
    }

}
