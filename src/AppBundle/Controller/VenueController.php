<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Venue;
use AppBundle\Form\VenueType;

/**
 * Venue controller.
 *
 * @Route("/venue")
 */
class VenueController extends Controller {

    /**
     * Lists all Venue entities.
     *
     * @Route("/", name="venue_index")
     * @Method("GET")
     * @Template()
     * @param Request $request
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:Venue e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $venues = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'venues' => $venues,
        );
    }

    /**
     * Search for Venue entities.
     *
     * To make this work, add a method like this one to the 
     * AppBundle:Venue repository. Replace the fieldName with
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
     * @Route("/search", name="venue_search")
     * @Method("GET")
     * @Template()
     * @param Request $request
     */
    public function searchAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Venue');
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->searchQuery($q);
            $paginator = $this->get('knp_paginator');
            $venues = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $venues = array();
        }

        return array(
            'venues' => $venues,
            'q' => $q,
        );
    }

    /**
     * Full text search for Venue entities.
     *
     * To make this work, add a method like this one to the 
     * AppBundle:Venue repository. Replace the fieldName with
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
     * fulltext indexes on your Venue entity.
     *     ORM\Index(name="alias_name_idx",columns="name", flags={"fulltext"})
     *
     *
     * @Route("/fulltext", name="venue_fulltext")
     * @Method("GET")
     * @Template()
     * @param Request $request
     * @return array
     */
    public function fulltextAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Venue');
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->fulltextQuery($q);
            $paginator = $this->get('knp_paginator');
            $venues = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $venues = array();
        }

        return array(
            'venues' => $venues,
            'q' => $q,
        );
    }

    /**
     * Creates a new Venue entity.
     *
     * @Route("/new", name="venue_new")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     */
    public function newAction(Request $request) {
        $venue = new Venue();
        $form = $this->createForm('AppBundle\Form\VenueType', $venue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($venue);
            $em->flush();

            $this->addFlash('success', 'The new venue was created.');
            return $this->redirectToRoute('venue_show', array('id' => $venue->getId()));
        }

        return array(
            'venue' => $venue,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Venue entity.
     *
     * @Route("/{id}", name="venue_show")
     * @Method("GET")
     * @Template()
     * @param Venue $venue
     */
    public function showAction(Venue $venue) {

        return array(
            'venue' => $venue,
        );
    }

    /**
     * Displays a form to edit an existing Venue entity.
     *
     * @Route("/{id}/edit", name="venue_edit")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     * @param Venue $venue
     */
    public function editAction(Request $request, Venue $venue) {
        $editForm = $this->createForm('AppBundle\Form\VenueType', $venue);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The venue has been updated.');
            return $this->redirectToRoute('venue_show', array('id' => $venue->getId()));
        }

        return array(
            'venue' => $venue,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Venue entity.
     *
     * @Route("/{id}/delete", name="venue_delete")
     * @Method("GET")
     * @param Request $request
     * @param Venue $venue
     */
    public function deleteAction(Request $request, Venue $venue) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($venue);
        $em->flush();
        $this->addFlash('success', 'The venue was deleted.');

        return $this->redirectToRoute('venue_index');
    }

}
