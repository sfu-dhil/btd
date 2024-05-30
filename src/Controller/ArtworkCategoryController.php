<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\ArtworkCategory;
use App\Form\Artwork\ArtworkCategoryType;
use App\Repository\MediaFileRepository;
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
 * ArtworkCategory controller.
 */
#[Route(path: '/artwork_category')]
class ArtworkCategoryController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all ArtworkCategory entities.
     */
    #[Route(path: '/', name: 'artwork_category_index', methods: ['GET'])]
    #[Template]
    public function index(Request $request, EntityManagerInterface $em, MediaFileRepository $repo) : array {
        $dql = 'SELECT e FROM App:ArtworkCategory e ORDER BY e.id';
        $query = $em->createQuery($dql);

        $artworkCategories = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return [
            'artworkCategories' => $artworkCategories,
        ];
    }

    /**
     * Creates a new ArtworkCategory entity.
     */
    #[Route(path: '/new', name: 'artwork_category_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function new(Request $request, EntityManagerInterface $em, MediaFileRepository $repo) : array|RedirectResponse {
        $artworkCategory = new ArtworkCategory();
        $form = $this->createForm(ArtworkCategoryType::class, $artworkCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($artworkCategory);
            $em->flush();

            $this->addFlash('success', 'The new artworkCategory was created.');

            return $this->redirectToRoute('artwork_category_show', ['id' => $artworkCategory->getId()]);
        }

        return [
            'artworkCategory' => $artworkCategory,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a ArtworkCategory entity.
     */
    #[Route(path: '/{id}', name: 'artwork_category_show', methods: ['GET'])]
    #[Template]
    public function show(ArtworkCategory $artworkCategory) : array {
        return [
            'artworkCategory' => $artworkCategory,
        ];
    }

    /**
     * Displays a form to edit an existing ArtworkCategory entity.
     */
    #[Route(path: '/{id}/edit', name: 'artwork_category_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function edit(Request $request, ArtworkCategory $artworkCategory, EntityManagerInterface $em, MediaFileRepository $repo) : array|RedirectResponse {
        $editForm = $this->createForm(ArtworkCategoryType::class, $artworkCategory);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The artworkCategory has been updated.');

            return $this->redirectToRoute('artwork_category_show', ['id' => $artworkCategory->getId()]);
        }

        return [
            'artworkCategory' => $artworkCategory,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a ArtworkCategory entity.
     */
    #[Route(path: '/{id}/delete', name: 'artwork_category_delete', methods: ['GET'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    public function delete(ArtworkCategory $artworkCategory, EntityManagerInterface $em, MediaFileRepository $repo) : RedirectResponse {
        $em->remove($artworkCategory);
        $em->flush();
        $this->addFlash('success', 'The artworkCategory was deleted.');

        return $this->redirectToRoute('artwork_category_index');
    }
}
