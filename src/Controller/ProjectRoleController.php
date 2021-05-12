<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\ProjectRole;
use App\Form\Project\ProjectRoleType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * ProjectRole controller.
 *
 * @Route("/project_role")
 */
class ProjectRoleController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all ProjectRole entities.
     *
     * @Route("/", name="project_role_index", methods={"GET"})
     *
     * @Template
     */
    public function indexAction(Request $request, EntityManagerInterface $em) {
        $dql = 'SELECT e FROM App:ProjectRole e ORDER BY e.id';
        $query = $em->createQuery($dql);

        $projectRoles = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);

        return [
            'projectRoles' => $projectRoles,
        ];
    }

    /**
     * Creates a new ProjectRole entity.
     *
     * @Route("/new", name="project_role_new", methods={"GET", "POST"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @Template
     */
    public function newAction(Request $request, EntityManagerInterface $em) {
        $projectRole = new ProjectRole();
        $form = $this->createForm(ProjectRoleType::class, $projectRole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($projectRole);
            $em->flush();

            $this->addFlash('success', 'The new projectRole was created.');

            return $this->redirectToRoute('project_role_show', ['id' => $projectRole->getId()]);
        }

        return [
            'projectRole' => $projectRole,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a ProjectRole entity.
     *
     * @Route("/{id}", name="project_role_show", methods={"GET"})
     *
     * @Template
     */
    public function showAction(ProjectRole $projectRole) {
        return [
            'projectRole' => $projectRole,
        ];
    }

    /**
     * Displays a form to edit an existing ProjectRole entity.
     *
     * @Route("/{id}/edit", name="project_role_edit", methods={"GET", "POST"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @Template
     */
    public function editAction(Request $request, ProjectRole $projectRole, EntityManagerInterface $em) {
        $editForm = $this->createForm(ProjectRoleType::class, $projectRole);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The projectRole has been updated.');

            return $this->redirectToRoute('project_role_show', ['id' => $projectRole->getId()]);
        }

        return [
            'projectRole' => $projectRole,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a ProjectRole entity.
     *
     * @Route("/{id}/delete", name="project_role_delete", methods={"GET"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     */
    public function deleteAction(Request $request, ProjectRole $projectRole, EntityManagerInterface $em) {
        $em->remove($projectRole);
        $em->flush();
        $this->addFlash('success', 'The projectRole was deleted.');

        return $this->redirectToRoute('project_role_index');
    }
}
