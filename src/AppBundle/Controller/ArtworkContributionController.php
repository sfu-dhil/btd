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
     * Search for ArtworkContribution entities.
     *
     * To make this work, add a method like this one to the 
     * AppBundle:ArtworkContribution repository. Replace the fieldName with
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
     * @Route("/search", name="artwork_contribution_search")
     * @Method("GET")
     * @Template()
     * @param Request $request
     */
    public function searchAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:ArtworkContribution');
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->searchQuery($q);
            $paginator = $this->get('knp_paginator');
            $artworkContributions = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $artworkContributions = array();
        }

        return array(
            'artworkContributions' => $artworkContributions,
            'q' => $q,
        );
    }

    /**
     * Full text search for ArtworkContribution entities.
     *
     * To make this work, add a method like this one to the 
     * AppBundle:ArtworkContribution repository. Replace the fieldName with
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
     * fulltext indexes on your ArtworkContribution entity.
     *     ORM\Index(name="alias_name_idx",columns="name", flags={"fulltext"})
     *
     *
     * @Route("/fulltext", name="artwork_contribution_fulltext")
     * @Method("GET")
     * @Template()
     * @param Request $request
     * @return array
     */
    public function fulltextAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:ArtworkContribution');
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->fulltextQuery($q);
            $paginator = $this->get('knp_paginator');
            $artworkContributions = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $artworkContributions = array();
        }

        return array(
            'artworkContributions' => $artworkContributions,
            'q' => $q,
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
