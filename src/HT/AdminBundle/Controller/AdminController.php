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


			public function userAjaxAction(Request $request) {

				$em = $this->getDoctrine()->getManager();
				$userRepository = $em->getRepository('HTUserBundle:User');
				$users = $userRepository->findAll(); 

				if($request->query->get('id')!=null && $request->query->get('value')!= null) {

					$user=$userRepository->find($request->query->get('id'));
					$value=$request->query->get('value');
				

					if($value==1) {$roles = array('ROLE_USER');}
					if($value==2) {$roles = array('ROLE_USER', 'ROLE_SELLER');}
					if($value==3) {$roles = array('ROLE_USER', 'ROLE_SELLER', 'ROLE_ADMIN');}
					if($value==4) {$roles = array('ROLE_USER', 'ROLE_SELLER', 'ROLE_ADMIN','ROLE_SUPER_ADMIN');}

					$user->setRoles($roles); 

					$em->persist($user);

					$em->flush(); 
				}

				if($request->query->get('id')!=null && $request->query->get('req') == "ban") {

					$user=$userRepository->find($request->query->get('id'));
					$ban=$request->query->get('ban');
					if($ban==null) {
						$ban=false; 
					}

					$user->setIsBanned(!$ban); 

					$em->persist($user);

					$em->flush(); 

				}

				return $this->render('HTAdminBundle:Admin:ajaxUser.html.twig', array(
						'users' => $users,
					));
			}

			public function adminAjaxAction(Request $request) {


				$em = $this->getDoctrine()->getManager();
				$contactRepository = $em->getRepository('HTAdminBundle:Contact');
				$contacts = $contactRepository->findAll(); 

				$userRepository = $em->getRepository('HTUserBundle:User');
				// $users = $userRepository->findByIsChecked(false); 

				if($request->query->get('id')!=null && $request->query->get('req')== "check")
				{

					$user = $userRepository->find($request->query->get('id'));
					$user->setIsChecked(true); 

					$em->persist($user); 

					$em->flush(); 
				}

			$users = $userRepository->findByIsChecked(false);

				return $this->render('HTAdminBundle:Admin:ajaxAdmin.html.twig', array(
						'contacts' => $contacts,
						'users' => $users 
					));
			}

			public function contentAjaxAction() {

				return $this->render('HTAdminBundle:Admin:ajaxContent.html.twig');
			}

}