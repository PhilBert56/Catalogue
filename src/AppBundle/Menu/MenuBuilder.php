<?php

namespace AppBundle\Menu;

use Knp\Menu\MenuFactory;

class MenuBuilder
{
    public function mainMenu(MenuFactory $factory, array $options){

        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav');
        $menu->addChild('Accueil', ['route' => 'homepage']);
        $menu->addChild('Catalogue', ['route' => 'catalogue']);
        //$menu->addChild('Catalogue Delarue Teneze', ['route' => 'catalogue']);
        
        return $menu;

    }
}