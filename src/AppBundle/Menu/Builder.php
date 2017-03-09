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
        $menu = $factory->createItem('root');
        $menu->setChildrenAttributes(array(
            'class' => 'dropdown-menu',
        ));
        $menu->setAttribute('dropdown', true);

        $menu->addChild('mediafile', array(
            'label' => 'Media Files',
            'route' => 'media_file_index',
        ));

        return $menu;
    }

}
