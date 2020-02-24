<?php

namespace App\Controller;

use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController  implements PaginatorAwareInterface {
    use PaginatorTrait;

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
