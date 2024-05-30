<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Project;
use App\Entity\ProjectPage;
use App\Form\Project\ProjectPageType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;

/**
 * ProjectPage controller.
 */
#[Route(path: '/project/{projectId}/page', requirements: [
    'projectId' => Requirement::DIGITS,
])]
#[ParamConverter('project', options: ['id' => 'projectId'])]
class ProjectPageController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all ProjectPage entities.
     */
    #[Route(path: '', name: 'project_page_index', methods: ['GET'])]
    #[Template]
    public function index(Request $request, Project $project, EntityManagerInterface $em) : array {
        $dql = 'SELECT e FROM App:ProjectPage e WHERE e.project = :project ORDER BY e.id';
        $query = $em->createQuery($dql);
        $query->setParameter('project', $project);

        $projectPages = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return [
            'project' => $project,
            'projectPages' => $projectPages,
        ];
    }

    /**
     * Creates a new ProjectPage entity.
     */
    #[Route(path: '/new', name: 'project_page_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function new(Request $request, Project $project, EntityManagerInterface $em) : array|RedirectResponse {
        $projectPage = new ProjectPage();
        $projectPage->setProject($project);
        $form = $this->createForm(ProjectPageType::class, $projectPage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($projectPage);
            $em->flush();

            $this->addFlash('success', 'The new projectPage was created.');

            return $this->redirectToRoute('project_page_show', ['projectId' => $project->getId(), 'id' => $projectPage->getId()]);
        }

        return [
            'project' => $project,
            'projectPage' => $projectPage,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a ProjectPage entity.
     */
    #[Route(path: '/{id}', name: 'project_page_show', methods: ['GET'])]
    #[Template]
    public function show(Project $project, ProjectPage $projectPage) : array {
        if ($project->getId() !== $projectPage->getProject()->getId()) {
            throw new NotFoundHttpException();
        }

        return [
            'project' => $project,
            'projectPage' => $projectPage,
        ];
    }

    /**
     * Displays a form to edit an existing ProjectPage entity.
     */
    #[Route(path: '/{id}/edit', name: 'project_page_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function edit(Request $request, Project $project, ProjectPage $projectPage, EntityManagerInterface $em) : array|RedirectResponse {
        if ($project->getId() !== $projectPage->getProject()->getId()) {
            throw new NotFoundHttpException();
        }
        $editForm = $this->createForm(ProjectPageType::class, $projectPage);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The projectPage has been updated.');

            return $this->redirectToRoute('project_page_show', [
                'projectId' => $project->getId(),
                'id' => $projectPage->getId(),
            ]);
        }

        return [
            'project' => $project,
            'projectPage' => $projectPage,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a ProjectPage entity.
     */
    #[Route(path: '/{id}/delete', name: 'project_page_delete', methods: ['GET'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    public function delete(Project $project, ProjectPage $projectPage, EntityManagerInterface $em) : RedirectResponse {
        $em->remove($projectPage);
        $em->flush();
        $this->addFlash('success', 'The projectPage was deleted.');

        return $this->redirectToRoute('project_page_index', [
            'projectId' => $project->getId(),
        ]);
    }
}
