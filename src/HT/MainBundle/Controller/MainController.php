<?php

namespace HT\MainBundle\Controller;

// c'est ici qu'est appelé les services (objets) de symfonie qui nous servirons dans ce controleur
use HT\MainBundle\Entity\Shop;
use HT\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


// notre controleur principal, c'est ici qu'il y aura toutes la logique du projet
class MainController extends controller {

	protected $title = "HappyTea";





	public function indexAction() { // le modèle pour la page index. les modèles finissent toujours par Action
		$pageName = "d'accueil";

		 $em = $this->getDoctrine()->getManager();

		 $productRepository = $em->getRepository('HTMainBundle:Product'); //em = 'entity manager'
		 $products = $productRepository->findAll();

		// on envoi la view index.html.twig
		return $this->render("HTMainBundle:Main:index.html.twig", array(
				'title' => $this->title,
				'pageName' => $pageName,
				'products' => $products  // on envoie les variable dans notre page twig
			));


	}

	public function categoriesAction() { // modèle page categories

		$pageName = "catégories";

		return $this->render("HTMainBundle:Main:categories.html.twig", array(
				'title' => $this->title,
				'pageName' => $pageName,
			));

	}

	public function teasAction($id) { // modèle page thé
		$pageName = "thés";

		$em = $this->getDoctrine()->getManager();

		$productRepository = $em->getRepository('HTMainBundle:Product'); //em = 'entity manager'
		$product = $productRepository->find($id);

		return $this->render("HTMainBundle:Main:teas.html.twig", array(
				'title' => $this->title,
				'pageName' => $pageName,
				'id' => $id,
				'product' => $product
			));


	}

	public function teamAction() { // modèle page Team

		$pageName = "team";

		return $this->render("HTMainBundle:Main:team.html.twig", array(
				'title' => $this->title,
				'pageName' => $pageName,

			));


	}

	public function faqAction() { // modèle FAQ


		$pageName = "FAQ";


		//SCRIPT TEST POUR REMPLIR BDD

		// // Les noms d'utilisateurs à créer
		//     $listNames = array('Alex', 'Antoine', 'Claire', 'Quentin');
		//     $manager = $this->getDoctrine()->getManager();

		//     foreach ($listNames as $name) {
		//       // On crée l'utilisateur
		//       $user = new User;

		//       // Le nom d'utilisateur et le mot de passe sont identiques pour l'instant
		//       $user->setUsername($name);
		//       $user->setPassword($name);

		//       // On ne se sert pas du sel pour l'instant
		//       $user->setSalt('');
		//       // On définit uniquement le role ROLE_USER qui est le role de base
		//       $user->setRoles(array('ROLE_USER', 'ROLE_SELLER'));

		//       // On le persiste
		//       $manager->persist($user);
		//     }

		//     // On déclenche l'enregistrement
		//     $manager->flush();




		return $this->render("HTMainBundle:Main:faq.html.twig", array(
				'title' => $this->title,
				'pageName' => $pageName,
			));
	}

	public function cguAction() { // modèle page CGU

		$pageName = "Conditions générales d'utilisation";

		return $this->render("HTMainBundle:Main:cgu.html.twig", array(
				'title' => $this->title,
				'pageName' => $pageName,

			));


	}




