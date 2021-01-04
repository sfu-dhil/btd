<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\MediaFile;
use App\Entity\MediaFileField;
use App\Form\MediaFileMetadataType;
use App\Form\MediaFileType;
use App\Repository\MediaFileRepository;
use App\Services\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\DublinCoreBundle\Repository\ElementRepository;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * MediaFile controller.
 *
 * @Route("/media_file")
 */
class MediaFileController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all MediaFile entities.
     *
     * @Route("/", name="media_file_index", methods={"GET"})
     *
     * @Template
     */
    public function indexAction(Request $request, EntityManagerInterface $em) {
        $dql = 'SELECT e FROM App:MediaFile e ORDER BY e.id';
        $query = $em->createQuery($dql);

        $mediaFiles = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);

        return [
            'mediaFiles' => $mediaFiles,
        ];
    }

    /**
     * Search for MediaFile entities.
     *
     * To make this work, add a method like this one to the
     * App:MediaFile repository. Replace the fieldName with
     * something appropriate, and adjust the generated search.html.twig
     * template.
     *
     * @Route("/search", name="media_file_search", methods={"GET"})
     *
     * @Template
     */
    public function searchAction(Request $request, MediaFileRepository $repo) {
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->searchQuery($q);

            $results = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $results = [];
        }

        return [
            'results' => $results,
            'q' => $q,
        ];
    }

    /**
     * Full text search for MediaFile entities.
     *
     * @Route("/fulltext", name="media_file_fulltext", methods={"GET"})
     *
     * @Template
     *
     * @return array
     */
    public function fulltextAction(Request $request, MediaFileRepository $repo) {
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->fulltextQuery($q);

            $mediaFiles = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $mediaFiles = [];
        }

        return [
            'mediaFiles' => $mediaFiles,
            'q' => $q,
        ];
    }

    /**
     * Creates a new MediaFile entity.
     *
     * @Route("/new", name="media_file_new", methods={"GET", "POST"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @Template
     */
    public function newAction(Request $request, EntityManagerInterface $em, ElementRepository $repo) {
        if ( ! $this->isGranted('ROLE_CONTENT_ADMIN')) {
            $this->addFlash('danger', 'You must login to access this page.');

            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $mediaFile = new MediaFile();
        $form = $this->createForm('App\Form\MediaFileType', $mediaFile, [
            'max_file_upload' => UploadedFile::getMaxFilesize(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mediaFile->setOriginalName($mediaFile->getFile()->getClientOriginalName());
            $em->persist($mediaFile);

            $mediaFileField = new MediaFileField();
            $mediaFileField->setElement($repo->findOneBy(['name' => 'dc_identifier']));
            $mediaFileField->setValue($mediaFile->getOriginalName());
            $mediaFileField->setMediaFile($mediaFile);
            $em->persist($mediaFileField);

            $em->flush();
            $this->addFlash('success', 'The new mediaFile was created');

            return $this->redirectToRoute('media_file_show', ['id' => $mediaFile->getId()]);
        }

        return [
            'mediaFile' => $mediaFile,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a MediaFile entity.
     *
     * @Route("/{id}", name="media_file_show", methods={"GET"})
     *
     * @Template
     */
    public function showAction(MediaFile $mediaFile, ElementRepository $repo) {
        return [
            'elements' => $repo->findAll(),
            'mediaFile' => $mediaFile,
        ];
    }

    /**
     * Finds and displays a media file.
     *
     * @Route("/{id}/view", name="media_file_raw", methods={"GET"})
     */
    public function mediaAction(MediaFile $mediaFile) {
        return new BinaryFileResponse($mediaFile->getFile());
    }

    /**
     * Finds and displays a media file.
     *
     * @Route("/{id}/tn", name="media_file_tn", methods={"GET"})
     */
    public function thumbnailAction(MediaFile $mediaFile) {
        $tn = $mediaFile->getThumbnail();
        if ($tn) {
            return new BinaryFileResponse($tn);
        }

        throw new NotFoundHttpException('Cannot find thumbnail.');
    }

    /**
     * Displays a form to edit an existing MediaFile entity.
     *
     * @Route("/{id}/edit", name="media_file_edit", methods={"GET", "POST"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @Template
     */
    public function editAction(Request $request, MediaFile $mediaFile, FileUploader $uploader, EntityManagerInterface $em, ElementRepository $repo) {
        if ( ! $this->isGranted('ROLE_CONTENT_ADMIN')) {
            $this->addFlash('danger', 'You must login to access this page.');

            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $max = $uploader->getMaxUploadSize();
        $editForm = $this->createForm(MediaFileType::class, $mediaFile, [
            'max_file_upload' => $max,
        ]);
        $editForm->remove('file');
        $editForm->add('newFile', FileType::class, [
            'label' => 'New File',
            'required' => false,
            'attr' => [
                'help_block' => "Select a file to replace the current one. Optional. Maximum file upload size is {$max}.",
            ],
            'mapped' => false,
        ]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            if (($upload = $editForm->get('newFile')->getData())) {
                $mediaFile->setFile($upload);
                $mediaFile->setOriginalName($mediaFile->getFile()->getClientOriginalName());
                $mediaFile->preUpdate();

                $mediaFileField = new MediaFileField();
                $mediaFileField->setElement($repo->findOneBy(['name' => 'dc_identifier']));
                $mediaFileField->setValue($mediaFile->getOriginalName());
                $mediaFileField->setMediaFile($mediaFile);
                $em->persist($mediaFileField);
            }
            $em->flush();
            $this->addFlash('success', 'The mediaFile has been updated.');

            return $this->redirectToRoute('media_file_show', ['id' => $mediaFile->getId()]);
        }

        return [
            'mediaFile' => $mediaFile,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a MediaFile entity.
     *
     * @Route("/{id}/delete", name="media_file_delete", methods={"GET"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     */
    public function deleteAction(Request $request, MediaFile $mediaFile, FileUploader $uploader, EntityManagerInterface $em) {
        if ( ! $this->isGranted('ROLE_CONTENT_ADMIN')) {
            $this->addFlash('danger', 'You must login to access this page.');

            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        if ($mediaFile->getFile()) {
            $uploader->delete($mediaFile->getFile());
        }
        if ($mediaFile->getThumbnail()) {
            $uploader->delete($mediaFile->getThumbnail());
        }

        foreach ($mediaFile->getMetadataFields() as $field) {
            $em->remove($field);
        }
        $em->remove($mediaFile);
        $em->flush();
        $this->addFlash('success', 'The mediaFile was deleted.');

        return $this->redirectToRoute('media_file_index');
    }

    /**
     * Edit metadata for a media file.
     *
     * @Route("/{id}/metadata", name="media_file_metadata", methods={"GET", "POST"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @Template
     */
    public function metadataAction(Request $request, MediaFile $mediaFile, EntityManagerInterface $em, ElementRepository $repo) {
        if ( ! $this->isGranted('ROLE_CONTENT_ADMIN')) {
            $this->addFlash('danger', 'You must login to access this page.');

            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $form = $this->createForm(MediaFileMetadataType::class, null, [
            'mediaFile' => $mediaFile,
            'entityManager' => $em,
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($mediaFile->getMetadataFields() as $field) {
                $em->remove($field);
            }

            foreach ($form->getData() as $name => $values) {
                $element = $repo->findOneBy(['name' => $name]);

                foreach ($values as $value) {
                    $field = new MediaFileField();
                    $field->setElement($element);
                    $field->setMediaFile($mediaFile);
                    $field->setValue($value);
                    $em->persist($field);
                }
            }
            $em->flush();
            $this->addFlash('success', 'The metadata has been updated.');

            return $this->redirectToRoute('media_file_show', ['id' => $mediaFile->getId()]);
        }

        return [
            'edit_form' => $form->createView(),
            'mediaFile' => $mediaFile,
        ];
    }
}
