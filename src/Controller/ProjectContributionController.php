<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * ProjectContribution controller.
 *
 * @Route("/project_contribution")
 */
class ProjectContributionController extends AbstractController  implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all ProjectContribution entities.
     *
     * @Route("/", name="project_contribution_index", methods={"GET"})
     *
     * @Template()
     *
     * @param Request $request
     */
    public function indexAction(Request $request, EntityManagerInterface $em) {
        $dql = 'SELECT e FROM App:ProjectContribution e ORDER BY e.id';
        $query = $em->createQuery($dql);

        $projectContributions = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'projectContributions' => $projectContributions,
        );
    }
}
