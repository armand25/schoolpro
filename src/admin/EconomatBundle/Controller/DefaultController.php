<?php

namespace admin\EconomatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('adminEconomatBundle:Default:index.html.twig');
    }
}
