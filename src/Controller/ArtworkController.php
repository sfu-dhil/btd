<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\Artwork;
use App\Form\Artwork\ArtworkContributionsType;
use App\Form\Artwork\ArtworkType;
use App\Form\Artwork\ProjectsType;
use App\Repository\ArtworkRepository;
use App\Repository\MediaFileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Artwork controller.
 *
 * @Route("/artwork")
 */
class ArtworkController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all Artwork entities.
     *
     * @Route("/", name="artwork_index", methods={"GET"})
     *
     * @Template
     */
    public function indexAction(Request $request, EntityManagerInterface $em) {
        $dql = 'SELECT e FROM App:Artwork e ORDER BY e.id';
        $query = $em->createQuery($dql);

        $artworks = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);

        return [
            'artworks' => $artworks,
        ];
    }

    /**
     * Full text search for Artwork entities.
     *
     * @Route("/search", name="artwork_search", methods={"GET"})
     *
     * @Template
     *
     * @return array
     */
    public function searchAction(Request $request, ArtworkRepository $repo) {
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->fulltextQuery($q);

            $artworks = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $artworks = [];
        }

        return [
            'artworks' => $artworks,
            'q' => $q,
        ];
    }

    /**
     * Creates a new Artwork entity.
     *
     * @Route("/new", name="artwork_new", methods={"GET", "POST"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @Template
     */
    public function newAction(Request $request, EntityManagerInterface $em) {
        $artwork = new Artwork();
        $form = $this->createForm(ArtworkType::class, $artwork);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($artwork);
            $em->flush();

            $this->addFlash('success', 'The new artwork was created.');

            return $this->redirectToRoute('artwork_show', ['id' => $artwork->getId()]);
        }

        return [
            'artwork' => $artwork,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a Artwork entity.
     *
     * @Route("/{id}", name="artwork_show", methods={"GET"})
     *
     * @Template
     */
    public function showAction(Artwork $artwork) {
        return [
            'artwork' => $artwork,
        ];
    }

    /**
     * Displays a form to edit an existing Artwork entity.
     *
     * @Route("/{id}/edit", name="artwork_edit", methods={"GET", "POST"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @Template
     */
    public function editAction(Request $request, Artwork $artwork, EntityManagerInterface $em) {
        $editForm = $this->createForm(ArtworkType::class, $artwork);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The artwork has been updated.');

            return $this->redirectToRoute('artwork_show', ['id' => $artwork->getId()]);
        }

        return [
            'artwork' => $artwork,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a Artwork entity.
     *
     * @Route("/{id}/delete", name="artwork_delete", methods={"GET"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     */
    public function deleteAction(Request $request, Artwork $artwork, EntityManagerInterface $em) {
        $em->remove($artwork);
        $em->flush();
        $this->addFlash('success', 'The artwork was deleted.');

        return $this->redirectToRoute('artwork_index');
    }

    /**
     * @Route("/{id}/add_media", name="artwork_add_media", methods={"GET"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @Template
     */
    public function addMediaAction(Request $request, Artwork $artwork, EntityManagerInterface $em, MediaFileRepository $repo) {
        $q = $request->query->get('q');

        if ($q) {
            $query = $repo->searchQuery($q);
            $results = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $results = $this->paginator->paginate($repo->findAll(), $request->query->getInt('page', 1), 25);
        }

        $addId = $request->query->get('addId');
        if ($addId) {
            $mediaFile = $repo->find($addId);
            if ( ! $artwork->hasMediaFile($mediaFile)) {
                $artwork->addMediaFile($mediaFile);
                $mediaFile->addArtwork($artwork);
                $em->flush();
            }
            $this->addFlash('success', 'The media file is associated with the artowrk.');

            return $this->redirectToRoute('artwork_add_media', [
                'id' => $artwork->getId(),
                'q' => $q,
                'page' => $request->query->getInt('page', 1),
            ]);
        }

        return [
            'artwork' => $artwork,
            'q' => $q,
            'results' => $results,
        ];
    }

    /**
     * @Route("/{id}/remove_media", name="artwork_remove_media", methods={"GET"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @Template
     */
    public function removeMediaAction(Request $request, Artwork $artwork, EntityManagerInterface $em, MediaFileRepository $repo) {
        $results = $this->paginator->paginate($artwork->getMediaFiles(), $request->query->getInt('page', 1), 25);

        $removeId = $request->query->get('removeId');
        if ($removeId) {
            $mediaFile = $repo->find($removeId);
            if ($artwork->hasMediaFile($mediaFile)) {
                $artwork->removeMediaFile($mediaFile);
                $mediaFile->removeArtwork($artwork);
                $em->flush();
            }
            $this->addFlash('success', 'The media file is associated with the artowrk.');

            return $this->redirectToRoute('artwork_remove_media', [
                'id' => $artwork->getId(),
                'page' => $request->query->getInt('page', 1),
            ]);
        }

        return [
            'artwork' => $artwork,
            'results' => $results,
        ];
    }

    /**
     * @Route("/{id}/contributions", name="artwork_contributions", methods={"GET", "POST"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @Template
     */
    public function contributionsAction(Request $request, Artwork $artwork, EntityManagerInterface $em) {
        $form = $this->createForm(ArtworkContributionsType::class, $artwork, [
            'artwork' => $artwork,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The contributions have been updated.');

            return $this->redirectToRoute('artwork_show', ['id' => $artwork->getId()]);
        }

        return [
            'artwork' => $artwork,
            'edit_form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}/projects", name="artwork_projects", methods={"GET", "POST"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @Template
     */
    public function projectsAction(Request $request, Artwork $artwork, EntityManagerInterface $em) {
        $oldProjects = $artwork->getProjects()->toArray();
        $form = $this->createForm(ProjectsType::class, $artwork);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($oldProjects as $project) {
                $project->removeArtwork($artwork);
            }

            foreach ($artwork->getProjects() as $project) {
                if ( ! $project->hasArtwork($artwork)) {
                    $project->addArtwork($artwork);
                }
            }
            $em->flush();
            $this->addFlash('success', 'The projects have been updated.');
            // return $this->redirectToRoute('artwork_show', array('id' => $artwork->getId()));
        }

        return [
            'artwork' => $artwork,
            'edit_form' => $form->createView(),
        ];
    }
}
