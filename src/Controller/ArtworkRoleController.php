<?php

namespace App\Controller;

use App\Entity\ArtworkRole;
use App\Form\Artwork\ArtworkRoleType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * ArtworkRole controller.
 *
 * @Route("/artwork_role")
 */
class ArtworkRoleController extends AbstractController  implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all ArtworkRole entities.
     *
     * @Route("/", name="artwork_role_index", methods={"GET"})
     *
     * @Template()
     *
     * @param Request $request
     */
    public function indexAction(Request $request, EntityManagerInterface $em) {
        $dql = 'SELECT e FROM App:ArtworkRole e ORDER BY e.id';
        $query = $em->createQuery($dql);

        $artworkRoles = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'artworkRoles' => $artworkRoles,
        );
    }

    /**
     * Creates a new ArtworkRole entity.
     *
     * @Route("/new", name="artwork_role_new", methods={"GET","POST"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @Template()
     *
     * @param Request $request
     */
    public function newAction(Request $request, EntityManagerInterface $em) {
        $artworkRole = new ArtworkRole();
        $form = $this->createForm(ArtworkRoleType::class, $artworkRole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($artworkRole);
            $em->flush();

            $this->addFlash('success', 'The new artworkRole was created.');

            return $this->redirectToRoute('artwork_role_show', array('id' => $artworkRole->getId()));
        }

        return array(
            'artworkRole' => $artworkRole,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a ArtworkRole entity.
     *
     * @Route("/{id}", name="artwork_role_show", methods={"GET"})
     *
     * @Template()
     *
     * @param ArtworkRole $artworkRole
     */
    public function showAction(ArtworkRole $artworkRole) {
        return array(
            'artworkRole' => $artworkRole,
        );
    }

    /**
     * Displays a form to edit an existing ArtworkRole entity.
     *
     * @Route("/{id}/edit", name="artwork_role_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @Template()
     *
     * @param Request $request
     * @param ArtworkRole $artworkRole
     */
    public function editAction(Request $request, ArtworkRole $artworkRole, EntityManagerInterface $em) {
        $editForm = $this->createForm(ArtworkRoleType::class, $artworkRole);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The artworkRole has been updated.');

            return $this->redirectToRoute('artwork_role_show', array('id' => $artworkRole->getId()));
        }

        return array(
            'artworkRole' => $artworkRole,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a ArtworkRole entity.
     *
     * @Route("/{id}/delete", name="artwork_role_delete", methods={"GET"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     *
     * @param Request $request
     * @param ArtworkRole $artworkRole
     */
    public function deleteAction(Request $request, ArtworkRole $artworkRole, EntityManagerInterface $em) {
        $em->remove($artworkRole);
        $em->flush();
        $this->addFlash('success', 'The artworkRole was deleted.');

        return $this->redirectToRoute('artwork_role_index');
    }
}
