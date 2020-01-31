<?php

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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Person controller.
 *
 * @Route("/person")
 */
class PersonController extends AbstractController  implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all Person entities.
     *
     * @Route("/", name="person_index", methods={"GET"})
     *
     * @Template()
     *
     * @param Request $request
     */
    public function indexAction(Request $request, EntityManagerInterface $em) {
        $dql = 'SELECT e FROM App:Person e ORDER BY e.sortableName';
        $query = $em->createQuery($dql);

        $people = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'people' => $people,
        );
    }

    /**
     * @param Request $request
     * @Route("/typeahead", name="person_typeahead", methods={"GET"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     *
     * @return JsonResponse
     */
    public function typeaheadAction(Request $request, PersonRepository $repo) {
        $q = $request->query->get('q');
        if ( ! $q) {
            return new JsonResponse(array());
        }
        $data = array();
        foreach ($repo->typeaheadQuery($q) as $result) {
            $data[] = array(
                'id' => $result->getId(),
                'text' => (string) $result,
            );
        }

        return new JsonResponse($data);
    }

    /**
     * Search for Person entities.
     *
     * @Route("/search", name="person_search", methods={"GET"})
     *
     * @Template()
     *
     * @param Request $request
     */
    public function searchAction(Request $request, PersonRepository $repo) {
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->searchQuery($q);

            $people = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $people = array();
        }

        return array(
            'people' => $people,
            'q' => $q,
        );
    }

    /**
     * Full text search for Person entities.
     *
     * @Route("/fulltext", name="person_fulltext", methods={"GET"})
     *
     * @Template()
     *
     * @param Request $request
     *
     * @return array
     */
    public function fulltextAction(Request $request, PersonRepository $repo) {
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->fulltextQuery($q);

            $people = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $people = array();
        }

        return array(
            'people' => $people,
            'q' => $q,
        );
    }

    /**
     * Creates a new Person entity.
     *
     * @Route("/new", name="person_new", methods={"GET","POST"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @Template()
     *
     * @param Request $request
     */
    public function newAction(Request $request, EntityManagerInterface $em) {
        $person = new Person();
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($person);
            $em->flush();

            $this->addFlash('success', 'The new person was created.');

            return $this->redirectToRoute('person_show', array('id' => $person->getId()));
        }

        return array(
            'person' => $person,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Person entity.
     *
     * @Route("/{id}", name="person_show", methods={"GET"})
     *
     * @Template()
     *
     * @param Person $person
     */
    public function showAction(Person $person) {
        return array(
            'person' => $person,
        );
    }

    /**
     * Displays a form to edit an existing Person entity.
     *
     * @Route("/{id}/edit", name="person_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @Template()
     *
     * @param Request $request
     * @param Person $person
     */
    public function editAction(Request $request, Person $person, EntityManagerInterface $em) {
        $editForm = $this->createForm(PersonType::class, $person);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The person has been updated.');

            return $this->redirectToRoute('person_show', array('id' => $person->getId()));
        }

        return array(
            'person' => $person,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Person entity.
     *
     * @Route("/{id}/delete", name="person_delete", methods={"GET"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     *
     * @param Request $request
     * @param Person $person
     */
    public function deleteAction(Request $request, Person $person, EntityManagerInterface $em) {
        $em->remove($person);
        $em->flush();
        $this->addFlash('success', 'The person was deleted.');

        return $this->redirectToRoute('person_index');
    }

    /**
     * @Route("/{id}/add_media", name="person_add_media", methods={"GET"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @Template()
     *
     * @param Request $request
     * @param Person $person
     */
    public function addMediaAction(Request $request, Person $person, EntityManagerInterface $em, MediaFileRepository $repo) {
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->searchQuery($q);

            $results = $this->paginator->paginate($query, $request->query->getInt('page', 1), 5);
        } else {
            $results = array();
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

            return $this->redirectToRoute('person_add_media', array(
                'id' => $person->getId(),
                'q' => $q,
                'page' => $request->query->getInt('page', 1),
            ));
        }

        return array(
            'person' => $person,
            'q' => $q,
            'results' => $results,
        );
    }

    /**
     * @Route("/{id}/remove_media", name="person_remove_media", methods={"GET"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @Template()
     *
     * @param Request $request
     * @param Person $person
     */
    public function removeMediaAction(Request $request, Person $person, EntityManagerInterface $em, MediaFileRepository $repo) {

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

            return $this->redirectToRoute('person_remove_media', array(
                'id' => $person->getId(),
                'page' => $request->query->getInt('page', 1),
            ));
        }

        return array(
            'person' => $person,
            'results' => $results,
        );
    }

    /**
     * @Route("/{id}/project_contributions", name="person_project_contributions", methods={"GET","POST"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @Template()
     *
     * @param Request $request
     * @param Person $person
     */
    public function projectContributionsAction(Request $request, Person $person, EntityManagerInterface $em) {
        $form = $this->createForm(ProjectContributionsType::class, $person, array(
            'person' => $person,
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The contributions have been updated.');

            return $this->redirectToRoute('person_show', array('id' => $person->getId()));
        }

        return array(
            'person' => $person,
            'edit_form' => $form->createView(),
        );
    }

    /**
     * @Route("/{id}/artwork_contributions", name="person_artwork_contributions", methods={"GET","POST"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @Template()
     *
     * @param Request $request
     * @param Person $person
     */
    public function artworkContributionsAction(Request $request, Person $person, EntityManagerInterface $em) {
        $form = $this->createForm(ArtworkContributionsType::class, $person, array(
            'person' => $person,
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The contributions have been updated.');

            return $this->redirectToRoute('person_show', array('id' => $person->getId()));
        }

        return array(
            'person' => $person,
            'edit_form' => $form->createView(),
        );
    }
}
