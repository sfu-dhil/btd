<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

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

    public const CARET = ' ▾'; // U+25BE, black down-pointing small triangle.

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
     * @return ItemInterface
     */
    public function mainMenu(array $options) {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttributes([
            'class' => 'nav navbar-nav',
        ]);
        $menu->addChild('home', [
            'label' => 'Home',
            'route' => 'homepage',
        ]);

        $browse = $menu->addChild('browse', [
            'label' => 'Browse ' . self::CARET,
            'uri' => '#',
        ]);

        $browse->setAttribute('dropdown', true);
        $browse->setLinkAttribute('class', 'dropdown-toggle');
        $browse->setLinkAttribute('data-toggle', 'dropdown');
        $browse->setChildrenAttribute('class', 'dropdown-menu');

        $browse->addChild('artwork', [
            'label' => 'Artworks',
            'route' => 'artwork_index',
        ]);
        $browse->addChild('artistic_statement', [
            'label' => 'Artistic Statements',
            'route' => 'artwork_statement_index',
        ]);
        $browse->addChild('location', [
            'label' => 'Locations',
            'route' => 'location_index',
        ]);
        $browse->addChild('mediafile', [
            'label' => 'Media Files',
            'route' => 'media_file_index',
        ]);
        $browse->addChild('person', [
            'label' => 'People',
            'route' => 'person_index',
        ]);
        $browse->addChild('project', [
            'label' => 'Projects',
            'route' => 'project_index',
        ]);
        $browse->addChild('organization', [
            'label' => 'Organizations',
            'route' => 'organization_index',
        ]);
        $browse->addChild('venue', [
            'label' => 'Venues',
            'route' => 'venue_index',
        ]);

        if ($this->hasRole('ROLE_USER')) {
            $browse->addChild('divider', [
                'label' => '',
            ]);
            $browse['divider']->setAttributes([
                'role' => 'separator',
                'class' => 'divider',
            ]);
            $browse->addChild('artwork_contributions', [
                'label' => 'Artwork Contributions',
                'route' => 'artwork_contribution_index',
            ]);
            $browse->addChild('project_contributions', [
                'label' => 'Project Contributions',
                'route' => 'project_contribution_index',
            ]);
            $browse->addChild('divider2', [
                'label' => '',
            ]);
            $browse['divider2']->setAttributes([
                'role' => 'separator',
                'class' => 'divider',
            ]);
            $browse->addChild('artwork_category', [
                'label' => 'Artwork Categories',
                'route' => 'artwork_category_index',
            ]);
            $browse->addChild('artwork_role', [
                'label' => 'Artwork Roles',
                'route' => 'artwork_role_index',
            ]);
            $browse->addChild('mediafile_category', [
                'label' => 'Media File Categories',
                'route' => 'media_file_category_index',
            ]);
            $browse->addChild('project_role', [
                'label' => 'Project Roles',
                'route' => 'project_role_index',
            ]);
            $browse->addChild('project_category', [
                'label' => 'Project Categories',
                'route' => 'project_category_index',
            ]);
            $browse->addChild('venue_category', [
                'label' => 'Venue Categories',
                'route' => 'venue_category_index',
            ]);
        }

        return $menu;
    }
}
