<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    /**
     * @Route("/panier", name="panier_user")
     */
    public function panier_show()
    {
        return $this->render('panier/panier_show.html.twig', [
            'name' => 'Sagnol',
        ]);
    }
}
