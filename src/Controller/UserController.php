<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserRegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    private $passwordEncoder;

    public function __construct(UserPasswordHasherInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/register", name="user_register")
     */
    public function register(Request $request, EntityManagerInterface $em): Response
    {
        $user = new User($this->passwordEncoder);
        $form = $this->createForm(UserRegistrationFormType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Encodez le mot de passe ici si nécessaire
            // $user->setPassword(...);

            $em->persist($user);
            $em->flush();

            // Redirigez l'utilisateur après l'inscription
            return $this->redirectToRoute('homepage');
        }


        return $this->render('security/register.html.twig', [
            'formView' => $form->createView(),
        ]);
    }
}
