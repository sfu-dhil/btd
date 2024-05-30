<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Project;
use App\Form\Project\ArtworksType;
use App\Form\Project\ContributionsType;
use App\Form\Project\ProjectType;
use App\Repository\MediaFileRepository;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Nines\UtilBundle\Services\Text;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Project controller.
 */
#[Route(path: '/project')]
class ProjectController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all Project entities.
     */
    #[Route(path: '/', name: 'project_index', methods: ['GET'])]
    #[Template]
    public function index(Request $request, EntityManagerInterface $em) : array {
        $dql = 'SELECT e FROM App:Project e ORDER BY e.id';
        $query = $em->createQuery($dql);

        $projects = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return [
            'projects' => $projects,
        ];
    }

    #[Route(path: '/typeahead', name: 'project_typeahead', methods: ['GET'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    public function typeahead(Request $request, ProjectRepository $repo) : JsonResponse {
        $q = $request->query->get('q');
        if ( ! $q) {
            return new JsonResponse([]);
        }
        $data = [];

        foreach ($repo->typeaheadQuery($q) as $result) {
            $data[] = [
                'id' => $result->getId(),
                'text' => $result->getTitle(),
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * Full text search for Project entities.
     */
    #[Route(path: '/fulltext', name: 'project_search', methods: ['GET'])]
    #[Template]
    public function search(Request $request, ProjectRepository $repo) : array {
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->fulltextQuery($q);

            $projects = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $projects = [];
        }

        return [
            'projects' => $projects,
            'q' => $q,
        ];
    }

    /**
     * Creates a new Project entity.
     */
    #[Route(path: '/new', name: 'project_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function new(Request $request, EntityManagerInterface $em) : array|RedirectResponse {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($project);
            $em->flush();

            $this->addFlash('success', 'The new project was created.');

            return $this->redirectToRoute('project_show', ['id' => $project->getId()]);
        }

        return [
            'project' => $project,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a Project entity.
     */
    #[Route(path: '/{id}', name: 'project_show', methods: ['GET'])]
    #[Template]
    public function show(Project $project) : ?array {
        return [
            'project' => $project,
        ];
    }

    /**
     * Displays a form to edit an existing Project entity.
     */
    #[Route(path: '/{id}/edit', name: 'project_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function edit(Request $request, Project $project, EntityManagerInterface $em) : array|RedirectResponse {
        $editForm = $this->createForm(ProjectType::class, $project);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The project has been updated.');

            return $this->redirectToRoute('project_show', ['id' => $project->getId()]);
        }

        return [
            'project' => $project,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a Project entity.
     */
    #[Route(path: '/{id}/delete', name: 'project_delete', methods: ['GET'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    public function delete(Project $project, EntityManagerInterface $em) : RedirectResponse {
        $em->remove($project);
        $em->flush();
        $this->addFlash('success', 'The project was deleted.');

        return $this->redirectToRoute('project_index');
    }

    #[Route(path: '/{id}/add_media', name: 'project_add_media', methods: ['GET'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function addMedia(Request $request, Project $project, EntityManagerInterface $em, MediaFileRepository $repo) : array|RedirectResponse {
        $q = $request->query->get('q');

        if ($q) {
            $query = $repo->searchQuery($q);
            $results = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $query = $repo->findAll();
            $results = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);
        }

        $addId = $request->query->get('addId');
        if ($addId) {
            $mediaFile = $repo->find($addId);
            if ( ! $project->hasMediaFile($mediaFile)) {
                $project->addMediaFile($mediaFile);
                $mediaFile->addProject($project);
                $em->flush();
            }
            $this->addFlash('success', 'The media file is associated with the artowrk.');

            return $this->redirectToRoute('project_add_media', [
                'id' => $project->getId(),
                'q' => $q,
                'page' => $request->query->getInt('page', 1),
            ]);
        }

        return [
            'project' => $project,
            'q' => $q,
            'results' => $results,
        ];
    }

    #[Route(path: '/{id}/remove_media', name: 'project_remove_media', methods: ['GET'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function removeMedia(Request $request, Project $project, EntityManagerInterface $em, MediaFileRepository $repo) : array|RedirectResponse {
        $results = $this->paginator->paginate($project->getMediaFiles(), $request->query->getInt('page', 1), 25);

        $removeId = $request->query->get('removeId');
        if ($removeId) {
            $mediaFile = $repo->find($removeId);
            if ($project->hasMediaFile($mediaFile)) {
                $project->removeMediaFile($mediaFile);
                $mediaFile->removeProject($project);
                $em->flush();
            }
            $this->addFlash('success', 'The media file is associated with the artowrk.');

            return $this->redirectToRoute('project_remove_media', [
                'id' => $project->getId(),
                'page' => $request->query->getInt('page', 1),
            ]);
        }

        return [
            'project' => $project,
            'results' => $results,
        ];
    }

    #[Route(path: '/{id}/contributions', name: 'project_contributions', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function contributions(Request $request, Project $project, EntityManagerInterface $em) : array {
        $form = $this->createForm(ContributionsType::class, $project, [
            'project' => $project,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The contributions have been updated.');
            // return $this->redirectToRoute('project_show', array('id' => $project->getId()));
        }

        return [
            'project' => $project,
            'edit_form' => $form->createView(),
        ];
    }

    #[Route(path: '/{id}/artworks', name: 'project_artworks', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function artworks(Request $request, Project $project, EntityManagerInterface $em) : array|RedirectResponse {
        $form = $this->createForm(ArtworksType::class, $project, [
            'project' => $project,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The artworks have been updated.');

            return $this->redirectToRoute('project_show', ['id' => $project->getId()]);
        }

        return [
            'project' => $project,
            'edit_form' => $form->createView(),
        ];
    }
}
