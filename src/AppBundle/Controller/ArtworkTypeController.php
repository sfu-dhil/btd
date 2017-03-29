<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\ArtworkType;
use AppBundle\Form\ArtworkTypeType;

/**
 * ArtworkType controller.
 *
 * @Route("/artwork_type")
 */
class ArtworkTypeController extends Controller
{
    /**
     * Lists all ArtworkType entities.
     *
     * @Route("/", name="artwork_type_index")
     * @Method("GET")
     * @Template()
	 * @param Request $request
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:ArtworkType e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $artworkTypes = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'artworkTypes' => $artworkTypes,
        );
    }
    /**
     * Search for ArtworkType entities.
	 *
	 * To make this work, add a method like this one to the 
	 * AppBundle:ArtworkType repository. Replace the fieldName with
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
     * @Route("/search", name="artwork_type_search")
     * @Method("GET")
     * @Template()
	 * @param Request $request
     */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('AppBundle:ArtworkType');
		$q = $request->query->get('q');
		if($q) {
	        $query = $repo->searchQuery($q);
			$paginator = $this->get('knp_paginator');
			$artworkTypes = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
		} else {
			$artworkTypes = array();
		}

        return array(
            'artworkTypes' => $artworkTypes,
			'q' => $q,
        );
    }
    /**
     * Full text search for ArtworkType entities.
	 *
	 * To make this work, add a method like this one to the 
	 * AppBundle:ArtworkType repository. Replace the fieldName with
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
	 * fulltext indexes on your ArtworkType entity.
	 *     ORM\Index(name="alias_name_idx",columns="name", flags={"fulltext"})
	 *
     *
     * @Route("/fulltext", name="artwork_type_fulltext")
     * @Method("GET")
     * @Template()
	 * @param Request $request
	 * @return array
     */
    public function fulltextAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('AppBundle:ArtworkType');
		$q = $request->query->get('q');
		if($q) {
	        $query = $repo->fulltextQuery($q);
			$paginator = $this->get('knp_paginator');
			$artworkTypes = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
		} else {
			$artworkTypes = array();
		}

        return array(
            'artworkTypes' => $artworkTypes,
			'q' => $q,
        );
    }

    /**
     * Creates a new ArtworkType entity.
     *
     * @Route("/new", name="artwork_type_new")
     * @Method({"GET", "POST"})
     * @Template()
	 * @param Request $request
     */
    public function newAction(Request $request)
    {
        $artworkType = new ArtworkType();
        $form = $this->createForm('AppBundle\Form\ArtworkTypeType', $artworkType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($artworkType);
            $em->flush();

            $this->addFlash('success', 'The new artworkType was created.');
            return $this->redirectToRoute('artwork_type_show', array('id' => $artworkType->getId()));
        }

        return array(
            'artworkType' => $artworkType,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a ArtworkType entity.
     *
     * @Route("/{id}", name="artwork_type_show")
     * @Method("GET")
     * @Template()
	 * @param ArtworkType $artworkType
     */
    public function showAction(ArtworkType $artworkType)
    {

        return array(
            'artworkType' => $artworkType,
        );
    }

    /**
     * Displays a form to edit an existing ArtworkType entity.
     *
     * @Route("/{id}/edit", name="artwork_type_edit")
     * @Method({"GET", "POST"})
     * @Template()
	 * @param Request $request
	 * @param ArtworkType $artworkType
     */
    public function editAction(Request $request, ArtworkType $artworkType)
    {
        $editForm = $this->createForm('AppBundle\Form\ArtworkTypeType', $artworkType);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The artworkType has been updated.');
            return $this->redirectToRoute('artwork_type_show', array('id' => $artworkType->getId()));
        }

        return array(
            'artworkType' => $artworkType,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a ArtworkType entity.
     *
     * @Route("/{id}/delete", name="artwork_type_delete")
     * @Method("GET")
	 * @param Request $request
	 * @param ArtworkType $artworkType
     */
    public function deleteAction(Request $request, ArtworkType $artworkType)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($artworkType);
        $em->flush();
        $this->addFlash('success', 'The artworkType was deleted.');

        return $this->redirectToRoute('artwork_type_index');
    }
}
