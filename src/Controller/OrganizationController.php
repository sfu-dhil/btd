<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Organization controller.
 *
 * @Route("/organization")
 */
class OrganizationController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all Organization entities.
     *
     * @Route("/", name="organization_index", methods={"GET"})
     *
     * @Template
     */
    public function indexAction(Request $request, EntityManagerInterface $em) {
        $dql = 'SELECT e FROM App:Organization e ORDER BY e.id';
        $query = $em->createQuery($dql);

        $organizations = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);

        return [
            'organizations' => $organizations,
        ];
    }

    /**
     * Full text search for Organization entities.
     *
     * @Route("/search", name="organization_search", methods={"GET"})
     *
     * @Template
     *
     * @return array
     */
    public function searchAction(Request $request, EntityManagerInterface $em, OrganizationRepository $repo) {
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
     *
     * @Route("/new", name="organization_new", methods={"GET", "POST"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @Template
     */
    public function newAction(Request $request, EntityManagerInterface $em) {
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
     *
     * @Route("/{id}", name="organization_show", methods={"GET"})
     *
     * @Template
     */
    public function showAction(Organization $organization) {
        return [
            'organization' => $organization,
        ];
    }

    /**
     * Displays a form to edit an existing Organization entity.
     *
     * @Route("/{id}/edit", name="organization_edit", methods={"GET", "POST"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @Template
     */
    public function editAction(Request $request, Organization $organization, EntityManagerInterface $em) {
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
     *
     * @Route("/{id}/delete", name="organization_delete", methods={"GET"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     */
    public function deleteAction(Request $request, Organization $organization, EntityManagerInterface $em) {
        $em->remove($organization);
        $em->flush();
        $this->addFlash('success', 'The organization was deleted.');

        return $this->redirectToRoute('organization_index');
    }

    /**
     * @Route("/{id}/project_contributions", name="organization_project_contributions", methods={"GET", "POST"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @Template
     */
    public function projectContributionsAction(Request $request, Organization $organization, EntityManagerInterface $em) {
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

    /**
     * @Route("/{id}/artwork_contributions", name="organization_artwork_contributions", methods={"GET", "POST"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @Template
     */
    public function artworkContributionsAction(Request $request, Organization $organization, EntityManagerInterface $em) {
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
