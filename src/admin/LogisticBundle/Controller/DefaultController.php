<?php

namespace admin\LogisticBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('adminLogisticBundle:Default:index.html.twig');
    }
}
