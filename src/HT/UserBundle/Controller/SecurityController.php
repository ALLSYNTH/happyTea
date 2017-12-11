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

		if($request->isMethod('POST')) {

			$username = $request->get('username'); 
			$password = $request->get('password'); 

			$user = new User; 
			$user->setUsername($username); 
			$user->setPassword($password); 
			$user->setSalt(''); 

			$user->setRoles(array('ROLE_USER', 'ROLE_SELLER')); 



			$em = $this->getDoctrine()->getManager(); 


			$em->persist($user); 


			$em->flush(); 

		}

		return $this->render('HTUserBundle:Security:register.html.twig', array(

			));
	}




}
