<?php

namespace HT\MainBundle\Controller;

// c'est ici qu'est appelé les services (objets) de symfonie qui nous servirons dans ce controleur
use HT\MainBundle\Entity\Shop;
use HT\MainBundle\Entity\Product;
use HT\MainBundle\Entity\Comment;
use HT\MainBundle\Entity\Rate;
use HT\UserBundle\Entity\User;
use HT\AdminBundle\Entity\Contact;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;



// notre controleur principal, c'est ici qu'il y aura toutes la logique du projet
class MainController extends Controller {

	protected $title = "HappyTea";





	public function indexAction() { // le modèle pour la page index. les modèles finissent toujours par Action
		$pageName = "d'accueil";

		 $em = $this->getDoctrine()->getManager();

		 $productRepository = $em->getRepository('HTMainBundle:Product'); //em = 'entity manager'
		 $products = $productRepository->findAll();

		 $articleRepository = $em->getRepository('HTAdminBundle:Article'); //em = 'entity manager'
		 if(null != $articleRepository->findByIsPublished(true) ) {
		 $articles = $articleRepository->findByIsPublished(true)[0];
		}
		// on envoi la view index.html.twig
		return $this->render("HTMainBundle:Main:index.html.twig", array(
				'title' => $this->title,
				'pageName' => $pageName,
				'products' => $products,
				'article' => $articles  // on envoie les variable dans notre page twig
			));


	}

	public function categoriesAction() { // modèle page categories

		$pageName = "catégories";

		return $this->render("HTMainBundle:Main:categories.html.twig", array(
				'title' => $this->title,
				'pageName' => $pageName,
			));

	}

	public function teasAction($id, Request $request) { // modèle page thé
		$pageName = "thés";
		$favarray = '';
		$utilisateur = $this->container->get('security.token_storage')->getToken()->getUser();
		if( $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY') ){
			$favs = $utilisateur->getFavProduct();
			$favarray = $favs->toArray();
		}


		$em = $this->getDoctrine()->getManager();

		// Recuperer le produit
		$productRepository = $em->getRepository('HTMainBundle:Product'); //em = 'entity manager'
		$product = $productRepository->find($id);
		$error = [];
		$success = "";

		// Commentaire
		if($request->isMethod('POST')) {
					$content = $request->get('content');
					$publishedAt = new \DateTime();
					$comment = new Comment();

					if(strlen($content) < 10) {
						$error['length'] = 'Votre commentaire doit comporter au minimum 10 caratères';
					}

					if(empty($error)) {

						$comment->setUser($utilisateur);
						$comment->setContent($content);
						$comment->setPublishedAt($publishedAt);
						$comment->setProduct($product);
						$em->persist($comment);
						$em->flush();

						$success .= "Votre commentaire à été posté.";
					}
				}
				$commentRepository = $em->getRepository('HTMainBundle:Comment');
				$comments = $commentRepository->findByProduct($id);

				$productRepository = $em->getRepository('HTMainBundle:Product'); //em = 'entity manager'
				$products = $productRepository->findAll();

				$rateRepository = $em->getRepository('HTMainBundle:Rate');
				$avgRate = $rateRepository->findAvgpRate($product)[0][1];

		return $this->render("HTMainBundle:Main:teas.html.twig", array(
				'title' => $this->title,
				'pageName' => $pageName,
				'id' => $id,
				'product' => $product,
				'comments' => $comments,
				'success' => $success,
				'error' => $error,
				'products' => $products,
				'user' => $utilisateur,
				'favs' => $favarray,
				'avgRate' => $avgRate
			));


	}

	public function teamAction() { // modèle page Team

		$pageName = "team";

		return $this->render("HTMainBundle:Main:team.html.twig", array(
				'title' => $this->title,
				'pageName' => $pageName

			));


	}

	public function blogAction() {
		$pageName = "Blog";

		$em = $this->getDoctrine()->getManager();

		$articleRepository = $em->getRepository('HTAdminBundle:Article'); //em = 'entity manager'
		$articles = $articleRepository->findByIsPublished(true);

		return $this->render("HTMainBundle:Main:blog.html.twig", array(
				'title' => $this->title,
				'pageName' => $pageName,
				'articles' => $articles

			));
	}

	public function sitemapAction(){
	    $pageName = "Sitemap";

 		return $this->render("HTMainBundle:Main:sitemap.html.twig", array(
 				'title' => $this->title,
 				'pageName' =>$pageName

 		));
		}

