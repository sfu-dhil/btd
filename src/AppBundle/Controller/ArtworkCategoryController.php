<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\ArtworkCategory;
use AppBundle\Form\ArtworkCategoryType;

/**
 * ArtworkCategory controller.
 *
 * @Route("/artwork_category")
 */
class ArtworkCategoryController extends Controller
{
    /**
     * Lists all ArtworkCategory entities.
     *
     * @Route("/", name="artwork_category_index")
     * @Method("GET")
     * @Template()
	 * @param Request $request
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:ArtworkCategory e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $artworkCategories = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'artworkCategories' => $artworkCategories,
        );
    }
    
    /**
     * Creates a new ArtworkCategory entity.
     *
     * @Route("/new", name="artwork_category_new")
     * @Method({"GET", "POST"})
     * @Template()
	 * @param Request $request
     */
    public function newAction(Request $request)
    {
        if( ! $this->isGranted('ROLE_CONTENT_ADMIN')) {
            $this->addFlash('danger', 'You must login to access this page.');
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $artworkCategory = new ArtworkCategory();
        $form = $this->createForm(ArtworkCategoryType::class, $artworkCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($artworkCategory);
            $em->flush();

            $this->addFlash('success', 'The new artworkCategory was created.');
            return $this->redirectToRoute('artwork_category_show', array('id' => $artworkCategory->getId()));
        }

        return array(
            'artworkCategory' => $artworkCategory,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a ArtworkCategory entity.
     *
     * @Route("/{id}", name="artwork_category_show")
     * @Method("GET")
     * @Template()
	 * @param ArtworkCategory $artworkCategory
     */
    public function showAction(ArtworkCategory $artworkCategory)
    {

        return array(
            'artworkCategory' => $artworkCategory,
        );
    }

    /**
     * Displays a form to edit an existing ArtworkCategory entity.
     *
     * @Route("/{id}/edit", name="artwork_category_edit")
     * @Method({"GET", "POST"})
     * @Template()
	 * @param Request $request
	 * @param ArtworkCategory $artworkCategory
     */
    public function editAction(Request $request, ArtworkCategory $artworkCategory)
    {
        if( ! $this->isGranted('ROLE_CONTENT_ADMIN')) {
            $this->addFlash('danger', 'You must login to access this page.');
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $editForm = $this->createForm(ArtworkCategoryType::class, $artworkCategory);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The artworkCategory has been updated.');
            return $this->redirectToRoute('artwork_category_show', array('id' => $artworkCategory->getId()));
        }

        return array(
            'artworkCategory' => $artworkCategory,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a ArtworkCategory entity.
     *
     * @Route("/{id}/delete", name="artwork_category_delete")
     * @Method("GET")
	 * @param Request $request
	 * @param ArtworkCategory $artworkCategory
     */
    public function deleteAction(Request $request, ArtworkCategory $artworkCategory)
    {
        if( ! $this->isGranted('ROLE_CONTENT_ADMIN')) {
            $this->addFlash('danger', 'You must login to access this page.');
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($artworkCategory);
        $em->flush();
        $this->addFlash('success', 'The artworkCategory was deleted.');

        return $this->redirectToRoute('artwork_category_index');
    }
}
