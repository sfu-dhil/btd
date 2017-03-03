<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\VenueType;
use AppBundle\Form\VenueTypeType;

/**
 * VenueType controller.
 *
 * @Route("/venue_type")
 */
class VenueTypeController extends Controller
{
    /**
     * Lists all VenueType entities.
     *
     * @Route("/", name="venue_type_index")
     * @Method("GET")
     * @Template()
	 * @param Request $request
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:VenueType e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $venueTypes = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'venueTypes' => $venueTypes,
        );
    }
    /**
     * Search for VenueType entities.
	 *
	 * To make this work, add a method like this one to the 
	 * AppBundle:VenueType repository. Replace the fieldName with
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
     * @Route("/search", name="venue_type_search")
     * @Method("GET")
     * @Template()
	 * @param Request $request
     */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('AppBundle:VenueType');
		$q = $request->query->get('q');
		if($q) {
	        $query = $repo->searchQuery($q);
			$paginator = $this->get('knp_paginator');
			$venueTypes = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
		} else {
			$venueTypes = array();
		}

        return array(
            'venueTypes' => $venueTypes,
			'q' => $q,
        );
    }
    /**
     * Full text search for VenueType entities.
	 *
	 * To make this work, add a method like this one to the 
	 * AppBundle:VenueType repository. Replace the fieldName with
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
	 * fulltext indexes on your VenueType entity.
	 *     ORM\Index(name="alias_name_idx",columns="name", flags={"fulltext"})
	 *
     *
     * @Route("/fulltext", name="venue_type_fulltext")
     * @Method("GET")
     * @Template()
	 * @param Request $request
	 * @return array
     */
    public function fulltextAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('AppBundle:VenueType');
		$q = $request->query->get('q');
		if($q) {
	        $query = $repo->fulltextQuery($q);
			$paginator = $this->get('knp_paginator');
			$venueTypes = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
		} else {
			$venueTypes = array();
		}

        return array(
            'venueTypes' => $venueTypes,
			'q' => $q,
        );
    }

    /**
     * Creates a new VenueType entity.
     *
     * @Route("/new", name="venue_type_new")
     * @Method({"GET", "POST"})
     * @Template()
	 * @param Request $request
     */
    public function newAction(Request $request)
    {
        $venueType = new VenueType();
        $form = $this->createForm('AppBundle\Form\VenueTypeType', $venueType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($venueType);
            $em->flush();

            $this->addFlash('success', 'The new venueType was created.');
            return $this->redirectToRoute('venue_type_show', array('id' => $venueType->getId()));
        }

        return array(
            'venueType' => $venueType,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a VenueType entity.
     *
     * @Route("/{id}", name="venue_type_show")
     * @Method("GET")
     * @Template()
	 * @param VenueType $venueType
     */
    public function showAction(VenueType $venueType)
    {

        return array(
            'venueType' => $venueType,
        );
    }

    /**
     * Displays a form to edit an existing VenueType entity.
     *
     * @Route("/{id}/edit", name="venue_type_edit")
     * @Method({"GET", "POST"})
     * @Template()
	 * @param Request $request
	 * @param VenueType $venueType
     */
    public function editAction(Request $request, VenueType $venueType)
    {
        $editForm = $this->createForm('AppBundle\Form\VenueTypeType', $venueType);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The venueType has been updated.');
            return $this->redirectToRoute('venue_type_show', array('id' => $venueType->getId()));
        }

        return array(
            'venueType' => $venueType,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a VenueType entity.
     *
     * @Route("/{id}/delete", name="venue_type_delete")
     * @Method("GET")
	 * @param Request $request
	 * @param VenueType $venueType
     */
    public function deleteAction(Request $request, VenueType $venueType)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($venueType);
        $em->flush();
        $this->addFlash('success', 'The venueType was deleted.');

        return $this->redirectToRoute('venue_type_index');
    }
}
