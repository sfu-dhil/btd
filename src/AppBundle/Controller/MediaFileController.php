<?php

namespace AppBundle\Controller;

use AppBundle\Entity\MediaFile;
use AppBundle\Entity\MediaFileField;
use AppBundle\Form\MediaFileMetadataType;
use Nines\DublinCoreBundle\Entity\Element;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * MediaFile controller.
 *
 * @Route("/media_file")
 */
class MediaFileController extends Controller {

    /**
     * Lists all MediaFile entities.
     *
     * @Route("/", name="media_file_index")
     * @Method("GET")
     * @Template()
     * @param Request $request
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:MediaFile e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $mediaFiles = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'mediaFiles' => $mediaFiles,
        );
    }

    /**
     * Search for MediaFile entities.
     *
     * To make this work, add a method like this one to the 
     * AppBundle:MediaFile repository. Replace the fieldName with
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
     * @Route("/search", name="media_file_search")
     * @Method("GET")
     * @Template()
     * @param Request $request
     */
    public function searchAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:MediaFile');
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->searchQuery($q);
            $paginator = $this->get('knp_paginator');
            $results = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $results = array();
        }

        return array(
            'results' => $results,
            'q' => $q,
        );
    }

    /**
     * Full text search for MediaFile entities.
     *
     * To make this work, add a method like this one to the 
     * AppBundle:MediaFile repository. Replace the fieldName with
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
     * fulltext indexes on your MediaFile entity.
     *     ORM\Index(name="alias_name_idx",columns="name", flags={"fulltext"})
     *
     *
     * @Route("/fulltext", name="media_file_fulltext")
     * @Method("GET")
     * @Template()
     * @param Request $request
     * @return array
     */
    public function fulltextAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:MediaFile');
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->fulltextQuery($q);
            $paginator = $this->get('knp_paginator');
            $mediaFiles = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $mediaFiles = array();
        }

        return array(
            'mediaFiles' => $mediaFiles,
            'q' => $q,
        );
    }
    
    /**
     * Creates a new MediaFile entity.
     *
     * @Route("/new", name="media_file_new")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     */
    public function newAction(Request $request) {
        $mediaFile = new MediaFile();
        $form = $this->createForm('AppBundle\Form\MediaFileType', $mediaFile, array(
            'max_file_upload' => UploadedFile::getMaxFilesize()
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();                    
            $mediaFile->setOriginalName($mediaFile->getFile()->getClientOriginalName());
            
            $mediaFileField = new MediaFileField();
            $mediaFileField->setElement($em->getRepository(Element::class)->findOneBy(array('name' => 'dc_identifier')));
            $mediaFileField->setValue($mediaFile->getOriginalName());
            $em->persist($mediaFileField);
            $em->persist($mediaFile);
            $em->flush();

            $this->addFlash('success', 'The new mediaFile was created');
            return $this->redirectToRoute('media_file_show', array('id' => $mediaFile->getId()));
        }

        return array(
            'mediaFile' => $mediaFile,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a MediaFile entity.
     *
     * @Route("/{id}", name="media_file_show")
     * @Method("GET")
     * @Template()
     * @param MediaFile $mediaFile
     */
    public function showAction(MediaFile $mediaFile) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Element::class);
        return array(
            'elements' => $repo->findAll(),
            'mediaFile' => $mediaFile,
        );
    }
    
    /**
     * Finds and displays a media file.
     *
     * @Route("/{id}/view", name="media_file_raw")
     * @Method("GET")
     * @param MediaFile $mediaFile
     */
    public function mediaAction(MediaFile $mediaFile) {
        return new BinaryFileResponse($mediaFile->getFile());
    }
    
    /**
     * Finds and displays a media file.
     *
     * @Route("/{id}/tn", name="media_file_tn")
     * @Method("GET")
     * @param MediaFile $mediaFile
     */
    public function thumbnailAction(MediaFile $mediaFile) {
        $tn = $mediaFile->getThumbnail();
        if($tn) {
            return new BinaryFileResponse($tn);
        }
        dump($tn);
        throw new NotFoundHttpException("Cannot find thumbnail.");
    }

    /**
     * Displays a form to edit an existing MediaFile entity.
     *
     * @Route("/{id}/edit", name="media_file_edit")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     * @param MediaFile $mediaFile
     */
    public function editAction(Request $request, MediaFile $mediaFile) {
        $oldFile = $mediaFile->getFile();
        $tnFile = $mediaFile->getThumbnail();
        $editForm = $this->createForm('AppBundle\Form\MediaFileType', $mediaFile, array(
            'max_file_upload' => UploadedFile::getMaxFilesize()
        ));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->get('app.file_uploader')->delete($oldFile);
            $this->get('app.file_uploader')->delete($tnFile);
            $mediaFile->setOriginalName($mediaFile->getFile()->getClientOriginalName());
            
            $em = $this->getDoctrine()->getManager();            
            $mediaFileField = new MediaFileField();
            $mediaFileField->setElement($em->getRepository(Element::class)->findOneBy(array('name' => 'dc_identifier')));
            $mediaFileField->setValue($mediaFile->getOriginalName());
            
            $em->flush();
            $this->addFlash('success', 'The mediaFile has been updated.');
            return $this->redirectToRoute('media_file_show', array('id' => $mediaFile->getId()));
        }

        return array(
            'mediaFile' => $mediaFile,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a MediaFile entity.
     *
     * @Route("/{id}/delete", name="media_file_delete")
     * @Method("GET")
     * @param Request $request
     * @param MediaFile $mediaFile
     */
    public function deleteAction(Request $request, MediaFile $mediaFile) {
        $em = $this->getDoctrine()->getManager();
        $this->get('app.file_uploader')->delete($mediaFile->getFile());
        $this->get('app.file_uploader')->delete($mediaFile->getThumbnail());
        $em->remove($mediaFile);
        $em->flush();
        $this->addFlash('success', 'The mediaFile was deleted.');

        return $this->redirectToRoute('media_file_index');
    }
    
    /**
     * Edit metadata for a media file.
     *
     * @Route("/{id}/metadata", name="media_file_metadata")
     * @Method({"GET","POST"})
     * @Template()
     * @param Request $request
     * @param MediaFile $mediaFile
     */
    public function metadataAction(Request $request, MediaFile $mediaFile) {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(MediaFileMetadataType::class, null, array(
            'mediaFile' => $mediaFile, 
            'entityManager' => $em
        ));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $repo = $em->getRepository(Element::class);
            foreach($mediaFile->getMetadataFields() as $field) {
                $em->remove($field);
            }
            foreach($form->getData() as $name => $values) {
                $element = $repo->findOneBy(array('name' => $name));
                foreach($values as $value) {
                    $field = new MediaFileField();
                    $field->setElement($element);
                    $field->setMediaFile($mediaFile);
                    $field->setValue($value);
                    $em->persist($field);
                }
            }
            $em->flush();
            $this->addFlash('success', 'The metadata has been updated.');
            return $this->redirectToRoute('media_file_show', array('id' => $mediaFile->getId()));
        }
        
        return array(
            'edit_form' => $form->createView(),
            'mediaFile' => $mediaFile,            
        );
    }
    
}
