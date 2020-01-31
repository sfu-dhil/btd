<?php

namespace App\Controller;

use App\Entity\Location;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Location controller.
 *
 * @Route("/location")
 */
class LocationController extends AbstractController  implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all Location entities.
     *
     * @Route("/", name="location_index", methods={"GET"})
     *
     * @Template()
     *
     * @param Request $request
     */
    public function indexAction(Request $request, EntityManagerInterface $em) {
        $dql = 'SELECT e FROM App:Location e ORDER BY e.id';
        $query = $em->createQuery($dql);

        $locations = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'locations' => $locations,
        );
    }

    /**
     * Full text search for Location entities.
     *
     * @Route("/search", name="location_search", methods={"GET"})
     *
     * @Template()
     *
     * @param Request $request
     *
     * @return array
     */
    public function searchAction(Request $request, LocationRepository $repo) {
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->fulltextQuery($q);

            $locations = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $locations = array();
        }

        return array(
            'locations' => $locations,
            'q' => $q,
        );
    }

    /**
     * Creates a new Location entity.
     *
     * @Route("/new", name="location_new", methods={"GET","POST"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @Template()
     *
     * @param Request $request
     */
    public function newAction(Request $request, EntityManagerInterface $em) {
        $location = new Location();
        $form = $this->createForm('App\Form\LocationType', $location);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($location);
            $em->flush();

            $this->addFlash('success', 'The new location was created.');

            return $this->redirectToRoute('location_show', array('id' => $location->getId()));
        }

        return array(
            'location' => $location,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Location entity.
     *
     * @Route("/{id}", name="location_show", methods={"GET"})
     *
     * @Template()
     *
     * @param Location $location
     */
    public function showAction(Location $location) {
        return array(
            'location' => $location,
        );
    }

    /**
     * Displays a form to edit an existing Location entity.
     *
     * @Route("/{id}/edit", name="location_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @Template()
     *
     * @param Request $request
     * @param Location $location
     */
    public function editAction(Request $request, Location $location, EntityManagerInterface $em) {
        $editForm = $this->createForm('App\Form\LocationType', $location);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The location has been updated.');

            return $this->redirectToRoute('location_show', array('id' => $location->getId()));
        }

        return array(
            'location' => $location,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Location entity.
     *
     * @Route("/{id}/delete", name="location_delete", methods={"GET"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     *
     * @param Request $request
     * @param Location $location
     */
    public function deleteAction(Request $request, Location $location, EntityManagerInterface $em) {
        $em->remove($location);
        $em->flush();
        $this->addFlash('success', 'The location was deleted.');

        return $this->redirectToRoute('location_index');
    }
}
