<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Location;
use AppBundle\Form\LocationType;

/**
 * Location controller.
 *
 * @Route("/location")
 */
class LocationController extends Controller {

    /**
     * Lists all Location entities.
     *
     * @Route("/", name="location_index")
     * @Method("GET")
     * @Template()
     * @param Request $request
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:Location e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $locations = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'locations' => $locations,
        );
    }

    /**
     * Search for Location entities.
     *
     * To make this work, add a method like this one to the 
     * AppBundle:Location repository. Replace the fieldName with
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
     * @Route("/search", name="location_search")
     * @Method("GET")
     * @Template()
     * @param Request $request
     */
    public function searchAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Location');
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->searchQuery($q);
            $paginator = $this->get('knp_paginator');
            $locations = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $locations = array();
        }

        return array(
            'locations' => $locations,
            'q' => $q,
        );
    }

    /**
     * Full text search for Location entities.
     *
     * To make this work, add a method like this one to the 
     * AppBundle:Location repository. Replace the fieldName with
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
     * fulltext indexes on your Location entity.
     *     ORM\Index(name="alias_name_idx",columns="name", flags={"fulltext"})
     *
     *
     * @Route("/fulltext", name="location_fulltext")
     * @Method("GET")
     * @Template()
     * @param Request $request
     * @return array
     */
    public function fulltextAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Location');
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->fulltextQuery($q);
            $paginator = $this->get('knp_paginator');
            $locations = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $locations = array();
        }

        return array(
            'locations' => $locations,
            'q' => $q,
        );
    }

    /**
     * Creates a new Location entity.
     *
     * @Route("/new", name="location_new")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     */
    public function newAction(Request $request) {
        $location = new Location();
        $form = $this->createForm('AppBundle\Form\LocationType', $location);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($location);
            $em->flush();

            $this->addFlash('success', 'The new location was created.');
            return $this->redirectToRoute('location_show', array('id' => $location->getId()));
        }

        return array(
            'location' => $location,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Location entity.
     *
     * @Route("/{id}", name="location_show")
     * @Method("GET")
     * @Template()
     * @param Location $location
     */
    public function showAction(Location $location) {

        return array(
            'location' => $location,
        );
    }

    /**
     * Displays a form to edit an existing Location entity.
     *
     * @Route("/{id}/edit", name="location_edit")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     * @param Location $location
     */
    public function editAction(Request $request, Location $location) {
        $editForm = $this->createForm('AppBundle\Form\LocationType', $location);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The location has been updated.');
            return $this->redirectToRoute('location_show', array('id' => $location->getId()));
        }

        return array(
            'location' => $location,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Location entity.
     *
     * @Route("/{id}/delete", name="location_delete")
     * @Method("GET")
     * @param Request $request
     * @param Location $location
     */
    public function deleteAction(Request $request, Location $location) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($location);
        $em->flush();
        $this->addFlash('success', 'The location was deleted.');

        return $this->redirectToRoute('location_index');
    }

}
