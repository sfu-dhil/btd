<?php

declare(strict_types=1);

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
 */
#[Route(path: '/project_contribution')]
class ProjectContributionController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all ProjectContribution entities.
     */
    #[Route(path: '/', name: 'project_contribution_index', methods: ['GET'])]
    #[Template]
    public function index(Request $request, EntityManagerInterface $em) : array {
        $dql = 'SELECT e FROM App:ProjectContribution e ORDER BY e.id';
        $query = $em->createQuery($dql);

        $projectContributions = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return [
            'projectContributions' => $projectContributions,
        ];
    }
}
