<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Organization;
use App\Form\Organization\ArtworkContributionsType;
use App\Form\Organization\OrganizationType;
use App\Form\Organization\ProjectContributionsType;
use App\Repository\OrganizationRepository;
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
 * Organization controller.
 */
#[Route(path: '/organization')]
class OrganizationController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all Organization entities.
     */
    #[Route(path: '/', name: 'organization_index', methods: ['GET'])]
    #[Template]
    public function index(Request $request, EntityManagerInterface $em) : array {
        $dql = 'SELECT e FROM App:Organization e ORDER BY e.id';
        $query = $em->createQuery($dql);

        $organizations = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return [
            'organizations' => $organizations,
        ];
    }

    /**
     * Full text search for Organization entities.
     */
    #[Route(path: '/search', name: 'organization_search', methods: ['GET'])]
    #[Template]
    public function search(Request $request, EntityManagerInterface $em, OrganizationRepository $repo) : array {
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->fulltextQuery($q);

            $organizations = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $organizations = [];
        }

        return [
            'organizations' => $organizations,
            'q' => $q,
        ];
    }

    /**
     * Creates a new Organization entity.
     */
    #[Route(path: '/new', name: 'organization_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function new(Request $request, EntityManagerInterface $em) : array|RedirectResponse {
        $organization = new Organization();
        $form = $this->createForm(OrganizationType::class, $organization);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($organization);
            $em->flush();

            $this->addFlash('success', 'The new organization was created.');

            return $this->redirectToRoute('organization_show', ['id' => $organization->getId()]);
        }

        return [
            'organization' => $organization,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a Organization entity.
     */
    #[Route(path: '/{id}', name: 'organization_show', methods: ['GET'])]
    #[Template]
    public function show(Organization $organization) : ?array {
        return [
            'organization' => $organization,
        ];
    }

    /**
     * Displays a form to edit an existing Organization entity.
     */
    #[Route(path: '/{id}/edit', name: 'organization_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function edit(Request $request, Organization $organization, EntityManagerInterface $em) : array|RedirectResponse {
        $editForm = $this->createForm(OrganizationType::class, $organization);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The organization has been updated.');

            return $this->redirectToRoute('organization_show', ['id' => $organization->getId()]);
        }

        return [
            'organization' => $organization,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a Organization entity.
     */
    #[Route(path: '/{id}/delete', name: 'organization_delete', methods: ['GET'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    public function delete(Organization $organization, EntityManagerInterface $em) : RedirectResponse {
        $em->remove($organization);
        $em->flush();
        $this->addFlash('success', 'The organization was deleted.');

        return $this->redirectToRoute('organization_index');
    }

    #[Route(path: '/{id}/project_contributions', name: 'organization_project_contributions', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function projectContributions(Request $request, Organization $organization, EntityManagerInterface $em) : array|RedirectResponse {
        $form = $this->createForm(ProjectContributionsType::class, $organization, [
            'organization' => $organization,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The contributions have been updated.');

            return $this->redirectToRoute('organization_show', ['id' => $organization->getId()]);
        }

        return [
            'organization' => $organization,
            'edit_form' => $form->createView(),
        ];
    }

    #[Route(path: '/{id}/artwork_contributions', name: 'organization_artwork_contributions', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function artworkContributions(Request $request, Organization $organization, EntityManagerInterface $em) : array|RedirectResponse {
        $form = $this->createForm(ArtworkContributionsType::class, $organization, [
            'organization' => $organization,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The contributions have been updated.');

            return $this->redirectToRoute('organization_show', ['id' => $organization->getId()]);
        }

        return [
            'organization' => $organization,
            'edit_form' => $form->createView(),
        ];
    }
}
