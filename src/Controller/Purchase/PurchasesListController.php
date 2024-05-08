<?php

namespace App\Controller\Purchase;

use App\Entity\User;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class PurchasesListController extends AbstractController
{
    protected $security;
    protected $router;
    protected $twig;

    /**
     * @Route("/purchases", name="purchase_index")
     * @IsGranted("ROLE_USER")
     */
    public function index()
    {
        /** @var User */
        $user = $this->getUser();

        // Affichage des commandes de l'utilisateur qui est connecté
        return $this->render('purchase/index.html.twig', [ 
            'purchases' => $user->getPurchases()
        ]);
    }
}