<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Location;
use App\Repository\LocationRepository;
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
 * Location controller.
 */
#[Route(path: '/location')]
class LocationController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all Location entities.
     */
    #[Route(path: '/', name: 'location_index', methods: ['GET'])]
    #[Template]
    public function index(Request $request, EntityManagerInterface $em) : array {
        $dql = 'SELECT e FROM App:Location e ORDER BY e.id';
        $query = $em->createQuery($dql);

        $locations = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return [
            'locations' => $locations,
        ];
    }

    /**
     * Full text search for Location entities.
     */
    #[Route(path: '/search', name: 'location_search', methods: ['GET'])]
    #[Template]
    public function search(Request $request, LocationRepository $repo) : array {
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->fulltextQuery($q);

            $locations = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $locations = [];
        }

        return [
            'locations' => $locations,
            'q' => $q,
        ];
    }

    /**
     * Creates a new Location entity.
     */
    #[Route(path: '/new', name: 'location_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function new(Request $request, EntityManagerInterface $em) : array|RedirectResponse {
        $location = new Location();
        $form = $this->createForm('App\Form\LocationType', $location);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($location);
            $em->flush();

            $this->addFlash('success', 'The new location was created.');

            return $this->redirectToRoute('location_show', ['id' => $location->getId()]);
        }

        return [
            'location' => $location,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a Location entity.
     */
    #[Route(path: '/{id}', name: 'location_show', methods: ['GET'])]
    #[Template]
    public function show(Location $location) : array {
        return [
            'location' => $location,
        ];
    }

    /**
     * Displays a form to edit an existing Location entity.
     */
    #[Route(path: '/{id}/edit', name: 'location_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function edit(Request $request, Location $location, EntityManagerInterface $em) : array|RedirectResponse {
        $editForm = $this->createForm('App\Form\LocationType', $location);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The location has been updated.');

            return $this->redirectToRoute('location_show', ['id' => $location->getId()]);
        }

        return [
            'location' => $location,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a Location entity.
     */
    #[Route(path: '/{id}/delete', name: 'location_delete', methods: ['GET'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    public function delete(Location $location, EntityManagerInterface $em) : RedirectResponse {
        $em->remove($location);
        $em->flush();
        $this->addFlash('success', 'The location was deleted.');

        return $this->redirectToRoute('location_index');
    }
}
