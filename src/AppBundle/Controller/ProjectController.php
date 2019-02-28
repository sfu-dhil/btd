<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Project;
use AppBundle\Form\Project\ArtworksType;
use AppBundle\Form\Project\ContributionsType;
use AppBundle\Form\Project\ProjectType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Project controller.
 *
 * @Route("/project")
 */
class ProjectController extends Controller {

    /**
     * Lists all Project entities.
     *
     * @Route("/", name="project_index")
     * @Method("GET")
     * @Template()
     * @param Request $request
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:Project e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $projects = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'projects' => $projects,
        );
    }

   /**
     * @param Request $request
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * @Route("/typeahead", name="project_typeahead")
     * @Method("GET")
     * @return JsonResponse
     */
    public function typeaheadAction(Request $request) {
        $q = $request->query->get('q');
        if (!$q) {
            return new JsonResponse([]);
        }
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Project::class);
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
     * @Route("/fulltext", name="project_search")
     * @Method("GET")
     * @Template()
     * @param Request $request
     * @return array
     */
    public function searchAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Project');
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->fulltextQuery($q);
            $paginator = $this->get('knp_paginator');
            $projects = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $projects = array();
        }

        return array(
            'projects' => $projects,
            'q' => $q,
        );
    }

    /**
     * Creates a new Project entity.
     *
     * @Route("/new", name="project_new")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     */
    public function newAction(Request $request, \Nines\UtilBundle\Services\Text $text) {
        if( ! $this->isGranted('ROLE_CONTENT_ADMIN')) {
            $this->addFlash('danger', 'You must login to access this page.');
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();

            $this->addFlash('success', 'The new project was created.');
            return $this->redirectToRoute('project_show', array('id' => $project->getId()));
        }

        return array(
            'project' => $project,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Project entity.
     *
     * @Route("/{id}", name="project_show")
     * @Method("GET")
     * @Template()
     * @param Project $project
     */
    public function showAction(Project $project) {

        return array(
            'project' => $project,
        );
    }

    /**
     * Displays a form to edit an existing Project entity.
     *
     * @Route("/{id}/edit", name="project_edit")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     * @param Project $project
     */
    public function editAction(Request $request, Project $project, \Nines\UtilBundle\Services\Text $text) {
        if( ! $this->isGranted('ROLE_CONTENT_ADMIN')) {
            $this->addFlash('danger', 'You must login to access this page.');
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $editForm = $this->createForm(ProjectType::class, $project);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The project has been updated.');
            return $this->redirectToRoute('project_show', array('id' => $project->getId()));
        }

        return array(
            'project' => $project,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Project entity.
     *
     * @Route("/{id}/delete", name="project_delete")
     * @Method("GET")
     * @param Request $request
     * @param Project $project
     */
    public function deleteAction(Request $request, Project $project) {
        if( ! $this->isGranted('ROLE_CONTENT_ADMIN')) {
            $this->addFlash('danger', 'You must login to access this page.');
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($project);
        $em->flush();
        $this->addFlash('success', 'The project was deleted.');

        return $this->redirectToRoute('project_index');
    }

    /**
     * @Route("/{id}/add_media", name="project_add_media")
     * @Method("GET")
     * @Template()
     * 
     * @param Request $request
     * @param Project $project
     */
    public function addMediaAction(Request $request, Project $project) {
        if( ! $this->isGranted('ROLE_CONTENT_ADMIN')) {
            $this->addFlash('danger', 'You must login to access this page.');
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:MediaFile');
        $q = $request->query->get('q');
        $paginator = $this->get('knp_paginator');
        if ($q) {
            $query = $repo->searchQuery($q);
            $results = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $query = $repo->findAll();
            $results = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
        }

        $addId = $request->query->get('addId');
        if ($addId) {
            $mediaFile = $repo->find($addId);
            if (!$project->hasMediaFile($mediaFile)) {
                $project->addMediaFile($mediaFile);
                $mediaFile->addProject($project);
                $em->flush();
            }
            $this->addFlash('success', 'The media file is associated with the artowrk.');
            return $this->redirectToRoute('project_add_media', array(
                        'id' => $project->getId(),
                        'q' => $q,
                        'page' => $request->query->getInt('page', 1)
            ));
        }

        return array(
            'project' => $project,
            'q' => $q,
            'results' => $results,
        );
    }

    /**
     * @Route("/{id}/remove_media", name="project_remove_media")
     * @Method("GET")
     * @Template()
     * 
     * @param Request $request
     * @param Project $project
     */
    public function removeMediaAction(Request $request, Project $project) {
        if( ! $this->isGranted('ROLE_CONTENT_ADMIN')) {
            $this->addFlash('danger', 'You must login to access this page.');
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $paginator = $this->get('knp_paginator');
        $results = $paginator->paginate($project->getMediaFiles(), $request->query->getInt('page', 1), 25);

        $removeId = $request->query->get('removeId');
        if ($removeId) {
            $em = $this->getDoctrine()->getManager();
            $repo = $em->getRepository('AppBundle:MediaFile');
            $mediaFile = $repo->find($removeId);
            if ($project->hasMediaFile($mediaFile)) {
                $project->removeMediaFile($mediaFile);
                $mediaFile->removeProject($project);
                $em->flush();
            }
            $this->addFlash('success', 'The media file is associated with the artowrk.');
            return $this->redirectToRoute('project_remove_media', array(
                        'id' => $project->getId(),
                        'page' => $request->query->getInt('page', 1)
            ));
        }

        return array(
            'project' => $project,
            'results' => $results,
        );
    }

    /**
     * @Route("/{id}/contributions", name="project_contributions")
     * @Method({"GET", "POST"})
     * @Template()
     * 
     * @param Request $request
     * @param Project $project
     */
    public function contributionsAction(Request $request, Project $project) {
        if( ! $this->isGranted('ROLE_CONTENT_ADMIN')) {
            $this->addFlash('danger', 'You must login to access this page.');
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $form = $this->createForm(ContributionsType::class, $project, array(
            'project' => $project,
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The contributions have been updated.');
            // return $this->redirectToRoute('project_show', array('id' => $project->getId()));
        }

        return array(
            'project' => $project,
            'edit_form' => $form->createView(),
        );
    }

    /**
     * @Route("/{id}/artworks", name="project_artworks")
     * @Method({"GET", "POST"})
     * @Template()
     * 
     * @param Request $request
     * @param Project $project
     */
    public function artworksAction(Request $request, Project $project) {
        if( ! $this->isGranted('ROLE_CONTENT_ADMIN')) {
            $this->addFlash('danger', 'You must login to access this page.');
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $form = $this->createForm(ArtworksType::class, $project, array(
            'project' => $project,
        ));
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The artworks have been updated.');
            return $this->redirectToRoute('project_show', array('id' => $project->getId()));
        }
        
        return array(
            'project' => $project,
            'edit_form' => $form->createView(),
        );
    }
}
