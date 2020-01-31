<?php

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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * ProjectPage controller.
 */
class ProjectPageController extends AbstractController  implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all ProjectPage entities.
     *
     * @Route("/project/{projectId}/page", name="project_page_index", methods={"GET"})
     * @ParamConverter("project", class="App:Project", options={"id": "projectId"})
     *
     * @Template()
     *
     * @param Request $request
     * @param Project $project
     */
    public function indexAction(Request $request, $project, EntityManagerInterface $em) {
        $dql = 'SELECT e FROM App:ProjectPage e WHERE e.project = :project ORDER BY e.id';
        $query = $em->createQuery($dql);
        $query->setParameter('project', $project);

        $projectPages = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'project' => $project,
            'projectPages' => $projectPages,
        );
    }

    /**
     * Creates a new ProjectPage entity.
     *
     * @Route("/project/{projectId}/page/new", name="project_page_new", methods={"GET","POST"})
     * @ParamConverter("project", class="App:Project", options={"id": "projectId"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @Template()
     *
     * @param Request $request
     * @param Project $project
     */
    public function newAction(Request $request, Project $project, EntityManagerInterface $em) {
        $projectPage = new ProjectPage();
        $projectPage->setProject($project);
        $form = $this->createForm(ProjectPageType::class, $projectPage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($projectPage);
            $em->flush();

            $this->addFlash('success', 'The new projectPage was created.');

            return $this->redirectToRoute('project_page_show', array('projectId' => $project->getId(), 'id' => $projectPage->getId()));
        }

        return array(
            'project' => $project,
            'projectPage' => $projectPage,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a ProjectPage entity.
     *
     * @Route("/project/{projectId}/page/{id}", name="project_page_show", methods={"GET"})
     * @ParamConverter("project", class="App:Project", options={"id": "projectId"})
     *
     * @Template()
     *
     * @param Project $project
     * @param ProjectPage $projectPage
     */
    public function showAction(Project $project, ProjectPage $projectPage) {
        if ($project->getId() !== $projectPage->getProject()->getId()) {
            throw new NotFoundHttpException();
        }

        return array(
            'project' => $project,
            'projectPage' => $projectPage,
        );
    }

    /**
     * Displays a form to edit an existing ProjectPage entity.
     *
     * @Route("/project/{projectId}/page/{id}/edit", name="project_page_edit", methods={"GET","POST"})
     * @ParamConverter("project", class="App:Project", options={"id": "projectId"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @Template()
     *
     * @param Request $request
     * @param ProjectPage $projectPage
     * @param Project $project
     */
    public function editAction(Request $request, Project $project, ProjectPage $projectPage, EntityManagerInterface $em) {
        if ($project->getId() !== $projectPage->getProject()->getId()) {
            throw new NotFoundHttpException();
        }
        $editForm = $this->createForm(ProjectPageType::class, $projectPage);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The projectPage has been updated.');

            return $this->redirectToRoute('project_page_show', array(
                'projectId' => $project->getId(),
                'id' => $projectPage->getId(),
            ));
        }

        return array(
            'project' => $project,
            'projectPage' => $projectPage,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a ProjectPage entity.
     *
     * @Route("/project/{projectId}/page/{id}/delete", name="project_page_delete", methods={"GET"})
     * @ParamConverter("project", class="App:Project", options={"id": "projectId"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     *
     * @param Request $request
     * @param ProjectPage $projectPage
     * @param Project $project
     */
    public function deleteAction(Request $request, Project $project, ProjectPage $projectPage, EntityManagerInterface $em) {
        $em->remove($projectPage);
        $em->flush();
        $this->addFlash('success', 'The projectPage was deleted.');

        return $this->redirectToRoute('project_page_index', array(
            'projectId' => $project->getId(),
        ));
    }
}
