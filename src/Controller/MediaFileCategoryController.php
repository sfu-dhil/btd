<?php

namespace App\Controller;

use App\Entity\MediaFileCategory;
use App\Form\MediaFileCategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * MediaFileCategory controller.
 *
 * @Route("/media_file_category")
 */
class MediaFileCategoryController extends AbstractController  implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all MediaFileCategory entities.
     *
     * @Route("/", name="media_file_category_index", methods={"GET"})
     *
     * @Template()
     *
     * @param Request $request
     */
    public function indexAction(Request $request, EntityManagerInterface $em) {
        $dql = 'SELECT e FROM App:MediaFileCategory e ORDER BY e.id';
        $query = $em->createQuery($dql);

        $mediaFileCategories = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'mediaFileCategories' => $mediaFileCategories,
        );
    }

    /**
     * Creates a new MediaFileCategory entity.
     *
     * @Route("/new", name="media_file_category_new", methods={"GET","POST"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @Template()
     *
     * @param Request $request
     */
    public function newAction(Request $request, EntityManagerInterface $em) {
        $mediaFileCategory = new MediaFileCategory();
        $form = $this->createForm(MediaFileCategoryType::class, $mediaFileCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
     * @Route("/{id}", name="media_file_category_show", methods={"GET"})
     *
     * @Template()
     *
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
     * @Route("/{id}/edit", name="media_file_category_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @Template()
     *
     * @param Request $request
     * @param MediaFileCategory $mediaFileCategory
     */
    public function editAction(Request $request, MediaFileCategory $mediaFileCategory, EntityManagerInterface $em) {
        $editForm = $this->createForm('App\Form\MediaFileCategoryType', $mediaFileCategory);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
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
     * @Route("/{id}/delete", name="media_file_category_delete", methods={"GET"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     *
     * @param Request $request
     * @param MediaFileCategory $mediaFileCategory
     */
    public function deleteAction(Request $request, MediaFileCategory $mediaFileCategory, EntityManagerInterface $em) {
        $em->remove($mediaFileCategory);
        $em->flush();
        $this->addFlash('success', 'The mediaFileCategory was deleted.');

        return $this->redirectToRoute('media_file_category_index');
    }
}
