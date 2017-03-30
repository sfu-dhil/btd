<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
}
