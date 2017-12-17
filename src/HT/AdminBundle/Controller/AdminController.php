<?php

namespace HT\AdminBundle\Controller;

// c'est ici qu'est appelé les services (objets) de symfonie qui nous servirons dans ce controleur
use HT\MainBundle\Entity\Shop;
use HT\MainBundle\Entity\Product;
use HT\MainBundle\Entity\Comment;
use HT\UserBundle\Entity\User;
use HT\AdminBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;




class AdminController extends controller {

			protected $title = "HappyTea";


			public function adminPageAction(Request $request) {
				$error = []; 
				$success = ""; 
				$em = $this->getDoctrine()->getManager();
				$userRepository = $em->getRepository('HTUserBundle:User');
				$users = $userRepository->findAll();


				
				$shopRepository = $em->getRepository('HTMainBundle:Shop');
				$shops = $shopRepository->findAll(); 

				if($request->isMethod('POST')) {

					$articleTitle = $request->get('title');
					$subTitle = $request->get('subTitle');
					$content = $request->get('content');
					$picture = $request->files->get('picture');




					if(empty($articleTitle) && empty($subTitle) && empty($content)   ) {

						$error['empty'] = "Veuillez remplir au moins un champs."; 

					}
					

					if($picture != null ) {
						$mime=$picture->guessClientExtension();
						$uploadName = uniqid("doc_", true).'.'.$mime;
						if(!$this->upload($picture, $uploadName, 1000000, array('png','gif','jpg','jpeg') )  ) {
							$error['picture'] = "Votre fichier n'est pas à un format autorisé et/ou est trop volumineux.";
						}
					}

					else {

						$uploadName = ""; 
					}


					if(empty($error)) {
						 $article = new Article;
						$article->setTitle($articleTitle);
						$article->setSubTitle($subTitle);
						$article->setContent($content);
						$article->setPicture($uploadName); 
						$article->setWriter('test'); 

					

						$em->persist($article);
						$em->flush();

						$success = "Votre article a bien été ajouté !";

					}



				}

					// dump($articleTitle);
					// dump($picture); 
				return $this->render('HTAdminBundle:Admin:adminPage.html.twig', array(
						'title' => $this->title,
						'users' => $users,
						'shops' => $shops,
						'error' => $error,
						'success' => $success
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

				if($request->query->get('req') == "search") {
					$search = $request->query->get('search'); 
					// $userRepository = $em->getRepository('HTUserBundle:User');

					// $users = $userRepository->findByUsername($search); 

				

					$query = $em->createQuery(
					    "SELECT p
					    FROM HTUserBundle:User p
					    WHERE p.username
					    LIKE :search OR p.mail LIKE :search OR p.id LIKE :search"
					)->setParameter('search', '%'.$search.'%');

					$users = $query->getResult();

					
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

			public function contentAjaxAction(Request $request) {


				return $this->render('HTAdminBundle:Admin:ajaxContent.html.twig');
			}


			private function upload($file,$name,$maxsize=FALSE,$extensions=FALSE) {
			   //Test1: fichier correctement uploadé
				$error= "";
			     if (!isset($file) OR $file->getError() != 0)
			        {
			        $error = 'Le fichier n a pas été correctement uploadé<br/> ';
			        return FALSE; }
			   //Test2: taille limite
			     if ($maxsize !== FALSE AND $file->getClientSize() > $maxsize)
			        {$error = 'Le fichier est trop gros !<br/>';
			        return FALSE; }

			   //Test3: extension
			     // $ext = substr(strrchr($_FILES[$index]['name'],'.'),1);
			        $ext=$file->guessClientExtension();

			     if ($extensions !== FALSE AND !in_array($ext,$extensions)) {

			        $error = 'Le fichier a une extension incorrect !<br/>';
			        return FALSE;
			     }
			     //Concatene l'extension MIME
			    // $name .= '.'.$ext;
			   //Déplacement
			     // return move_uploaded_file($_FILES[$index]['tmp_name'],$destination);
			     dump($file);


			     return $file->move("web/img/", $name );
				}

		
	}