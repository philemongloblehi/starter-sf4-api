<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ExpertController extends Controller
{
    /**
     * @Route("/expert", name="expert")
     */
    public function index()
    {
        return $this->render('expert/index.html.twig', [
            'controller_name' => 'ExpertController',
        ]);
    }
}
