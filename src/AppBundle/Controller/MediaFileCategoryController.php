<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\MediaFileCategory;
use AppBundle\Form\MediaFileCategoryType;

/**
 * MediaFileCategory controller.
 *
 * @Route("/media_file_category")
 */
class MediaFileCategoryController extends Controller {

    /**
     * Lists all MediaFileCategory entities.
     *
     * @Route("/", name="media_file_category_index")
     * @Method("GET")
     * @Template()
     * @param Request $request
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:MediaFileCategory e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $mediaFileCategories = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'mediaFileCategories' => $mediaFileCategories,
        );
    }

    /**
     * Creates a new MediaFileCategory entity.
     *
     * @Route("/new", name="media_file_category_new")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     */
    public function newAction(Request $request) {
        $mediaFileCategory = new MediaFileCategory();
        $form = $this->createForm('AppBundle\Form\MediaFileTypeType', $mediaFileType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($mediaFileCategory);
            $em->flush();

            $this->addFlash('success', 'The new mediaFileCategory was created.');
            return $this->redirectToRoute('media_file_category_show', array('id' => $mediaFileCategory->getId()));
        }

        return array(
            'mediaFileCategory' => $mediaFileCategory,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a MediaFileCategory entity.
     *
     * @Route("/{id}", name="media_file_category_show")
     * @Method("GET")
     * @Template()
     * @param MediaFileCategory $mediaFileCategory
     */
    public function showAction(MediaFileCategory $mediaFileCategory) {

        return array(
            'mediaFileCategory' => $mediaFileCategory,
        );
    }

    /**
     * Displays a form to edit an existing MediaFileCategory entity.
     *
     * @Route("/{id}/edit", name="media_file_category_edit")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     * @param MediaFileCategory $mediaFileCategory
     */
    public function editAction(Request $request, MediaFileCategory $mediaFileCategory) {
        $editForm = $this->createForm('AppBundle\Form\MediaFileCategoryType', $mediaFileCategory);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The mediaFileCategory has been updated.');
            return $this->redirectToRoute('media_file_category_show', array('id' => $mediaFileCategory->getId()));
        }

        return array(
            'mediaFileCategory' => $mediaFileCategory,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a MediaFileCategory entity.
     *
     * @Route("/{id}/delete", name="media_file_category_delete")
     * @Method("GET")
     * @param Request $request
     * @param MediaFileCategory $mediaFileCategory
     */
    public function deleteAction(Request $request, MediaFileCategory $mediaFileCategory) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($mediaFileCategory);
        $em->flush();
        $this->addFlash('success', 'The mediaFileCategory was deleted.');

        return $this->redirectToRoute('media_file_category_index');
    }

}
