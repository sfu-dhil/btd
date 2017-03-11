<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\MediaFileType;
use AppBundle\Form\MediaFileTypeType;

/**
 * MediaFileType controller.
 *
 * @Route("/media_file_type")
 */
class MediaFileTypeController extends Controller {

    /**
     * Lists all MediaFileType entities.
     *
     * @Route("/", name="media_file_type_index")
     * @Method("GET")
     * @Template()
     * @param Request $request
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:MediaFileType e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $mediaFileTypes = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'mediaFileTypes' => $mediaFileTypes,
        );
    }

    /**
     * Search for MediaFileType entities.
     *
     * To make this work, add a method like this one to the 
     * AppBundle:MediaFileType repository. Replace the fieldName with
     * something appropriate, and adjust the generated search.html.twig
     * template.
     * 
      //    public function searchQuery($q) {
      //        $qb = $this->createQueryBuilder('e');
      //        $qb->where("e.fieldName like '%$q%'");
      //        return $qb->getQuery();
      //    }
     *
     *
     * @Route("/search", name="media_file_type_search")
     * @Method("GET")
     * @Template()
     * @param Request $request
     */
    public function searchAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:MediaFileType');
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->searchQuery($q);
            $paginator = $this->get('knp_paginator');
            $mediaFileTypes = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $mediaFileTypes = array();
        }

        return array(
            'mediaFileTypes' => $mediaFileTypes,
            'q' => $q,
        );
    }

    /**
     * Full text search for MediaFileType entities.
     *
     * To make this work, add a method like this one to the 
     * AppBundle:MediaFileType repository. Replace the fieldName with
     * something appropriate, and adjust the generated fulltext.html.twig
     * template.
     * 
      //    public function fulltextQuery($q) {
      //        $qb = $this->createQueryBuilder('e');
      //        $qb->addSelect("MATCH_AGAINST (e.name, :q 'IN BOOLEAN MODE') as score");
      //        $qb->add('where', "MATCH_AGAINST (e.name, :q 'IN BOOLEAN MODE') > 0.5");
      //        $qb->orderBy('score', 'desc');
      //        $qb->setParameter('q', $q);
      //        return $qb->getQuery();
      //    }
     * 
     * Requires a MatchAgainst function be added to doctrine, and appropriate
     * fulltext indexes on your MediaFileType entity.
     *     ORM\Index(name="alias_name_idx",columns="name", flags={"fulltext"})
     *
     *
     * @Route("/fulltext", name="media_file_type_fulltext")
     * @Method("GET")
     * @Template()
     * @param Request $request
     * @return array
     */
    public function fulltextAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:MediaFileType');
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->fulltextQuery($q);
            $paginator = $this->get('knp_paginator');
            $mediaFileTypes = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $mediaFileTypes = array();
        }

        return array(
            'mediaFileTypes' => $mediaFileTypes,
            'q' => $q,
        );
    }

    /**
     * Creates a new MediaFileType entity.
     *
     * @Route("/new", name="media_file_type_new")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     */
    public function newAction(Request $request) {
        $mediaFileType = new MediaFileType();
        $form = $this->createForm('AppBundle\Form\MediaFileTypeType', $mediaFileType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($mediaFileType);
            $em->flush();

            $this->addFlash('success', 'The new mediaFileType was created.');
            return $this->redirectToRoute('media_file_type_show', array('id' => $mediaFileType->getId()));
        }

        return array(
            'mediaFileType' => $mediaFileType,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a MediaFileType entity.
     *
     * @Route("/{id}", name="media_file_type_show")
     * @Method("GET")
     * @Template()
     * @param MediaFileType $mediaFileType
     */
    public function showAction(MediaFileType $mediaFileType) {

        return array(
            'mediaFileType' => $mediaFileType,
        );
    }

    /**
     * Displays a form to edit an existing MediaFileType entity.
     *
     * @Route("/{id}/edit", name="media_file_type_edit")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     * @param MediaFileType $mediaFileType
     */
    public function editAction(Request $request, MediaFileType $mediaFileType) {
        $editForm = $this->createForm('AppBundle\Form\MediaFileTypeType', $mediaFileType);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The mediaFileType has been updated.');
            return $this->redirectToRoute('media_file_type_show', array('id' => $mediaFileType->getId()));
        }

        return array(
            'mediaFileType' => $mediaFileType,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a MediaFileType entity.
     *
     * @Route("/{id}/delete", name="media_file_type_delete")
     * @Method("GET")
     * @param Request $request
     * @param MediaFileType $mediaFileType
     */
    public function deleteAction(Request $request, MediaFileType $mediaFileType) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($mediaFileType);
        $em->flush();
        $this->addFlash('success', 'The mediaFileType was deleted.');

        return $this->redirectToRoute('media_file_type_index');
    }

}
