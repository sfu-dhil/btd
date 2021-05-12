<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\VenueCategory;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * VenueCategory controller.
 *
 * @Route("/venue_category")
 */
class VenueCategoryController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all VenueCategory entities.
     *
     * @Route("/", name="venue_category_index", methods={"GET"})
     *
     * @Template
     */
    public function indexAction(Request $request, EntityManagerInterface $em) {
        $dql = 'SELECT e FROM App:VenueCategory e ORDER BY e.id';
        $query = $em->createQuery($dql);

        $venueCategories = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);

        return [
            'venueCategories' => $venueCategories,
        ];
    }

    /**
     * Creates a new VenueCategory entity.
     *
     * @Route("/new", name="venue_category_new", methods={"GET", "POST"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @Template
     */
    public function newAction(Request $request, EntityManagerInterface $em) {
        $venueCategory = new VenueCategory();
        $form = $this->createForm('App\Form\VenueCategoryType', $venueCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($venueCategory);
            $em->flush();

            $this->addFlash('success', 'The new venueCategory was created.');

            return $this->redirectToRoute('venue_category_show', ['id' => $venueCategory->getId()]);
        }

        return [
            'venueCategory' => $venueCategory,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a VenueCategory entity.
     *
     * @Route("/{id}", name="venue_category_show", methods={"GET"})
     *
     * @Template
     */
    public function showAction(VenueCategory $venueCategory) {
        return [
            'venueCategory' => $venueCategory,
        ];
    }

    /**
     * Displays a form to edit an existing VenueCategory entity.
     *
     * @Route("/{id}/edit", name="venue_category_edit", methods={"GET", "POST"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @Template
     */
    public function editAction(Request $request, VenueCategory $venueCategory, EntityManagerInterface $em) {
        $editForm = $this->createForm('App\Form\VenueCategoryType', $venueCategory);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The venueCategory has been updated.');

            return $this->redirectToRoute('venue_category_show', ['id' => $venueCategory->getId()]);
        }

        return [
            'venueCategory' => $venueCategory,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a VenueCategory entity.
     *
     * @Route("/{id}/delete", name="venue_category_delete", methods={"GET"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     */
    public function deleteAction(Request $request, VenueCategory $venueCategory, EntityManagerInterface $em) {
        $em->remove($venueCategory);
        $em->flush();
        $this->addFlash('success', 'The venueCategory was deleted.');

        return $this->redirectToRoute('venue_category_index');
    }
}