	public function addSellerAction(Request $request) { // l'objet request sert à récupérer les données du formulaire

		if(!$this->get('security.authorization_checker')->isGranted('ROLE_SELLER')) {

			throw new AccessDeniedException("Accès limité aux vendeurs de thés. ");

		}

		$pageName = "ajouter vendeur";
		$nameTest = "";
		$adressTest="";
		$error = []; 
    	$success = ""; 


		$utilisateur = $this->container->get('security.token_storage')->getToken()->getUser();

		dump($utilisateur);

		$userId = $utilisateur->getId(); 



	 // INSERER DANS LA BDD
		// //on vérifie si le formulaire a bien été envoyé
		 if($request->isMethod('POST')) {
    
    
		 // est égal à $_POST['name'];
		$name=$request->get('name');
		$adress=$request->get('adress');
		$url=$request->get('url'); 
		$description=$request->get('description');
		$phone=$request->get('phone');
		$logo = $request->files->get('logo');

		$openingTimes = []; 

		$openingTimes['opening'] = $request->get('open'); 
		$openingTimes['closing'] = $request->get('close'); 



		if(empty($name)) {
			$error['name'] = "Veuillez remplir le champ \"Nom\"."; 

		}



		if(empty($url)) {
			$error['url'] = "Veuillez remplir le champ \"Lien de votre site\"."; 

		}

		if(!filter_var($url, FILTER_VALIDATE_URL)) {

			$error['url'] = "Votre lien n'est pas valide.";
		}

	

		if(strlen($description)< 10) {

			$error['description'] = "Votre description doit faire au moins 10 caractères."; 
		}

		if($logo == NULL ) {

			$error['logo'] = "Veuillez ajouter une image pour illustrer votre Shop."; 
		}

		// dump($logo); 

		
    
		//création de l'entité (objet qui nous sert à envoyer le nouveau vendeur dans la database)
		if(empty($error)) {

		

		dump($logo);

		$mime=$logo->guessClientExtension();
		$uploadName = uniqid("doc_", true).'.'.$mime; 
		$this->upload($logo, $uploadName);  

		$shop = new Shop();
		 $shop->setName($name);
		 $shop->setAdress($adress);
		 $shop->setUrl($url);
		 $shop->setDescription($description);
		 $shop->setPhone($phone); 
		 $shop->setOpeningTimes($openingTimes); 
		 $shop->setLogo($uploadName);
		 $shop->setUser($utilisateur); 

		 $em = $this->getDoctrine()->getManager();

		 $em->persist($shop); 

		 $em->flush(); 
		 $success = "Votre Shop a bien été ajouté !";
		}
    //
		// // on créé l'entity manager
		// $em = $this->getDoctrine()->getManager();
    //
		// // Étape 1 : On « persiste » l'entité
		// $em->persist($seller);
    //
		//     // Étape 2 : On « flush » tout ce qui a été persisté avant. l'entity manager envoie les informations dans la base de donnée
		// $em->flush();

		// getRepository sert à récupérer les informations dans la base de donnees. on recupérere donc les données du vendeur que l'ont vient juste de créer
		//ceci remplace donc le select de mySql
// CHERCHER DANS LA BDD
		// $sellerRepository = $em->getRepository('HTMainBundle:seller')->find($seller->getId());
    //
		// $nameTest= $sellerRepository->getName();
		// $adressTest= $sellerRepository->getAdress();



		// et voilà ! pas besoin de reqête SQL, vous pouvez vérifier, le nouveau vendeur à bien été ajouter à la base de donnée !

	 }

		return $this->render("HTMainBundle:Main:addSeller.html.twig", array(

				'title' => $this->title,
				'pageName' => $pageName,
				'error' => $error,
				'success' => $success


			));


	}

	public function shopListAction() {
		$pageName = 'List des boutiques';

		$em = $this->getDoctrine()->getManager();

		$shopRepository = $em->getRepository('HTMainBundle:Shop'); //em = 'entity manager'
		$shops = $shopRepository->findAll();
		dump($shops); 
		return $this->render('HTMainBundle:Main:shopList.html.twig' , array(
			'title' => $this->title,
			'pageName' => $pageName,
			'shops' => $shops
		));
	}

	public function shopAction($id) {
		$pageName = 'shop';

		$em = $this->getDoctrine()->getManager();

		$shopRepository = $em->getRepository('HTMainBundle:Shop'); //em = 'entity manager'
		$shop = $shopRepository->find($id);

		return $this->render('HTMainBundle:Main:shop.html.twig', array(
			'title' => $this->title,
			'pageName' => $pageName,
			'id' => $id,
			'shop' => $shop
		));
	}


	private function upload($file,$name,$maxsize=FALSE,$extensions=FALSE) {
	   //Test1: fichier correctement uploadé
	     if (!isset($file) OR $file->getError() != 0) 
	        {
	        $error = 'Le fichier n a pas été correctement uploadé<br/> ';
	        return $error; }
	   //Test2: taille limite
	     if ($maxsize !== FALSE AND $file->getClientSize() > $maxsize) 
	        {$error = 'Le fichier est trop gros !<br/>';
	        return $error; }

	   //Test3: extension
	     // $ext = substr(strrchr($_FILES[$index]['name'],'.'),1);
	        $ext=$file->guessClientExtension();

	     if ($extensions !== FALSE AND !in_array($ext,$extensions)) {
	        
	        $error = 'Le fichier a une extension incorrect !<br/>';
	        return $error; 
	     }
	     //Concatene l'extension MIME
	    // $name .= '.'.$ext;
	   //Déplacement
	     // return move_uploaded_file($_FILES[$index]['tmp_name'],$destination);
	     dump($file); 
	    

	     return $file->move("web/img/", $name );
		}









}
