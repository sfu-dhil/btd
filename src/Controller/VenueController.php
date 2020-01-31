<?php

namespace App\Controller;

use App\Entity\Venue;
use App\Repository\VenueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Venue controller.
 *
 * @Route("/venue")
 */
class VenueController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all Venue entities.
     *
     * @Route("/", name="venue_index", methods={"GET"})
     *
     * @Template()
     *
     * @param Request $request
     */
    public function indexAction(Request $request, EntityManagerInterface $em) {
        $dql = 'SELECT e FROM App:Venue e ORDER BY e.id';
        $query = $em->createQuery($dql);

        $venues = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'venues' => $venues,
        );
    }

    /**
     * @param Request $request
     * @Security("is_granted('ROLE_CONTENT_ADMIN')")
     * @Route("/typeahead", name="venue_typeahead", methods={"GET"})
     *
     *
     * @return JsonResponse
     */
    public function typeaheadAction(Request $request, VenueRepository $repo) {
        $q = $request->query->get('q');
        if ( ! $q) {
            return new JsonResponse(array());
        }
        $data = array();
        foreach ($repo->typeaheadQuery($q) as $result) {
            $data[] = array(
                'id' => $result->getId(),
                'text' => $result->getName(),
            );
        }

        return new JsonResponse($data);
    }

    /**
     * Creates a new Venue entity.
     *
     * @Route("/new", name="venue_new", methods={"GET","POST"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @Template()
     *
     * @param Request $request
     */
    public function newAction(Request $request, EntityManagerInterface $em) {
        $venue = new Venue();
        $form = $this->createForm('App\Form\VenueType', $venue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($venue);
            $em->flush();

            $this->addFlash('success', 'The new venue was created.');

            return $this->redirectToRoute('venue_show', array('id' => $venue->getId()));
        }

        return array(
            'venue' => $venue,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Venue entity.
     *
     * @Route("/{id}", name="venue_show", methods={"GET"})
     *
     * @Template()
     *
     * @param Venue $venue
     */
    public function showAction(Venue $venue) {
        return array(
            'venue' => $venue,
        );
    }

    /**
     * Displays a form to edit an existing Venue entity.
     *
     * @Route("/{id}/edit", name="venue_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @Template()
     *
     * @param Request $request
     * @param Venue $venue
     */
    public function editAction(Request $request, Venue $venue, EntityManagerInterface $em) {
        $editForm = $this->createForm('App\Form\VenueType', $venue);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The venue has been updated.');

            return $this->redirectToRoute('venue_show', array('id' => $venue->getId()));
        }

        return array(
            'venue' => $venue,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Venue entity.
     *
     * @Route("/{id}/delete", name="venue_delete", methods={"GET"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     *
     * @param Request $request
     * @param Venue $venue
     */
    public function deleteAction(Request $request, Venue $venue, EntityManagerInterface $em) {
        $em->remove($venue);
        $em->flush();
        $this->addFlash('success', 'The venue was deleted.');

        return $this->redirectToRoute('venue_index');
    }
}
