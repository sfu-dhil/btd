<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller {
    /**
     * @Route("/", name="homepage")
     * @Template()
     *
     * @param Request $request
     */
    public function indexAction(Request $request) {
        return array();
    }

    /**
     * @Route("/privacy", name="privacy")
     * @Template()
     *
     * @param Request $request
     */
    public function privacyAction(Request $request) {
    }
}
