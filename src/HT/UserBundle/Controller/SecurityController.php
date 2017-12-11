<?php


namespace HT\UserBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use HT\UserBundle\Entity\User; 



class SecurityController extends Controller 
{
	public function loginAction(Request $request)
	{
		if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) 
		{

			return $this->redirectToRoute('ht_main_homepage');
		}

			    // Le service authentication_utils permet de récupérer le nom d'utilisateur
    // et l'erreur dans le cas où le formulaire a déjà été soumis mais était invalide
    // (mauvais mot de passe par exemple)
    $authenticationUtils = $this->get('security.authentication_utils');

    return $this->render('HTUserBundle:Security:login.html.twig', array(
      'last_username' => $authenticationUtils->getLastUsername(),
      'error'         => $authenticationUtils->getLastAuthenticationError(),
    ));
	}



	public function registerAction(Request $request) 
	{	
		$error = []; 
		$success = ""; 


		if($request->isMethod('POST')) {

			$username = $request->get('username'); 
			$password = $request->get('password'); 
			$password2 = $request->get('password2'); 
			$mail = $request->get('mail'); 

			$em = $this->getDoctrine()->getManager(); 

			if($userRepository = $em->getRepository('HTUserBundle:User')->findByMail($mail)) {

				$error['mail'] = "Votre adresse mail est déjà utilisé."; 
			} 

			if($userRepository = $em->getRepository('HTUserBundle:User')->findByUsername($username)) {

				$error['username'] = "Ce nom d'utilisateur est déjà utilisé."; 
			} 


			if(strlen($username) < 8) {

				$error['username'] = "Votre nom d'utilisateur doit contenir au moins 8 charactères"; 
			}

			if($password !== $password2) {

				$error['password'] = "Les deux champs passwords ne sont pas identiques."; 
			}

			if(strlen($password) < 8) {

				$error['password'] = "Le mot de passe doit contenir au moins 8 charactères."; 
			}

			if(is_numeric($password)) {

				$error['password'] = "Le mot de passe doit contenir au moins une lettre."; 
			}

			if (!filter_var($mail , FILTER_VALIDATE_EMAIL)) {

				$error['mail'] = "Votre email n'est pas correct."; 
			}

			if(empty($error)) {

			$user = new User; 
			$user->setUsername($username); 
			$user->setPassword($password); 
			$user->setSalt(''); 
			$user->setMail($mail); 

			$user->setRoles(array('ROLE_USER', 'ROLE_SELLER')); 



			


			$em->persist($user); 


			$em->flush(); 


			}

		}

		return $this->render('HTUserBundle:Security:register.html.twig', array(
				'error' => $error,
				'success' => $success,
				'userRep' => $userRepository,   
			));
	}




}
