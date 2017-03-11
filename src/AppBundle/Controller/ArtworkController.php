<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Artwork;
use AppBundle\Form\ArtworkType;

/**
 * Artwork controller.
 *
 * @Route("/artwork")
 */
class ArtworkController extends Controller {

    /**
     * Lists all Artwork entities.
     *
     * @Route("/", name="artwork_index")
     * @Method("GET")
     * @Template()
     * @param Request $request
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:Artwork e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $artworks = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'artworks' => $artworks,
        );
    }

    /**
     * Search for Artwork entities.
     *
     * To make this work, add a method like this one to the 
     * AppBundle:Artwork repository. Replace the fieldName with
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
     * @Route("/search", name="artwork_search")
     * @Method("GET")
     * @Template()
     * @param Request $request
     */
    public function searchAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Artwork');
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->searchQuery($q);
            $paginator = $this->get('knp_paginator');
            $artworks = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $artworks = array();
        }

        return array(
            'artworks' => $artworks,
            'q' => $q,
        );
    }

    /**
     * Full text search for Artwork entities.
     *
     * To make this work, add a method like this one to the 
     * AppBundle:Artwork repository. Replace the fieldName with
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
     * fulltext indexes on your Artwork entity.
     *     ORM\Index(name="alias_name_idx",columns="name", flags={"fulltext"})
     *
     *
     * @Route("/fulltext", name="artwork_fulltext")
     * @Method("GET")
     * @Template()
     * @param Request $request
     * @return array
     */
    public function fulltextAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Artwork');
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->fulltextQuery($q);
            $paginator = $this->get('knp_paginator');
            $artworks = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $artworks = array();
        }

        return array(
            'artworks' => $artworks,
            'q' => $q,
        );
    }

    /**
     * Creates a new Artwork entity.
     *
     * @Route("/new", name="artwork_new")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     */
    public function newAction(Request $request) {
        $artwork = new Artwork();
        $form = $this->createForm('AppBundle\Form\ArtworkType', $artwork);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($artwork);
            $em->flush();

            $this->addFlash('success', 'The new artwork was created.');
            return $this->redirectToRoute('artwork_show', array('id' => $artwork->getId()));
        }

        return array(
            'artwork' => $artwork,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Artwork entity.
     *
     * @Route("/{id}", name="artwork_show")
     * @Method("GET")
     * @Template()
     * @param Artwork $artwork
     */
    public function showAction(Artwork $artwork) {

        return array(
            'artwork' => $artwork,
        );
    }

    /**
     * Displays a form to edit an existing Artwork entity.
     *
     * @Route("/{id}/edit", name="artwork_edit")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     * @param Artwork $artwork
     */
    public function editAction(Request $request, Artwork $artwork) {
        $editForm = $this->createForm('AppBundle\Form\ArtworkType', $artwork);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The artwork has been updated.');
            return $this->redirectToRoute('artwork_show', array('id' => $artwork->getId()));
        }

        return array(
            'artwork' => $artwork,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Artwork entity.
     *
     * @Route("/{id}/delete", name="artwork_delete")
     * @Method("GET")
     * @param Request $request
     * @param Artwork $artwork
     */
    public function deleteAction(Request $request, Artwork $artwork) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($artwork);
        $em->flush();
        $this->addFlash('success', 'The artwork was deleted.');

        return $this->redirectToRoute('artwork_index');
    }

}
