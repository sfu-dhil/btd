<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Project controller.
 *
 * @Route("/project")
 */
class ProjectController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all Project entities.
     *
     * @Route("/", name="project_index", methods={"GET"})
     *
     * @Template
     */
    public function indexAction(Request $request, EntityManagerInterface $em) {
        $dql = 'SELECT e FROM App:Project e ORDER BY e.id';
        $query = $em->createQuery($dql);

        $projects = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);

        return [
            'projects' => $projects,
        ];
    }

    /**
     * @Security("is_granted('ROLE_CONTENT_ADMIN')")
     * @Route("/typeahead", name="project_typeahead", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function typeaheadAction(Request $request, ProjectRepository $repo) {
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
     *
     * @Route("/fulltext", name="project_search", methods={"GET"})
     *
     * @Template
     *
     * @return array
     */
    public function searchAction(Request $request, ProjectRepository $repo) {
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
     *
     * @Route("/new", name="project_new", methods={"GET", "POST"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @Template
     */
    public function newAction(Request $request, EntityManagerInterface $em) {
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
     *
     * @Route("/{id}", name="project_show", methods={"GET"})
     *
     * @Template
     */
    public function showAction(Project $project) {
        return [
            'project' => $project,
        ];
    }

    /**
     * Displays a form to edit an existing Project entity.
     *
     * @Route("/{id}/edit", name="project_edit", methods={"GET", "POST"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @Template
     *
     * @param Text $text
     */
    public function editAction(Request $request, Project $project, EntityManagerInterface $em) {
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
     *
     * @Route("/{id}/delete", name="project_delete", methods={"GET"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     */
    public function deleteAction(Request $request, Project $project, EntityManagerInterface $em) {
        $em->remove($project);
        $em->flush();
        $this->addFlash('success', 'The project was deleted.');

        return $this->redirectToRoute('project_index');
    }

    /**
     * @Route("/{id}/add_media", name="project_add_media", methods={"GET"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @Template
     */
    public function addMediaAction(Request $request, Project $project, EntityManagerInterface $em, MediaFileRepository $repo) {
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

    /**
     * @Route("/{id}/remove_media", name="project_remove_media", methods={"GET"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @Template
     */
    public function removeMediaAction(Request $request, Project $project, EntityManagerInterface $em, MediaFileRepository $repo) {
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

    /**
     * @Route("/{id}/contributions", name="project_contributions", methods={"GET", "POST"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @Template
     */
    public function contributionsAction(Request $request, Project $project, EntityManagerInterface $em) {
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

    /**
     * @Route("/{id}/artworks", name="project_artworks", methods={"GET", "POST"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @Template
     */
    public function artworksAction(Request $request, Project $project, EntityManagerInterface $em) {
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
