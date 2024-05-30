<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Person;
use App\Form\Person\ArtworkContributionsType;
use App\Form\Person\PersonType;
use App\Form\Person\ProjectContributionsType;
use App\Repository\MediaFileRepository;
use App\Repository\PersonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Person controller.
 */
#[Route(path: '/person')]
class PersonController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all Person entities.
     */
    #[Route(path: '/', name: 'person_index', methods: ['GET'])]
    #[Template]
    public function index(Request $request, EntityManagerInterface $em) : array {
        $dql = 'SELECT e FROM App:Person e ORDER BY e.sortableName';
        $query = $em->createQuery($dql);

        $people = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return [
            'people' => $people,
        ];
    }

    #[Route(path: '/typeahead', name: 'person_typeahead', methods: ['GET'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    public function typeahead(Request $request, PersonRepository $repo) : JsonResponse {
        $q = $request->query->get('q');
        if ( ! $q) {
            return new JsonResponse([]);
        }
        $data = [];

        foreach ($repo->typeaheadQuery($q) as $result) {
            $data[] = [
                'id' => $result->getId(),
                'text' => (string) $result,
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * Search for Person entities.
     */
    #[Route(path: '/search', name: 'person_search', methods: ['GET'])]
    #[Template]
    public function search(Request $request, PersonRepository $repo) : array {
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->searchQuery($q);

            $people = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $people = [];
        }

        return [
            'people' => $people,
            'q' => $q,
        ];
    }

    /**
     * Full text search for Person entities.
     */
    #[Route(path: '/fulltext', name: 'person_fulltext', methods: ['GET'])]
    #[Template]
    public function fulltext(Request $request, PersonRepository $repo) : array {
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->fulltextQuery($q);

            $people = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $people = [];
        }

        return [
            'people' => $people,
            'q' => $q,
        ];
    }

    /**
     * Creates a new Person entity.
     */
    #[Route(path: '/new', name: 'person_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function new(Request $request, EntityManagerInterface $em) : array|RedirectResponse {
        $person = new Person();
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($person);
            $em->flush();

            $this->addFlash('success', 'The new person was created.');

            return $this->redirectToRoute('person_show', ['id' => $person->getId()]);
        }

        return [
            'person' => $person,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a Person entity.
     */
    #[Route(path: '/{id}', name: 'person_show', methods: ['GET'])]
    #[Template]
    public function show(Person $person) : ?array {
        return [
            'person' => $person,
        ];
    }

    /**
     * Displays a form to edit an existing Person entity.
     */
    #[Route(path: '/{id}/edit', name: 'person_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function edit(Request $request, Person $person, EntityManagerInterface $em) : array|RedirectResponse {
        $editForm = $this->createForm(PersonType::class, $person);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The person has been updated.');

            return $this->redirectToRoute('person_show', ['id' => $person->getId()]);
        }

        return [
            'person' => $person,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a Person entity.
     */
    #[Route(path: '/{id}/delete', name: 'person_delete', methods: ['GET'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    public function delete(Person $person, EntityManagerInterface $em) : RedirectResponse {
        $em->remove($person);
        $em->flush();
        $this->addFlash('success', 'The person was deleted.');

        return $this->redirectToRoute('person_index');
    }

    #[Route(path: '/{id}/add_media', name: 'person_add_media', methods: ['GET'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function addMedia(Request $request, Person $person, EntityManagerInterface $em, MediaFileRepository $repo) : array|RedirectResponse {
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->searchQuery($q);

            $results = $this->paginator->paginate($query, $request->query->getInt('page', 1), 5);
        } else {
            $results = [];
        }

        $addId = $request->query->get('addId');
        if ($addId) {
            $mediaFile = $repo->find($addId);
            if ( ! $person->hasMediaFile($mediaFile)) {
                $person->addMediaFile($mediaFile);
                $mediaFile->addPerson($person);
                $em->flush();
            }
            $this->addFlash('success', 'The media file is associated with the person.');

            return $this->redirectToRoute('person_add_media', [
                'id' => $person->getId(),
                'q' => $q,
                'page' => $request->query->getInt('page', 1),
            ]);
        }

        return [
            'person' => $person,
            'q' => $q,
            'results' => $results,
        ];
    }

    #[Route(path: '/{id}/remove_media', name: 'person_remove_media', methods: ['GET'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function removeMedia(Request $request, Person $person, EntityManagerInterface $em, MediaFileRepository $repo) : array|RedirectResponse {
        $results = $this->paginator->paginate($person->getMediaFiles(), $request->query->getInt('page', 1), 25);

        $removeId = $request->query->get('removeId');
        if ($removeId) {
            $mediaFile = $repo->find($removeId);
            if ($person->hasMediaFile($mediaFile)) {
                $person->removeMediaFile($mediaFile);
                $mediaFile->removePerson($person);
                $em->flush();
            }
            $this->addFlash('success', 'The media file is no longer associated with the person.');

            return $this->redirectToRoute('person_remove_media', [
                'id' => $person->getId(),
                'page' => $request->query->getInt('page', 1),
            ]);
        }

        return [
            'person' => $person,
            'results' => $results,
        ];
    }

    #[Route(path: '/{id}/project_contributions', name: 'person_project_contributions', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function projectContributions(Request $request, Person $person, EntityManagerInterface $em) : array|RedirectResponse {
        $form = $this->createForm(ProjectContributionsType::class, $person, [
            'person' => $person,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The contributions have been updated.');

            return $this->redirectToRoute('person_show', ['id' => $person->getId()]);
        }

        return [
            'person' => $person,
            'edit_form' => $form->createView(),
        ];
    }

    #[Route(path: '/{id}/artwork_contributions', name: 'person_artwork_contributions', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Template]
    public function artworkContributions(Request $request, Person $person, EntityManagerInterface $em) : array|RedirectResponse {
        $form = $this->createForm(ArtworkContributionsType::class, $person, [
            'person' => $person,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The contributions have been updated.');

            return $this->redirectToRoute('person_show', ['id' => $person->getId()]);
        }

        return [
            'person' => $person,
            'edit_form' => $form->createView(),
        ];
    }
}
