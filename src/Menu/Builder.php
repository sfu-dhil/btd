<?php

namespace App\Menu;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class to build some menus for navigation.
 */
class Builder implements ContainerAwareInterface {
    use ContainerAwareTrait;

    const CARET = ' ▾'; // U+25BE, black down-pointing small triangle.

    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authChecker;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(FactoryInterface $factory, AuthorizationCheckerInterface $authChecker, TokenStorageInterface $tokenStorage, EntityManagerInterface $em) {
        $this->factory = $factory;
        $this->authChecker = $authChecker;
        $this->tokenStorage = $tokenStorage;
        $this->em = $em;
    }

    private function hasRole($role) {
        if ( ! $this->tokenStorage->getToken()) {
            return false;
        }

        return $this->authChecker->isGranted($role);
    }

    /**
     * Build a menu for blog posts.
     *
     * @param array $options
     *
     * @return ItemInterface
     */
    public function mainMenu(array $options) {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttributes(array(
            'class' => 'nav navbar-nav',
        ));
        $menu->addChild('home', array(
            'label' => 'Home',
            'route' => 'homepage',
        ));

        $browse = $menu->addChild('browse', array(
            'label' => 'Browse ' . self::CARET,
            'uri' => '#',
        ));

        $browse->setAttribute('dropdown', true);
        $browse->setLinkAttribute('class', 'dropdown-toggle');
        $browse->setLinkAttribute('data-toggle', 'dropdown');
        $browse->setChildrenAttribute('class', 'dropdown-menu');

        $browse->addChild('artwork', array(
            'label' => 'Artworks',
            'route' => 'artwork_index',
        ));
        $browse->addChild('artistic_statement', array(
            'label' => 'Artistic Statements',
            'route' => 'artwork_statement_index',
        ));
        $browse->addChild('location', array(
            'label' => 'Locations',
            'route' => 'location_index',
        ));
        $browse->addChild('mediafile', array(
            'label' => 'Media Files',
            'route' => 'media_file_index',
        ));
        $browse->addChild('person', array(
            'label' => 'People',
            'route' => 'person_index',
        ));
        $browse->addChild('project', array(
            'label' => 'Projects',
            'route' => 'project_index',
        ));
        $browse->addChild('organization', array(
            'label' => 'Organizations',
            'route' => 'organization_index',
        ));
        $browse->addChild('venue', array(
            'label' => 'Venues',
            'route' => 'venue_index',
        ));

        if ($this->hasRole('ROLE_USER')) {
            $browse->addChild('divider', array(
                'label' => '',
            ));
            $browse['divider']->setAttributes(array(
                'role' => 'separator',
                'class' => 'divider',
            ));
            $browse->addChild('artwork_contributions', array(
                'label' => 'Artwork Contributions',
                'route' => 'artwork_contribution_index',
            ));
            $browse->addChild('project_contributions', array(
                'label' => 'Project Contributions',
                'route' => 'project_contribution_index',
            ));
            $browse->addChild('divider2', array(
                'label' => '',
            ));
            $browse['divider2']->setAttributes(array(
                'role' => 'separator',
                'class' => 'divider',
            ));
            $browse->addChild('artwork_category', array(
                'label' => 'Artwork Categories',
                'route' => 'artwork_category_index',
            ));
            $browse->addChild('artwork_role', array(
                'label' => 'Artwork Roles',
                'route' => 'artwork_role_index',
            ));
            $browse->addChild('mediafile_category', array(
                'label' => 'Media File Categories',
                'route' => 'media_file_category_index',
            ));
            $browse->addChild('project_role', array(
                'label' => 'Project Roles',
                'route' => 'project_role_index',
            ));
            $browse->addChild('project_category', array(
                'label' => 'Project Categories',
                'route' => 'project_category_index',
            ));
            $browse->addChild('venue_category', array(
                'label' => 'Venue Categories',
                'route' => 'venue_category_index',
            ));
        }

        return $menu;
    }
}
