<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Venue;
use App\Repository\VenueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Venue controller.
 */
#[Route(path: '/venue')]
class VenueController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all Venue entities.
     */
    #[Route(path: '/', name: 'venue_index', methods: ['GET'])]
    #[Template]
    public function index(Request $request, EntityManagerInterface $em) : array {
        $dql = 'SELECT e FROM App:Venue e ORDER BY e.id';
        $query = $em->createQuery($dql);

        $venues = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return [
            'venues' => $venues,
        ];
    }

    #[Route(path: '/typeahead', name: 'venue_typeahead', methods: ['GET'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    public function typeahead(Request $request, VenueRepository $repo) : JsonResponse {
        $q = $request->query->get('q');
        if ( ! $q) {
            return new JsonResponse([]);
        }
        $data = [];

        foreach ($repo->typeaheadQuery($q) as $result) {
            $data[] = [
                'id' => $result->getId(),
                'text' => $result->getName(),
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * Creates a new Venue entity.
     */
    #[Route(path: '/new', name: 'venue_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function new(Request $request, EntityManagerInterface $em) : array|RedirectResponse {
        $venue = new Venue();
        $form = $this->createForm('App\Form\VenueType', $venue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($venue);
            $em->flush();

            $this->addFlash('success', 'The new venue was created.');

            return $this->redirectToRoute('venue_show', ['id' => $venue->getId()]);
        }

        return [
            'venue' => $venue,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a Venue entity.
     */
    #[Route(path: '/{id}', name: 'venue_show', methods: ['GET'])]
    #[Template]
    public function show(Venue $venue) : array {
        return [
            'venue' => $venue,
        ];
    }

    /**
     * Displays a form to edit an existing Venue entity.
     */
    #[Route(path: '/{id}/edit', name: 'venue_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function edit(Request $request, Venue $venue, EntityManagerInterface $em) : array|RedirectResponse {
        $editForm = $this->createForm('App\Form\VenueType', $venue);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The venue has been updated.');

            return $this->redirectToRoute('venue_show', ['id' => $venue->getId()]);
        }

        return [
            'venue' => $venue,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a Venue entity.
     */
    #[Route(path: '/{id}/delete', name: 'venue_delete', methods: ['GET'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    public function delete(Venue $venue, EntityManagerInterface $em) : RedirectResponse {
        $em->remove($venue);
        $em->flush();
        $this->addFlash('success', 'The venue was deleted.');

        return $this->redirectToRoute('venue_index');
    }
}
