<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Artwork;
use AppBundle\Form\Artwork\ArtworkType;
use AppBundle\Form\Artwork\ProjectsType;
use AppBundle\Form\ArtworkContributionsType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Artwork controller.
 *
 * @Route("/artwork")
 */
class ArtworkController extends Controller {

    /**
     * Lists all Artwork entities.
     *
     * @Route("/", name="artwork_index")
     * @Method("GET")
     * @Template()
     * @param Request $request
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:Artwork e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $artworks = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'artworks' => $artworks,
        );
    }

    /**
     * Full text search for Artwork entities.
     *
     * @Route("/search", name="artwork_search")
     * @Method("GET")
     * @Template()
     * @param Request $request
     * @return array
     */
    public function searchAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Artwork');
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->fulltextQuery($q);
            $paginator = $this->get('knp_paginator');
            $artworks = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $artworks = array();
        }

        return array(
            'artworks' => $artworks,
            'q' => $q,
        );
    }

    /**
     * Creates a new Artwork entity.
     *
     * @Route("/new", name="artwork_new")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     */
    public function newAction(Request $request) {
        $artwork = new Artwork();
        $form = $this->createForm(ArtworkType::class, $artwork);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($artwork);
            $em->flush();

            $this->addFlash('success', 'The new artwork was created.');
            return $this->redirectToRoute('artwork_show', array('id' => $artwork->getId()));
        }

        return array(
            'artwork' => $artwork,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Artwork entity.
     *
     * @Route("/{id}", name="artwork_show")
     * @Method("GET")
     * @Template()
     * @param Artwork $artwork
     */
    public function showAction(Artwork $artwork) {

        return array(
            'artwork' => $artwork,
        );
    }

    /**
     * Displays a form to edit an existing Artwork entity.
     *
     * @Route("/{id}/edit", name="artwork_edit")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     * @param Artwork $artwork
     */
    public function editAction(Request $request, Artwork $artwork) {
        $editForm = $this->createForm(ArtworkType::class, $artwork);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The artwork has been updated.');
            return $this->redirectToRoute('artwork_show', array('id' => $artwork->getId()));
        }

        return array(
            'artwork' => $artwork,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Artwork entity.
     *
     * @Route("/{id}/delete", name="artwork_delete")
     * @Method("GET")
     * @param Request $request
     * @param Artwork $artwork
     */
    public function deleteAction(Request $request, Artwork $artwork) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($artwork);
        $em->flush();
        $this->addFlash('success', 'The artwork was deleted.');

        return $this->redirectToRoute('artwork_index');
    }

    /**
     * @Route("/{id}/add_media", name="artwork_add_media")
     * @Method("GET")
     * @Template()
     * 
     * @param Request $request
     * @param Artwork $artwork
     */
    public function addMediaAction(Request $request, Artwork $artwork) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:MediaFile');
        $q = $request->query->get('q');
        $paginator = $this->get('knp_paginator');
        if ($q) {
            $query = $repo->searchQuery($q);
            $results = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $results = $paginator->paginate($repo->findAll(), $request->query->getInt('page', 1), 25);
        }

        $addId = $request->query->get('addId');
        if ($addId) {
            $mediaFile = $repo->find($addId);
            if (!$artwork->hasMediaFile($mediaFile)) {
                $artwork->addMediaFile($mediaFile);
                $mediaFile->addArtwork($artwork);
                $em->flush();
            }
            $this->addFlash('success', 'The media file is associated with the artowrk.');
            return $this->redirectToRoute('artwork_add_media', array(
                        'id' => $artwork->getId(),
                        'q' => $q,
                        'page' => $request->query->getInt('page', 1)
            ));
        }

        return array(
            'artwork' => $artwork,
            'q' => $q,
            'results' => $results,
        );
    }

    /**
     * @Route("/{id}/remove_media", name="artwork_remove_media")
     * @Method("GET")
     * @Template()
     * 
     * @param Request $request
     * @param Artwork $artwork
     */
    public function removeMediaAction(Request $request, Artwork $artwork) {
        $paginator = $this->get('knp_paginator');
        $results = $paginator->paginate($artwork->getMediaFiles(), $request->query->getInt('page', 1), 25);

        $removeId = $request->query->get('removeId');
        if ($removeId) {
            $em = $this->getDoctrine()->getManager();
            $repo = $em->getRepository('AppBundle:MediaFile');
            $mediaFile = $repo->find($removeId);
            if ($artwork->hasMediaFile($mediaFile)) {
                $artwork->removeMediaFile($mediaFile);
                $mediaFile->removeArtwork($artwork);
                $em->flush();
            }
            $this->addFlash('success', 'The media file is associated with the artowrk.');
            return $this->redirectToRoute('artwork_remove_media', array(
                'id' => $artwork->getId(),
                'page' => $request->query->getInt('page', 1)
            ));
        }

        return array(
            'artwork' => $artwork,
            'results' => $results,
        );
    }
    
    /**
     * @Route("/{id}/contributions", name="artwork_contributions")
     * @Method({"GET", "POST"})
     * @Template()
     * 
     * @param Request $request
     * @param Artwork $artwork
     */
    public function contributionsAction(Request $request, Artwork $artwork) {
        $form = $this->createForm(ArtworkContributionsType::class, $artwork, array(
            'artwork' => $artwork,
        ));
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The contributions have been updated.');
            return $this->redirectToRoute('artwork_show', array('id' => $artwork->getId()));
        }
        
        return array(
            'artwork' => $artwork,
            'edit_form' => $form->createView(),
        );
    }
    
    /**
     * @Route("/{id}/projects", name="artwork_projects")
     * @Method({"GET", "POST"})
     * @Template()
     * 
     * @param Request $request
     * @param Artwork $artwork
     */
    public function projectsAction(Request $request, Artwork $artwork) {
        $oldProjects = $artwork->getProjects()->toArray();
        $form = $this->createForm(ProjectsType::class, $artwork);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            foreach($oldProjects as $project) {
                dump($project);
                $project->removeArtwork($artwork);
            }
            foreach($artwork->getProjects() as $project) {
                if( ! $project->hasArtwork($artwork)) {
                    $project->addArtwork($artwork);
                }
            }
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The projects have been updated.');
            // return $this->redirectToRoute('artwork_show', array('id' => $artwork->getId()));
        }
        
        return array(
            'artwork' => $artwork,
            'edit_form' => $form->createView(),
        );
    }
}
