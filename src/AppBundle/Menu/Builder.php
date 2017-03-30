<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Class to build some menus for navigation.
 */
class Builder implements ContainerAwareInterface {

    use ContainerAwareTrait;

    /**
     * Build a menu for blog posts.
     * 
     * @param FactoryInterface $factory
     * @param array $options
     * @return ItemInterface
     */
    public function navMenu(FactoryInterface $factory, array $options) {
        $em = $this->container->get('doctrine')->getManager();

        $menu = $factory->createItem('root');
        $menu->setChildrenAttributes(array(
            'class' => 'dropdown-menu',
        ));
        $menu->setAttribute('dropdown', true);

        $menu->addChild('artwork', array(
            'label' => 'Artworks',
            'route' => 'artwork_index',
        ));
        $menu->addChild('location', array(
            'label' => 'Locations',
            'route' => 'location_index',
        ));
        $menu->addChild('mediafile', array(
            'label' => 'Media Files',
            'route' => 'media_file_index',
        ));
        $menu->addChild('person', array(
            'label' => 'People',
            'route' => 'person_index',
        ));
        $menu->addChild('project', array(
            'label' => 'Projects',
            'route' => 'project_index',
        ));
        $menu->addChild('organization', array(
            'label' => 'Organizations',
            'route' => 'organization_index',
        ));        
        $menu->addChild('venue', array(
            'label' => 'Venues',
            'route' => 'venue_index',
        ));

        if ($this->container->get('security.token_storage')->getToken() && $this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $menu->addChild('divider', array(
                'label' => '',
            ));
            $menu['divider']->setAttributes(array(
                'role' => 'separator',
                'class' => 'divider',
            ));
            $menu->addChild('artwork_contributions', array(
                'label' => 'Artwork Contributions',
                'route' => 'artwork_contribution_index',
            ));
            $menu->addChild('project_contributions', array(
                'label' => 'Project Contributions',
                'route' => 'project_contribution_index',
            ));
            $menu->addChild('divider2', array(
                'label' => '',
            ));
            $menu['divider2']->setAttributes(array(
                'role' => 'separator',
                'class' => 'divider',
            ));
            $menu->addChild('artwork_category', array(
                'label' => 'Artwork Categories',
                'route' => 'artwork_category_index',
            ));
            $menu->addChild('artwork_role', array(
                'label' => 'Artwork Roles',
                'route' => 'artwork_role_index',
            ));
            $menu->addChild('mediafile_category', array(
                'label' => 'Media File Categories',
                'route' => 'media_file_category_index',
            ));
            $menu->addChild('project_role', array(
                'label' => 'Project Roles',
                'route' => 'project_role_index',
            ));
            $menu->addChild('project_category', array(
                'label' => 'Project Categories',
                'route' => 'project_category_index',
            ));
            $menu->addChild('venue_category', array(
                'label' => 'Venue Categories',
                'route' => 'venue_category_index',
            ));
        }

        return $menu;
    }

}
