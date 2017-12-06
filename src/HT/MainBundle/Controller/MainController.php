<?php 

namespace HT\MainBundle\Controller;

// c'est ici qu'est appelé les services (objets) de symfonie qui nous servirons dans ce controleur
use HT\MainBundle\Entity\seller; 
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


// notre controleur principal, c'est ici qu'il y aura toutes la logique du projet
class MainController extends controller {

	protected $title = "HappyTea"; 





	public function indexAction() { // le modèle pour la page index. les modèles finissent toujours par Action


		$pageName = "d'accueil";
						// on envoi la view index.html.twig
		return $this->render("HTMainBundle:Main:index.html.twig", array(
				'title' => $this->title, 
				'pageName' => $pageName,  // on envoie les variable dans notre page twig

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

		return $this->render("HTMainBundle:Main:teas.html.twig", array(

				'title' => $this->title,
				'pageName' => $pageName, 
				'id' => $id, 

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

		$pageName = "ajouter vendeur";
		$nameTest = "";
		$adressTest=""; 
		//on vérifie si le formulaire a bien été envoyé
		if($request->isMethod('POST')) {

			//$request->get('name');  est égal à $_POST['name'];
		$name=$request->get('name'); 
		$adress=$request->get('adress'); 

		//création de l'entité (objet qui nous sert à envoyer le nouveau vendeur dans la database)
		$seller = new seller(); 
		$seller->setName($name);
		$seller->setAdress($adress); 

		// on créé l'entity manager
		$em = $this->getDoctrine()->getManager();

		// Étape 1 : On « persiste » l'entité
		$em->persist($seller);

		    // Étape 2 : On « flush » tout ce qui a été persisté avant. l'entity manager envoie les informations dans la base de donnée
		$em->flush();

		// getRepository sert à récupérer les informations dans la base de donnees. on recupérere donc les données du vendeur que l'ont vient juste de créer
		//ceci remplace donc le select de mySql
		$sellerRepository = $em->getRepository('HTMainBundle:seller')->find($seller->getId()); 

		$nameTest= $sellerRepository->getName(); 
		$adressTest= $sellerRepository->getAdress(); 



		// et voilà ! pas besoin de reqête SQL, vous pouvez vérifier, le nouveau vendeur à bien été ajouter à la base de donnée ! 

	}

		return $this->render("HTMainBundle:Main:addSeller.html.twig", array(

				'title' => $this->title,
				'pageName' => $pageName, 
				'nameTest' => $nameTest, 
				'adressTest' => $adressTest, 


			));


	}



}