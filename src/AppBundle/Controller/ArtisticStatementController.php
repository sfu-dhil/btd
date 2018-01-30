<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\ArtisticStatement;
use AppBundle\Form\ArtisticStatementType;

/**
 * ArtisticStatement controller.
 *
 * @Route("/artistic_statement")
 */
class ArtisticStatementController extends Controller {

    /**
     * Lists all ArtisticStatement entities.
     *
     * @Route("/", name="artwork_statement_index")
     * @Method("GET")
     * @Template()
     * @param Request $request
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('e')->from(ArtisticStatement::class, 'e')->orderBy('e.id', 'ASC');
        $query = $qb->getQuery();
        $paginator = $this->get('knp_paginator');
        $artisticStatements = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'artisticStatements' => $artisticStatements,
        );
    }

    /**
     * Search for ArtisticStatement entities.
     *
     * To make this work, add a method like this one to the 
     * AppBundle:ArtisticStatement repository. Replace the fieldName with
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
     * @Route("/search", name="artwork_statement_search")
     * @Method("GET")
     * @Template()
     * @param Request $request
     */
    public function searchAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:ArtisticStatement');
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->searchQuery($q);
            $paginator = $this->get('knp_paginator');
            $artisticStatements = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $artisticStatements = array();
        }

        return array(
            'artisticStatements' => $artisticStatements,
            'q' => $q,
        );
    }

    /**
     * Full text search for ArtisticStatement entities.
     *
     * To make this work, add a method like this one to the 
     * AppBundle:ArtisticStatement repository. Replace the fieldName with
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
     * fulltext indexes on your ArtisticStatement entity.
     *     ORM\Index(name="alias_name_idx",columns="name", flags={"fulltext"})
     *
     *
     * @Route("/fulltext", name="artwork_statement_fulltext")
     * @Method("GET")
     * @Template()
     * @param Request $request
     * @return array
     */
    public function fulltextAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:ArtisticStatement');
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->fulltextQuery($q);
            $paginator = $this->get('knp_paginator');
            $artisticStatements = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $artisticStatements = array();
        }

        return array(
            'artisticStatements' => $artisticStatements,
            'q' => $q,
        );
    }

    /**
     * Creates a new ArtisticStatement entity.
     *
     * @Route("/new", name="artwork_statement_new")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     */
    public function newAction(Request $request) {
        if (!$this->isGranted('ROLE_CONTENT_ADMIN')) {
            $this->addFlash('danger', 'You must login to access this page.');
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $artisticStatement = new ArtisticStatement();
        $form = $this->createForm(ArtisticStatementType::class, $artisticStatement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($artisticStatement);
            $em->flush();

            $this->addFlash('success', 'The new artisticStatement was created.');
            return $this->redirectToRoute('artwork_statement_show', array('id' => $artisticStatement->getId()));
        }

        return array(
            'artisticStatement' => $artisticStatement,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a ArtisticStatement entity.
     *
     * @Route("/{id}", name="artwork_statement_show")
     * @Method("GET")
     * @Template()
     * @param ArtisticStatement $artisticStatement
     */
    public function showAction(ArtisticStatement $artisticStatement) {

        return array(
            'artisticStatement' => $artisticStatement,
        );
    }

    /**
     * Displays a form to edit an existing ArtisticStatement entity.
     *
     * @Route("/{id}/edit", name="artwork_statement_edit")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     * @param ArtisticStatement $artisticStatement
     */
    public function editAction(Request $request, ArtisticStatement $artisticStatement) {
        if (!$this->isGranted('ROLE_CONTENT_ADMIN')) {
            $this->addFlash('danger', 'You must login to access this page.');
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $editForm = $this->createForm(ArtisticStatementType::class, $artisticStatement);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The artisticStatement has been updated.');
            return $this->redirectToRoute('artwork_statement_show', array('id' => $artisticStatement->getId()));
        }

        return array(
            'artisticStatement' => $artisticStatement,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a ArtisticStatement entity.
     *
     * @Route("/{id}/delete", name="artwork_statement_delete")
     * @Method("GET")
     * @param Request $request
     * @param ArtisticStatement $artisticStatement
     */
    public function deleteAction(Request $request, ArtisticStatement $artisticStatement) {
        if (!$this->isGranted('ROLE_CONTENT_ADMIN')) {
            $this->addFlash('danger', 'You must login to access this page.');
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($artisticStatement);
        $em->flush();
        $this->addFlash('success', 'The artisticStatement was deleted.');

        return $this->redirectToRoute('artwork_statement_index');
    }

}
