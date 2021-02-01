<?php

namespace App\Controller\FrontOffice;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Constant\MessageConstant;
use App\Controller\BaseController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class AccountController.
 * 
 * @author Mickael Nambinintsoa <mickael.nambinintsoa07081999@gmail.com>
 */
class AccountController extends BaseController
{
    /** @var UserPasswordEncoderInterface */
    private UserPasswordEncoderInterface $encoder;

    /**
     * AccountController constructor.
     *
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        parent::__construct($em);
        $this->encoder = $encoder;
    }

    /**
     * Login.
     * 
     * @Route("/login", name="app_login", methods={"POST","GET"})
     *
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('front_office/account/login.html.twig', [
            'username' => $authenticationUtils->getLastUsername(),
            'hasError' => $authenticationUtils->getLastAuthenticationError()
        ]);
    }

    /**
     * Permettant de creer un compte utilisateur.
     * 
     * @Route("/register", name="app_register", methods={"POST","GET"})
     *
     * @param Request $request
     * @return Response
     */
    public function register(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $this->encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            if ($this->save($user)) {
                $this->addFlash(
                    MessageConstant::SUCCESS_TYPE,
                    "Votre compte a bien été créé ! Vous pouvez maintenant vous connecter !"
                );
                return $this->redirectToRoute("app_login");
            }
        }
        return $this->render('front_office/account/register.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * Logout.
     * 
     * @Route("/logout", name="app_logout")
     *
     * @return void
     */
    public function logout(): void
    {
        // Empty.
    }
}