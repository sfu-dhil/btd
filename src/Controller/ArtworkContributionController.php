<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\ArtworkContribution;
use App\Repository\MediaFileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * ArtworkContribution controller.
 *
 * @Route("/artwork_contribution")
 */
class ArtworkContributionController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all ArtworkContribution entities.
     *
     * @Route("/", name="artwork_contribution_index", methods={"GET"})
     *
     * @Template
     */
    public function indexAction(Request $request, EntityManagerInterface $em, MediaFileRepository $repo) {
        $dql = 'SELECT e FROM App:ArtworkContribution e ORDER BY e.id';
        $query = $em->createQuery($dql);

        $artworkContributions = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);

        return [
            'artworkContributions' => $artworkContributions,
        ];
    }
}
