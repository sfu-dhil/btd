<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\ProjectCategory;
use App\Form\Project\ProjectCategoryType;
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
 * ProjectCategory controller.
 */
#[Route(path: '/project_category')]
class ProjectCategoryController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all ProjectCategory entities.
     */
    #[Route(path: '/', name: 'project_category_index', methods: ['GET'])]
    #[Template]
    public function index(Request $request, EntityManagerInterface $em) : array {
        $dql = 'SELECT e FROM App:ProjectCategory e ORDER BY e.id';
        $query = $em->createQuery($dql);

        $projectCategories = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return [
            'projectCategories' => $projectCategories,
        ];
    }

    /**
     * Creates a new ProjectCategory entity.
     */
    #[Route(path: '/new', name: 'project_category_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function new(Request $request, EntityManagerInterface $em) : array|RedirectResponse {
        $projectCategory = new ProjectCategory();
        $form = $this->createForm(ProjectCategoryType::class, $projectCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($projectCategory);
            $em->flush();

            $this->addFlash('success', 'The new projectCategory was created.');

            return $this->redirectToRoute('project_category_show', ['id' => $projectCategory->getId()]);
        }

        return [
            'projectCategory' => $projectCategory,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a ProjectCategory entity.
     */
    #[Route(path: '/{id}', name: 'project_category_show', methods: ['GET'])]
    #[Template]
    public function show(ProjectCategory $projectCategory) : array {
        return [
            'projectCategory' => $projectCategory,
        ];
    }

    /**
     * Displays a form to edit an existing ProjectCategory entity.
     */
    #[Route(path: '/{id}/edit', name: 'project_category_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function edit(Request $request, ProjectCategory $projectCategory, EntityManagerInterface $em) : array|RedirectResponse {
        $editForm = $this->createForm(ProjectCategoryType::class, $projectCategory);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The projectCategory has been updated.');

            return $this->redirectToRoute('project_category_show', ['id' => $projectCategory->getId()]);
        }

        return [
            'projectCategory' => $projectCategory,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a ProjectCategory entity.
     */
    #[Route(path: '/{id}/delete', name: 'project_category_delete', methods: ['GET'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    public function delete(ProjectCategory $projectCategory, EntityManagerInterface $em) : RedirectResponse {
        $em->remove($projectCategory);
        $em->flush();
        $this->addFlash('success', 'The projectCategory was deleted.');

        return $this->redirectToRoute('project_category_index');
    }
}