	public function contactAction(Request $request) { // modèle page CGU //it's my POST method

		$pageName = "contact";
		$error = "";
		$success = "";
		$em = $this->getDoctrine()->getManager();

		if($request->isMethod('POST')) { // Si j'ai fait mon Submit
			$name = $request->get('name'); // je stocke le nom dans la variable nom
			$mail = $request->get('mail');
			$message = $request->get('message');

			if(empty($name) || empty($mail)  || empty($message) ) {
					$error = "Remplir tous les champs!";
			}

			if(empty($error)) {
				$contact = new Contact;
				$contact->setName($name);
				$contact->setMail($mail);
				$contact->setMessage($message);
				$em->persist($contact);
				$em->flush();

				$success = "Votre message a bien été envoyé !";

			}
		}



		return $this->render("HTMainBundle:Main:contact.html.twig", array(
				'title' => $this->title,
				'pageName' => $pageName,
				'error' => $error,
				'success' => $success

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

		else {
			$mime=$logo->guessClientExtension();
			$uploadName = uniqid("doc_", true).'.'.$mime;
			if(!$this->upload($logo, $uploadName, 1000000, array('png','gif','jpg','jpeg') )  ) {
				$error['logo'] = "Votre fichier n'est pas à un format autorisé et/ou est trop volumineux.";
			}

		}

		 dump($error);



		//création de l'entité (objet qui nous sert à envoyer le nouveau vendeur dans la database)
		if(empty($error)) {



		dump($logo);




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
		$pageName = 'Shop';
		if(!$this->get('security.authorization_checker')->isGranted('ROLE_SELLER')) {

			throw new AccessDeniedException("Accès limité aux vendeurs de thés. ");

		}



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

	public function userAction($id) {
 		$pageName = 'User';

	 	$em = $this->getDoctrine()->getManager();

 		$userRepository = $em->getRepository('HTUserBundle:User');
 		$user = $userRepository->find($id);

 		$shopRepository = $em->getRepository('HTMainBundle:Shop');
 		// $userShop = $shopRepository->findByUser($id)[0];
 		$userShop = $user->getShop();
		$userfavs = $user->getFavProduct();

 		dump($userShop);

 		$productRepository = $em->getRepository('HTMainBundle:Product'); //em = 'entity manager'
 		$products = $productRepository->findAll();

 		return $this->render('HTMainBundle:Main:user.html.twig', array(
 			'title' => $this->title,
 			'pageName' => $pageName,
 			'id' => $id,
 			'user' => $user,
 			'userShop' => $userShop,
 			'products' => $products,
			'userFavs' => $userfavs
 		));
 	}

	public function addProductAction(Request $request) {

		if(!$this->get('security.authorization_checker')->isGranted('ROLE_SELLER')) {

			throw new AccessDeniedException("Accès limité aux vendeurs de thés. ");

		}

			$pageName = "ajouter thé";
			$error = [];
			$success = "";

			$utilisateur = $this->container->get('security.token_storage')->getToken()->getUser();



			$userId = $utilisateur->getId();

			$em = $this->getDoctrine()->getManager();
			$shop = $em->getRepository('HTMainBundle:Shop')->findByUser($userId)[0];

			$productRepository = $em->getRepository('HTMainBundle:Product'); //em = 'entity manager'
			$products = $productRepository->findByShop($shop->getId());
			// dump($products);

			 if($request->isMethod('POST')) {



			 	$name = $request->get('name');
			 	$price = $request->get('price');
			 	$description = $request->get('description');
			 	$picture = $request->files->get('picture');
			 	$category_id = $request->get('category');

			 	$category = $em->getRepository('HTMainBundle:Category')->findById($category_id)[0];

			 	if(empty($name)) {

			 		$error['name'] = "Veuillez remplir le champ \"Nom\".";
			 	}

			 	if(empty($price)) {

			 		$error['price'] = "Veuillez remplir le champ \"Price\".";
			 	}

			 	if(!is_numeric($price)) {

			 		$error['price'] = "Le prix doit être un nombre.";
			 	}

			 	if(empty($description)) {

			 		$error['description'] = "Veuillez remplir le champ \"Description\".";
			 	}

			 	if(empty($category)) {

			 		$error['category'] = "Veuillez remplir le champ \"Catégorie\".";
			 	}

			 	if($picture == NULL ) {

			 		$error['picture'] = "Veuillez ajouter une image pour illustrer votre Shop.";
			 	}

			 	else {
			 		$mime=$picture->guessClientExtension();
			 		$uploadName = uniqid("doc_", true).'.'.$mime;
			 		if(!$this->upload($picture, $uploadName, 1000000, array('png','gif','jpg','jpeg') )  ) {
			 			$error['picture'] = "Votre fichier n'est pas à un format autorisé et/ou est trop volumineux.";
			 		}

			 	}



				if(empty($error)) {



					$product = new Product;
					$product->setShop($shop);
					$product->setCategory($category);
					$product->setPicture($uploadName);
					$product->setDescription($description);
					$product->setPrice($price);
					$product->setName($name);

					$em->persist($product);

					$em->flush();

					$success = "Le produit a bien été ajouté à votre shop.";


				}



			 }




		return $this->render('HTMainBundle:Main:addProduct.html.twig', array(
				'title' => $this->title,
				'pageName' => $pageName,
				'error' => $error,
				'success' => $success,
				'products' => $products
			));
	}

	public function favAction(Request $request) {
		$statut = [];
		$id = $request->query->get('id');
		$user = $this->container->get('security.token_storage')->getToken()->getUser();

		$em = $this->getDoctrine()->getManager();
		$productRepository = $em->getRepository('HTMainBundle:Product'); //em = 'entity manager'
		$product = $productRepository->find($id);

		if ($user->isFavProduct($product)) {
			   $user->removeFavProduct($product);
		} else {
			   $user->addFavProduct($product);
		}

		$em->persist($user);
		$em->flush();

		return new JsonResponse($statut);
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

		public function shopPageAction($id) {
			$pageName = 'Shop Page';

      // Create a robot (entity manager = $em) that will fetch info from database
			$em = $this->getDoctrine()->getManager();

      // Telling which table to fetch in the database
			$shopRepository = $em->getRepository('HTMainBundle:Shop'); //em = 'entity manager'
			$productRepository = $em->getRepository('HTMainBundle:Product');

			// fetch info from shop according to specified ID
			$shop = $shopRepository->find($id);
			$shopProducts = $productRepository->findByShop($shop);

			// Twig names
			return $this->render('HTMainBundle:Main:shopPage.html.twig', array(
				'title' => $this->title,
				'pageName' => $pageName,
				'id' => $id,
				'shop' => $shop, // refers to table Shop (see line 549)
				'shopProducts' => $shopProducts
			));
		}





		public function updateProductAction($id, Request $request) {



			if(!$this->get('security.authorization_checker')->isGranted('ROLE_SELLER')) {

				throw new AccessDeniedException("Accès limité aux vendeurs de thés. ");

			}

				$pageName = "modifier produit";
				$error = [];
				$success = "";

				$utilisateur = $this->container->get('security.token_storage')->getToken()->getUser();



				$userId = $utilisateur->getId();

				$em = $this->getDoctrine()->getManager();
				$shop = $em->getRepository('HTMainBundle:Shop')->findByUser($userId)[0];

				$productRepository = $em->getRepository('HTMainBundle:Product'); //em = 'entity manager'


				$product = $productRepository->find($id);

				 if($request->isMethod('POST')) {



				 	$name = $request->get('name');
				 	$price = $request->get('price');
				 	$description = $request->get('description');
				 	$picture = $request->files->get('picture');
				 	$category_id = $request->get('category');

				 	$category = $em->getRepository('HTMainBundle:Category')->findById($category_id)[0];

				 	if(empty($name)) {

				 		$error['name'] = "Veuillez remplir le champ \"Nom\".";
				 	}

				 	if(empty($price)) {

				 		$error['price'] = "Veuillez remplir le champ \"Price\".";
				 	}

				 	if(!is_numeric($price)) {

				 		$error['price'] = "Le prix doit être un nombre.";
				 	}

				 	if(empty($description)) {

				 		$error['description'] = "Veuillez remplir le champ \"Description\".";
				 	}

				 	if(empty($category)) {

				 		$error['category'] = "Veuillez remplir le champ \"Catégorie\".";
				 	}



				 	if($picture == NULL && $product->getPicture() == NULL ) {

				 		$error['picture'] = "Veuillez ajouter une image pour illustrer votre Shop.";
				 	}

				 	else {
				 		$mime=$picture->guessClientExtension();
				 		$uploadName = uniqid("doc_", true).'.'.$mime;
				 		if(!$this->upload($picture, $uploadName, 1000000, array('png','gif','jpg','jpeg') )  ) {
				 			$error['picture'] = "Votre fichier n'est pas à un format autorisé et/ou est trop volumineux.";
				 		}

				 	}



					if(empty($error)) {





						$product->setShop($shop);
						$product->setCategory($category);
						$product->setPicture($uploadName);
						$product->setDescription($description);
						$product->setPrice($price);
						$product->setName($name);

						$em->persist($product);

						$em->flush();

						$success = "Le produit a bien été ajouté à votre shop.";


					}


			}



			return $this->render('HTMainBundle:Main:updateProduct.html.twig', array(
							'title' => $this->title,
							'success' => $success,
							'error' => $error,
							'pagename' => $pageName,
							'product' => $product
				));

		}


		public function ajaxCallProductAction($id , Request $request) {

			// if($request->query->get('req') == "remove-product") {
			// 		$em = $this->getDoctrine()->getManager();
			// 		$productRepository = $em->getRepository('HTMainBundle:Product');
			// 		$products = $productRepository->findAll();
			// 		$statut = "";
			// 		$productId = $request->query->get('id');
			// 		// dump($productId);
			// 		$productToRemove = $productRepository->findOneById($productId);
			// 		// dump($productToRemove);
			// 		if(	$productToRemove != null ) {
			// 		$em->remove($productToRemove);
			// 		$em->flush();
			// 		}

			// 		}

						$em = $this->getDoctrine()->getManager();
					$productRepository = $em->getRepository('HTMainBundle:Product');
					$products = $productRepository->findAll();
					$statut = "";
					// $productId = $request->query->get('id');
					// dump($productId);
					$productToRemove = $productRepository->findOneById($id);
					// dump($productToRemove);
					if(	$productToRemove != null ) {
					$em->remove($productToRemove);
					$em->flush();
					}

					return $this->redirectToRoute('ht_main_addProduct');

					// return $this->render('HTMainBundle:Main:ajaxTabProduct.html.twig', array(
					// 		'products' => $products
					// 	));

		}


			public function updateSellerAction($id , Request $request) { // l'objet request sert à récupérer les données du formulaire

				if(!$this->get('security.authorization_checker')->isGranted('ROLE_SELLER')) {

					throw new AccessDeniedException("Accès limité aux vendeurs de thés. ");

				}

				$pageName = "ajouter vendeur";
				$nameTest = "";
				$adressTest="";
				$error = [];
		    	$success = "";

		    	$em = $this->getDoctrine()->getManager();
		    	$shopRepository = $em->getRepository('HTMainBundle:Shop');
		    	$shop = $shopRepository->find($id);

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

				if(!empty($openingTimes)) {

					if($openingTimes['opening'] > $openingTimes['closing']) {

						$error['openingTimes'] = "L'heure d'ouverture doit être antérieur à l'heure de fermeture.";
					}
				}



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

				if($logo == NULL && $shop->getLogo() == null ) {

					$error['logo'] = "Veuillez ajouter une image pour illustrer votre Shop.";
				}

				elseif($logo != NULL ) {
					$mime=$logo->guessClientExtension();
					$uploadName = uniqid("doc_", true).'.'.$mime;
					if(!$this->upload($logo, $uploadName, 1000000, array('png','gif','jpg','jpeg') )  ) {
						$error['logo'] = "Votre fichier n'est pas à un format autorisé et/ou est trop volumineux.";
					}

				}

				 dump($error);



				//création de l'entité (objet qui nous sert à envoyer le nouveau vendeur dans la database)
				if(empty($error)) {



				dump($logo);





				 $shop->setName($name);
				 $shop->setAdress($adress);
				 $shop->setUrl($url);
				 $shop->setDescription($description);
				 $shop->setPhone($phone);
				 $shop->setOpeningTimes($openingTimes);
				 if($logo != NULL ) {
				 $shop->setLogo($uploadName);
				}
				 $shop->setUser($utilisateur);

				 $em = $this->getDoctrine()->getManager();

				 $em->persist($shop);

				 $em->flush();
				 $success = "Votre Shop a bien été modifié !";
				}

			 }

				return $this->render("HTMainBundle:Main:updateShop.html.twig", array(

						'title' => $this->title,
						'pageName' => $pageName,
						'error' => $error,
						'success' => $success,
						'shop' => $shop


					));


			}


		public function ajaxRateAction(Request $request) {
			$statut="";
			$em = $this->getDoctrine()->getManager();

			$user = $this->getUser();
			$productRate = $request->query->get('rate');
			$rateRepository = $em->getRepository('HTMainBundle:Rate');

			$id = $request->query->get('id');
			$productRepository = $em->getRepository('HTMainBundle:Product');
			$product = $productRepository->find($id);

			if( $rateRepository->findOneBy( array("user" => $user, "product" => $product) ) ) {
				$rate = $rateRepository->findOneBy( array("user" => $user, "product" => $product) );
			} else {
				$rate = new Rate;
				$rate->setUser($user);
				$rate->setProduct($product);
			}

			$rate->setRate($productRate);
			$em->persist($rate);
			$em->flush();

			return new JsonResponse($statut);
		}




}
