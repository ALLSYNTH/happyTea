<?php

namespace HT\AdminBundle\Controller;

// c'est ici qu'est appelé les services (objets) de symfonie qui nous servirons dans ce controleur
use HT\MainBundle\Entity\Shop;
use HT\MainBundle\Entity\Product;
use HT\MainBundle\Entity\Comment;
use HT\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;




class AdminController extends controller {

			protected $title = "HappyTea";


			public function adminPageAction() {

				

				return $this->render('HTAdminBundle:Admin:adminPage.html.twig', array(
						'title' => $this->title,
					));

			}

}