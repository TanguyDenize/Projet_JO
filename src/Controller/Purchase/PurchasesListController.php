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

class PurchasesListController extends AbstractController
{
    protected $security;
    protected $router;
    protected $twig;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @Route("/purchases", name="purchase_index")
     */
    public function index()
    {
        /** @var User */
        $user = $this->getUser();

        if(!$user)
        {
            $url = $this->router->generate('security_login');
            return new RedirectResponse($url);
        }

        // Affichage des commandes de l'utilisateur qui est connecté
        return $this->render('purchase/index.html.twig', [ 
            'purchases' => $user->getPurchases(),
            'tickets' => $user->getTickets()
        ]);
    }
}