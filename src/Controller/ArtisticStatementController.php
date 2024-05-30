<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\ArtisticStatement;
use App\Form\ArtisticStatementType;
use App\Repository\ArtisticStatementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * ArtisticStatement controller.
 */
#[Route(path: '/artistic_statement')]
class ArtisticStatementController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all ArtisticStatement entities.
     */
    #[Route(path: '/', name: 'artwork_statement_index', methods: ['GET'])]
    #[Template]
    public function index(Request $request, EntityManagerInterface $em) : array {
        $qb = $em->createQueryBuilder();
        $qb->select('e')->from(ArtisticStatement::class, 'e')->orderBy('e.id', 'ASC');
        $query = $qb->getQuery();

        $artisticStatements = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return [
            'artisticStatements' => $artisticStatements,
        ];
    }

    /**
     * Search for ArtisticStatement entities.
     *
     * To make this work, add a method like this one to the
     * App:ArtisticStatement repository. Replace the fieldName with
     * something appropriate, and adjust the generated search.html.twig
     * template.
     */
    #[Route(path: '/search', name: 'artwork_statement_search', methods: ['GET'])]
    #[Template]
    public function search(Request $request, EntityManagerInterface $em, ArtisticStatementRepository $repo) : array {
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->searchQuery($q);

            $artisticStatements = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $artisticStatements = [];
        }

        return [
            'artisticStatements' => $artisticStatements,
            'q' => $q,
        ];
    }

    #[Route(path: '/fulltext', name: 'artwork_statement_fulltext', methods: ['GET'])]
    #[Template]
    public function fulltext(Request $request, ArtisticStatementRepository $repo) : array {
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->fulltextQuery($q);

            $artisticStatements = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $artisticStatements = [];
        }

        return [
            'artisticStatements' => $artisticStatements,
            'q' => $q,
        ];
    }

    /**
     * Creates a new ArtisticStatement entity.
     */
    #[Route(path: '/new', name: 'artwork_statement_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function new(Request $request, EntityManagerInterface $em) : array|RedirectResponse {
        $artisticStatement = new ArtisticStatement();
        $form = $this->createForm(ArtisticStatementType::class, $artisticStatement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($artisticStatement);
            $em->flush();

            $this->addFlash('success', 'The new artistic statement was created.');

            return $this->redirectToRoute('artwork_statement_show', ['id' => $artisticStatement->getId()]);
        }

        return [
            'artisticStatement' => $artisticStatement,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a ArtisticStatement entity.
     */
    #[Route(path: '/{id}', name: 'artwork_statement_show', methods: ['GET'])]
    #[Template]
    public function show(ArtisticStatement $artisticStatement) : array {
        return [
            'artisticStatement' => $artisticStatement,
        ];
    }

    /**
     * Displays a form to edit an existing ArtisticStatement entity.
     */
    #[Route(path: '/{id}/edit', name: 'artwork_statement_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function edit(Request $request, ArtisticStatement $artisticStatement, EntityManagerInterface $em) : array|RedirectResponse {
        if ( ! $this->isGranted('ROLE_CONTENT_ADMIN')) {
            $this->addFlash('danger', 'You must login to access this page.');

            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $editForm = $this->createForm(ArtisticStatementType::class, $artisticStatement);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The artisticStatement has been updated.');

            return $this->redirectToRoute('artwork_statement_show', ['id' => $artisticStatement->getId()]);
        }

        return [
            'artisticStatement' => $artisticStatement,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a ArtisticStatement entity.
     */
    #[Route(path: '/{id}/delete', name: 'artwork_statement_delete', methods: ['GET'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    public function delete(ArtisticStatement $artisticStatement, EntityManagerInterface $em) : RedirectResponse {
        $em->remove($artisticStatement);
        $em->flush();
        $this->addFlash('success', 'The artisticStatement was deleted.');

        return $this->redirectToRoute('artwork_statement_index');
    }
}
