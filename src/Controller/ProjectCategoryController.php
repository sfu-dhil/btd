<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\ProjectCategory;
use App\Form\Project\ProjectCategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * ProjectCategory controller.
 *
 * @Route("/project_category")
 */
class ProjectCategoryController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all ProjectCategory entities.
     *
     * @Route("/", name="project_category_index", methods={"GET"})
     *
     * @Template
     */
    public function indexAction(Request $request, EntityManagerInterface $em) {
        $dql = 'SELECT e FROM App:ProjectCategory e ORDER BY e.id';
        $query = $em->createQuery($dql);

        $projectCategories = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);

        return [
            'projectCategories' => $projectCategories,
        ];
    }

    /**
     * Creates a new ProjectCategory entity.
     *
     * @Route("/new", name="project_category_new", methods={"GET", "POST"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @Template
     */
    public function newAction(Request $request, EntityManagerInterface $em) {
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
     *
     * @Route("/{id}", name="project_category_show", methods={"GET"})
     *
     * @Template
     */
    public function showAction(ProjectCategory $projectCategory) {
        return [
            'projectCategory' => $projectCategory,
        ];
    }

    /**
     * Displays a form to edit an existing ProjectCategory entity.
     *
     * @Route("/{id}/edit", name="project_category_edit", methods={"GET", "POST"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @Template
     */
    public function editAction(Request $request, ProjectCategory $projectCategory, EntityManagerInterface $em) {
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
     *
     * @Route("/{id}/delete", name="project_category_delete", methods={"GET"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     */
    public function deleteAction(Request $request, ProjectCategory $projectCategory, EntityManagerInterface $em) {
        $em->remove($projectCategory);
        $em->flush();
        $this->addFlash('success', 'The projectCategory was deleted.');

        return $this->redirectToRoute('project_category_index');
    }
}
