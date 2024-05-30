<?php

declare(strict_types=1);

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

    public function __construct(private FactoryInterface $factory, private AuthorizationCheckerInterface $authChecker, private TokenStorageInterface $tokenStorage, private EntityManagerInterface $em) {}

    private function hasRole($role) {
        if ( ! $this->tokenStorage->getToken()) {
            return false;
        }

        return $this->authChecker->isGranted($role);
    }

    public function mainMenu(array $options) : ItemInterface {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttributes([
            'class' => 'nav navbar-nav',
        ]);
        $menu->addChild('home', [
            'label' => 'Home',
            'route' => 'homepage',
            'attributes' => [
                'class' => 'nav-item',
            ],
            'linkAttributes' => [
                'class' => 'nav-link',
            ],
        ]);

        $browse = $menu->addChild('Browse', [
            'uri' => '#',
            'label' => 'Browse',
            'attributes' => [
                'class' => 'nav-item dropdown',
            ],
            'linkAttributes' => [
                'class' => 'nav-link dropdown-toggle',
                'role' => 'button',
                'data-bs-toggle' => 'dropdown',
                'id' => 'browse-dropdown',
            ],
            'childrenAttributes' => [
                'class' => 'dropdown-menu text-small shadow',
                'aria-labelledby' => 'browse-dropdown',
            ],
        ]);

        $browse->addChild('artwork', [
            'label' => 'Artworks',
            'route' => 'artwork_index',
            'linkAttributes' => [
                'class' => 'dropdown-item link-dark',
            ],
        ]);
        $browse->addChild('artistic_statement', [
            'label' => 'Artistic Statements',
            'route' => 'artwork_statement_index',
            'linkAttributes' => [
                'class' => 'dropdown-item link-dark',
            ],
        ]);
        $browse->addChild('location', [
            'label' => 'Locations',
            'route' => 'location_index',
            'linkAttributes' => [
                'class' => 'dropdown-item link-dark',
            ],
        ]);
        $browse->addChild('mediafile', [
            'label' => 'Media Files',
            'route' => 'media_file_index',
            'linkAttributes' => [
                'class' => 'dropdown-item link-dark',
            ],
        ]);
        $browse->addChild('person', [
            'label' => 'People',
            'route' => 'person_index',
            'linkAttributes' => [
                'class' => 'dropdown-item link-dark',
            ],
        ]);
        $browse->addChild('project', [
            'label' => 'Projects',
            'route' => 'project_index',
            'linkAttributes' => [
                'class' => 'dropdown-item link-dark',
            ],
        ]);
        $browse->addChild('organization', [
            'label' => 'Organizations',
            'route' => 'organization_index',
            'linkAttributes' => [
                'class' => 'dropdown-item link-dark',
            ],
        ]);
        $browse->addChild('venue', [
            'label' => 'Venues',
            'route' => 'venue_index',
            'linkAttributes' => [
                'class' => 'dropdown-item link-dark',
            ],
        ]);

        if ($this->hasRole('ROLE_USER')) {
            $browse->addChild('divider1', [
                'label' => '<hr class="dropdown-divider">',
                'attributes' => [
                    'aria-hidden' => 'true',
                ],
                'extras' => [
                    'safe_label' => true,
                ],
            ]);
            $browse->addChild('artwork_contributions', [
                'label' => 'Artwork Contributions',
                'route' => 'artwork_contribution_index',
                'linkAttributes' => [
                    'class' => 'dropdown-item link-dark',
                ],
            ]);
            $browse->addChild('project_contributions', [
                'label' => 'Project Contributions',
                'route' => 'project_contribution_index',
                'linkAttributes' => [
                    'class' => 'dropdown-item link-dark',
                ],
            ]);
            $browse->addChild('divider2', [
                'label' => '<hr class="dropdown-divider">',
                'attributes' => [
                    'aria-hidden' => 'true',
                ],
                'extras' => [
                    'safe_label' => true,
                ],
            ]);
            $browse->addChild('artwork_category', [
                'label' => 'Artwork Categories',
                'route' => 'artwork_category_index',
                'linkAttributes' => [
                    'class' => 'dropdown-item link-dark',
                ],
            ]);
            $browse->addChild('artwork_role', [
                'label' => 'Artwork Roles',
                'route' => 'artwork_role_index',
                'linkAttributes' => [
                    'class' => 'dropdown-item link-dark',
                ],
            ]);
            $browse->addChild('mediafile_category', [
                'label' => 'Media File Categories',
                'route' => 'media_file_category_index',
                'linkAttributes' => [
                    'class' => 'dropdown-item link-dark',
                ],
            ]);
            $browse->addChild('project_role', [
                'label' => 'Project Roles',
                'route' => 'project_role_index',
                'linkAttributes' => [
                    'class' => 'dropdown-item link-dark',
                ],
            ]);
            $browse->addChild('project_category', [
                'label' => 'Project Categories',
                'route' => 'project_category_index',
                'linkAttributes' => [
                    'class' => 'dropdown-item link-dark',
                ],
            ]);
            $browse->addChild('venue_category', [
                'label' => 'Venue Categories',
                'route' => 'venue_category_index',
                'linkAttributes' => [
                    'class' => 'dropdown-item link-dark',
                ],
            ]);
        }

        return $menu;
    }
}
