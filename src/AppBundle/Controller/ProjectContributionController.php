<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\ProjectContribution;
use AppBundle\Form\ProjectContributionType;

/**
 * ProjectContribution controller.
 *
 * @Route("/project_contribution")
 */
class ProjectContributionController extends Controller {

    /**
     * Lists all ProjectContribution entities.
     *
     * @Route("/", name="project_contribution_index")
     * @Method("GET")
     * @Template()
     * @param Request $request
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:ProjectContribution e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $projectContributions = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'projectContributions' => $projectContributions,
        );
    }

    /**
     * Search for ProjectContribution entities.
     *
     * To make this work, add a method like this one to the 
     * AppBundle:ProjectContribution repository. Replace the fieldName with
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
     * @Route("/search", name="project_contribution_search")
     * @Method("GET")
     * @Template()
     * @param Request $request
     */
    public function searchAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:ProjectContribution');
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->searchQuery($q);
            $paginator = $this->get('knp_paginator');
            $projectContributions = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $projectContributions = array();
        }

        return array(
            'projectContributions' => $projectContributions,
            'q' => $q,
        );
    }

    /**
     * Full text search for ProjectContribution entities.
     *
     * To make this work, add a method like this one to the 
     * AppBundle:ProjectContribution repository. Replace the fieldName with
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
     * fulltext indexes on your ProjectContribution entity.
     *     ORM\Index(name="alias_name_idx",columns="name", flags={"fulltext"})
     *
     *
     * @Route("/fulltext", name="project_contribution_fulltext")
     * @Method("GET")
     * @Template()
     * @param Request $request
     * @return array
     */
    public function fulltextAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:ProjectContribution');
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->fulltextQuery($q);
            $paginator = $this->get('knp_paginator');
            $projectContributions = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $projectContributions = array();
        }

        return array(
            'projectContributions' => $projectContributions,
            'q' => $q,
        );
    }

    /**
     * Creates a new ProjectContribution entity.
     *
     * @Route("/new", name="project_contribution_new")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     */
    public function newAction(Request $request) {
        $projectContribution = new ProjectContribution();
        $form = $this->createForm('AppBundle\Form\ProjectContributionType', $projectContribution);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($projectContribution);
            $em->flush();

            $this->addFlash('success', 'The new projectContribution was created.');
            return $this->redirectToRoute('project_contribution_show', array('id' => $projectContribution->getId()));
        }

        return array(
            'projectContribution' => $projectContribution,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a ProjectContribution entity.
     *
     * @Route("/{id}", name="project_contribution_show")
     * @Method("GET")
     * @Template()
     * @param ProjectContribution $projectContribution
     */
    public function showAction(ProjectContribution $projectContribution) {

        return array(
            'projectContribution' => $projectContribution,
        );
    }

    /**
     * Displays a form to edit an existing ProjectContribution entity.
     *
     * @Route("/{id}/edit", name="project_contribution_edit")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     * @param ProjectContribution $projectContribution
     */
    public function editAction(Request $request, ProjectContribution $projectContribution) {
        $editForm = $this->createForm('AppBundle\Form\ProjectContributionType', $projectContribution);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The projectContribution has been updated.');
            return $this->redirectToRoute('project_contribution_show', array('id' => $projectContribution->getId()));
        }

        return array(
            'projectContribution' => $projectContribution,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a ProjectContribution entity.
     *
     * @Route("/{id}/delete", name="project_contribution_delete")
     * @Method("GET")
     * @param Request $request
     * @param ProjectContribution $projectContribution
     */
    public function deleteAction(Request $request, ProjectContribution $projectContribution) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($projectContribution);
        $em->flush();
        $this->addFlash('success', 'The projectContribution was deleted.');

        return $this->redirectToRoute('project_contribution_index');
    }

}
