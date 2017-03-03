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
            'route' => 'mediafile_index',
        ));
        $menu->addChild('person', array(
            'label' => 'People',
            'route' => 'person_index',
        ));
        $menu->addChild('project', array(
            'label' => 'Projects',
            'route' => 'project_index',
        ));
        $menu->addChild('venue', array(
            'label' => 'Venues',
            'route' => 'venue_index',
        ));

        if ($this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $menu->addChild('divider', array(
                'label' => '',
            ));
            $menu['divider']->setAttributes(array(
                'role' => 'separator',
                'class' => 'divider',
            ));
            
        }

        return $menu;
    }

}
