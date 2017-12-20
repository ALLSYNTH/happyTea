<?php


namespace HT\UserBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use HT\UserBundle\Entity\User; 
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



class SecurityController extends Controller 
{
	private $title = "HappyTea"; 


	public function loginAction(Request $request)
	{
		$pageName = 'login';

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
      'title' 		  => $this->title,
      'pageName'     => $pageName,
    ));
	}



	public function registerAction(Request $request) 
	{	
		$error = []; 
		$success = ""; 

		$encoder = $this->get('security.password_encoder');

		
		$pageName = 'inscription';


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
			$encoded = $encoder->encodePassword( $user, $password);  
			$user->setPassword($encoded); 
			$user->setSalt(''); 
			$user->setMail($mail); 
			$user->setIsChecked(true); 
			

			$user->setRoles(array('ROLE_USER')); 



			


			$em->persist($user); 


			$em->flush(); 

			$success=true;


			}

		}

		dump($request); 

		return $this->render('HTUserBundle:Security:register.html.twig', array(
				'error' => $error,
				'success' => $success,
				
				'title'   => $this->title,  
				'pageName'=> $pageName,
				'request' => $request 
			));
	}


	public function registerSellerAction(Request $request) 
	{	
		$error = []; 
		$success = ""; 

		$encoder = $this->get('security.password_encoder');

		
		$pageName = 'inscription';

			if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) 
		{

			return $this->redirectToRoute('ht_main_homepage');
		}



		if($request->isMethod('POST')) {

			$username = $request->get('username'); 
			$password = $request->get('password'); 
			$password2 = $request->get('password2'); 
			$mail = $request->get('mail'); 
			$siret = $request->get('siret'); 

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

			if(strlen($siret) < 9 || !is_numeric($siret)) {

				$error['siret'] = "Le champ SIRET n'est pas valide."; 
			}


			if($userRepository = $em->getRepository('HTUserBundle:User')->findBySiret($siret)) {

				$error['siret'] = "Ce siret est déjà utilisé par un autre utilisateur."; 
			} 

			if(empty($error)) {

			$user = new User; 
			$user->setUsername($username);
			$encoded = $encoder->encodePassword( $user, $password);  
			$user->setPassword($encoded); 
			$user->setSalt(''); 
			$user->setMail($mail); 
			$user->setSiret($siret); 

			$user->setRoles(array('ROLE_USER', 'ROLE_SELLER')); 



			


			$em->persist($user); 


			$em->flush(); 
			$success=true;


			}

		}

		return $this->render('HTUserBundle:Security:registerSeller.html.twig', array(
				'error' => $error,
				'success' => $success,
				
				'title'   => $this->title,  
				'pageName'=> $pageName,
			));
	}


	public function passwordForgetAction(Request $request, \Swift_Mailer $mailer) {

				$pageName = 'Mot de passe oublié'; 
				$error = [];
				$success = "";
				$token = ""; 
				$userId = "";
				$em = $this->getDoctrine()->getManager(); 

				if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) 
				{

					return $this->redirectToRoute('ht_main_homepage');
				}

			


				if($request->isMethod('POST')) {

					$mail=$request->get('mail');


					if($em->getRepository('HTUserBundle:User')->findByMail($mail)) {
						$user = $em->getRepository('HTUserBundle:User')->findByMail($mail)[0];
						$userId = $user->getId(); 
					}

					else {

						$error['mail'] = "Aucun utilisateur n'est enregistré avec cette adresse mail."; 
					} 

					if(empty($error)) {
						$token = $this->generateResetToken(); 
						$user->setResetToken($token);
						$timeToken = new \Datetime(); 
						$timeToken->modify('+1 day'); 
						$user->setResetExpire($timeToken);

						// date("Y/m/d/h/i", time()+ 24*3600

						$em->persist($user); 

						$em->flush(); 
						$success = true; 

						$link = $this->generateUrl('password_change', array('id'=> $userId, 'token' => $token ));

						$message = (new \Swift_Message('Mot de passe perdu'))
						     ->setFrom('no-response@HappyTea.ninja')
						     ->setTo('quentin.gary@nordnet.fr')
						     ->setBody(
						         $this->renderView(
						             // templates/emails/registration.html.twig
						             'emails/mail_password.html.twig',
						             array('link' => $link)
						         ),
						         'text/html'
						     );

						 // var_dump($message); 

						 dump($mailer->send($message)); 

					}

					dump($success);

				}

		return $this->render('HTUserBundle:Security:passwordForget.html.twig', array(
				'error' => $error,
				'success' => $success,
				'title'   => $this->title,  
				'pageName'=> $pageName,
				'token' => $token,
				'userId'=> $userId

			));
	}

	public function passwordChangeAction($token, $id,  Request $request) {

			$canChange = $this->checkToken($id, $token); 

			$error = []; 
			$success = ""; 
			$pageName = "Changer de mot de passe."; 

			$encoder = $this->get('security.password_encoder');

			dump($id);

			dump($token);
			// die();

			if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) 
			{

				return $this->redirectToRoute('ht_main_homepage');
			}


			if($request->isMethod('POST') && $canChange) {

				$password = $request->get('password'); 
				$password2 = $request->get('password2'); 

				if(empty($password) || empty($password2) ) {

					$error['password'] = "Veuillez remplir tous les champs.";
				}

				if($password != $password2) {

					$error['password'] = "Les deux champs mots de passes ne sont pas identiques."; 

				}

				if(strlen($password) < 8) {

					$error['password'] = "Le mot de passe doit contenir au moins 8 charactères."; 
				}

				if(is_numeric($password)) {

					$error['password'] = "Le mot de passe doit contenir au moins une lettre."; 
				}

				if(empty($error)) {

					$em = $this->getDoctrine()->getManager(); 
					
					$user = $em->getRepository('HTUserBundle:User')->find($id);
					$encoded = $encoder->encodePassword( $user, $password);  
					$user->setPassword($encoded); 
					$user->setResetExpire(NULL);
					$user->setResetToken(NULL);

					$em->persist($user); 
					$em->flush(); 

					$success = true; 
				}

				


			}

		return $this->render('HTUserBundle:Security:passwordChange.html.twig', array(
				'error' => $error,
				'success' => $success,
				'title'   => $this->title,  
				'pageName'=> $pageName,
				'canChange' => $canChange
				
			));
	}

	private	function generateResetToken() {

		return md5(uniqid( rand(), true));

	}


	private function checkToken($id, $token) {
		//include("connectPDO.php"); 

	// 	global $bdd; 

	// 	$stmt=$bdd->prepare('SELECT * FROM users WHERE id = ?'); 
	// $stmt->bindValue(1, $id); 
	// $stmt->execute(); 

	// $userInfo=$stmt->fetch(); 
		$em = $this->getDoctrine()->getManager(); 
		dump($id);
		$user = $em->getRepository('HTUserBundle:User')->find($id);
			dump($user); 
		$resetExpire=$user->getResetExpire(); 
		$resetToken=$user->getResetToken(); 

		dump($resetToken);
		dump($token); 
		dump($user); 
	// $time=strtotime($resetExpire); 

		$now = new \Datetime();
		$now->format("Y/m/d/h/i"); 
		dump($now); 
		dump($resetExpire); 


		if($resetToken==$token && $now < $resetExpire  ) {

			return true; 
		}

		return false; 
	
	}

}
