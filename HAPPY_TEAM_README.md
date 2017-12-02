Architecture dans Symfony : 

Dans happyTea/src/HT/Resources/view se trouve les vues de notre site en twig. 

Dans happyTea/src/HT/MainBundle/Controller  se trouve le contrôleur de notre site ainsi que ses modèle. Le controller se nomme ici MainController.php 

Dans happyTea/src/HT/Ressources/config se trouvent les fichiers de config, particulièrement le fichier routing.yml qui permet de configurer les routes, qui sont les urls de nos pages.

Enfin, dans happyTea/app/config/parameters.yml se trouvent les paramètres d'accès à la base de donnees. Il faudra les changer pour avoir accès à la votre.  

Pour appeler la page principal : [votrelocalhost]/happyTea/web/app_dev.php/main.

On rajoute ensuite le path situé dans le fichier routing.yml pour accéder au pages particulière (happyTea/src/HT/Ressources/config/routing.yml) . 

Par exemple, pour accéder à la page catégorie, on tape : 

[votrelocalhost]/happyTea/web/app_dev.php/main/categories 


Le front se fera donc dans les fichiers twig situer dans happyTea/src/HT/Resources/view. 

Le back se fera donc dans les fichiers Controller situer dans happyTea/src/HT/MainBundle/Controller 



Pour rajouter une page : 
Dans routin.yml, on crée le routing : 


ht_main_homepage:
    path:     /
    defaults: { _controller: HTMainBundle:Main:index }




« ht_main_homepage » est le nom de la route  
le path est ce qu’il faudra taper dans l’URL pour y accéder 
default est le controller qu’il appelera une fois sur L’url. En l’occurrence, celui-ci se trouve dans le bundle  « HTMainBundle », le controlleur s’appelle « Main » et la fonction/methode s’appel « indexAction »

On crée ensuite un contrôleur dans  happyTea/src/HT/MainBundle/Controller  qui s’appelera MainController , qui héritera de la classe controleur de symfony. Le MainController est déjà créé, il suffit donc de créer une methode (function) dans la class MainController qui s’appelera indexAction. 


//Dans happyTea/src/HT/MainBundle/Controller/MainController 

class MainController extends controller { 

	protected $title = "HappyTea";

	public function indexAction() { //c'est le modèle qui fais la logique de la page 

		
		$pageName = "d'accueil";

		return $this->render("HTMainBundle:Main:index.html.twig", array( //et qui appel la vue de la page
				'title' => $this->title, 
				'pageName' => $pageName,

			));


	}

	//on peut rajouter ici d'autres modèles 
}



Il reste ensuite à créer la page en twig qui est appelé dans la méthode render. En l’occurrence celle-ci s’appellera « index.html.twig ». 

Voilà, normalement vous êtes désormais en mesure de créer votre page dans symfony. Je vous invite à essayer par vous-même ;) 



===========================================================


Formulaire : 

A la page addSeller ( pour y accéder :
[votrelocalhost]/happyTea/web/app_dev.php/main/addSeller  )

se trouve un formulaire test qui ajoute un vendeur de thé à la base de données. 


Dans happyTea/src/HT/MainBundle/Controller/MainController, 

se trouve le modèle addSellerAction qui s'occupe de traiter le formulaire. les commentaires vous aiderons à comprendre le fonctionnement des formulaires sous symfonie. 

Dans happyTea/src/HT/MainBundle/Entity/seller.php, 

se trouve l'entité qui nous sert à envoyer le nouveau vendeur dans la base de données


