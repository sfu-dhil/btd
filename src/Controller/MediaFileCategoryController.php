<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\MediaFileCategory;
use App\Form\MediaFileCategoryType;
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
 * MediaFileCategory controller.
 */
#[Route(path: '/media_file_category')]
class MediaFileCategoryController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all MediaFileCategory entities.
     */
    #[Route(path: '/', name: 'media_file_category_index', methods: ['GET'])]
    #[Template]
    public function index(Request $request, EntityManagerInterface $em) : array {
        $dql = 'SELECT e FROM App:MediaFileCategory e ORDER BY e.id';
        $query = $em->createQuery($dql);

        $mediaFileCategories = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return [
            'mediaFileCategories' => $mediaFileCategories,
        ];
    }

    /**
     * Creates a new MediaFileCategory entity.
     */
    #[Route(path: '/new', name: 'media_file_category_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function new(Request $request, EntityManagerInterface $em) : array|RedirectResponse {
        $mediaFileCategory = new MediaFileCategory();
        $form = $this->createForm(MediaFileCategoryType::class, $mediaFileCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($mediaFileCategory);
            $em->flush();

            $this->addFlash('success', 'The new mediaFileCategory was created.');

            return $this->redirectToRoute('media_file_category_show', ['id' => $mediaFileCategory->getId()]);
        }

        return [
            'mediaFileCategory' => $mediaFileCategory,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a MediaFileCategory entity.
     */
    #[Route(path: '/{id}', name: 'media_file_category_show', methods: ['GET'])]
    #[Template]
    public function show(MediaFileCategory $mediaFileCategory) : array {
        return [
            'mediaFileCategory' => $mediaFileCategory,
        ];
    }

    /**
     * Displays a form to edit an existing MediaFileCategory entity.
     */
    #[Route(path: '/{id}/edit', name: 'media_file_category_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function edit(Request $request, MediaFileCategory $mediaFileCategory, EntityManagerInterface $em) : array|RedirectResponse {
        $editForm = $this->createForm('App\Form\MediaFileCategoryType', $mediaFileCategory);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The mediaFileCategory has been updated.');

            return $this->redirectToRoute('media_file_category_show', ['id' => $mediaFileCategory->getId()]);
        }

        return [
            'mediaFileCategory' => $mediaFileCategory,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a MediaFileCategory entity.
     */
    #[Route(path: '/{id}/delete', name: 'media_file_category_delete', methods: ['GET'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    public function delete(MediaFileCategory $mediaFileCategory, EntityManagerInterface $em) : RedirectResponse {
        $em->remove($mediaFileCategory);
        $em->flush();
        $this->addFlash('success', 'The mediaFileCategory was deleted.');

        return $this->redirectToRoute('media_file_category_index');
    }
}
