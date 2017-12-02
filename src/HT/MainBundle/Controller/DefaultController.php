<?php

namespace HT\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('HTMainBundle:Default:index.html.twig');
    }
}
