<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\ArtworkRole;
use App\Form\Artwork\ArtworkRoleType;
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
 * ArtworkRole controller.
 */
#[Route(path: '/artwork_role')]
class ArtworkRoleController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all ArtworkRole entities.
     */
    #[Route(path: '/', name: 'artwork_role_index', methods: ['GET'])]
    #[Template]
    public function index(Request $request, EntityManagerInterface $em) : array {
        $dql = 'SELECT e FROM App:ArtworkRole e ORDER BY e.id';
        $query = $em->createQuery($dql);

        $artworkRoles = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return [
            'artworkRoles' => $artworkRoles,
        ];
    }

    /**
     * Creates a new ArtworkRole entity.
     */
    #[Route(path: '/new', name: 'artwork_role_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function new(Request $request, EntityManagerInterface $em) : array|RedirectResponse {
        $artworkRole = new ArtworkRole();
        $form = $this->createForm(ArtworkRoleType::class, $artworkRole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($artworkRole);
            $em->flush();

            $this->addFlash('success', 'The new artworkRole was created.');

            return $this->redirectToRoute('artwork_role_show', ['id' => $artworkRole->getId()]);
        }

        return [
            'artworkRole' => $artworkRole,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a ArtworkRole entity.
     */
    #[Route(path: '/{id}', name: 'artwork_role_show', methods: ['GET'])]
    #[Template]
    public function show(ArtworkRole $artworkRole) : array {
        return [
            'artworkRole' => $artworkRole,
        ];
    }

    /**
     * Displays a form to edit an existing ArtworkRole entity.
     */
    #[Route(path: '/{id}/edit', name: 'artwork_role_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function edit(Request $request, ArtworkRole $artworkRole, EntityManagerInterface $em) : array|RedirectResponse {
        $editForm = $this->createForm(ArtworkRoleType::class, $artworkRole);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The artworkRole has been updated.');

            return $this->redirectToRoute('artwork_role_show', ['id' => $artworkRole->getId()]);
        }

        return [
            'artworkRole' => $artworkRole,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a ArtworkRole entity.
     */
    #[Route(path: '/{id}/delete', name: 'artwork_role_delete', methods: ['GET'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    public function delete(ArtworkRole $artworkRole, EntityManagerInterface $em) : RedirectResponse {
        $em->remove($artworkRole);
        $em->flush();
        $this->addFlash('success', 'The artworkRole was deleted.');

        return $this->redirectToRoute('artwork_role_index');
    }
}
