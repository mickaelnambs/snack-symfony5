<?php

namespace App\Controller\BackOffice;

use App\Entity\User;
use App\Form\EditUserType;
use App\Form\RegistrationType;
use App\Constant\MessageConstant;
use App\Controller\BaseController;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class AccountController.
 * 
 * @Route("/admin/accounts")
 * 
 * @author Mickael Nambinintsoa <mickael.nambinintsoa07081999@gmail.com>
 */
class AccountController extends BaseController
{
    /** @var UserRepository */
    protected UserRepository $userRepository;

    /** @var UserPasswordEncoderInterface */
    protected UserPasswordEncoderInterface $passwordEncoder;

    /**
     * AccountController constructor.
     *
     * @param EntityManagerInterface $em
     * @param UserRepository $userRepository
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(
        EntityManagerInterface $em,
        UserRepository $userRepository,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        parent::__construct($em);
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Permet de recuperer tous les utilisateurs
     * 
     * @Route("/", name="admin_account_manage", methods={"POST","GET"})
     *
     * @return Response
     */
    public function manage(): Response
    {
        return $this->render('back_office/account/manage.html.twig', [
            'users' => $this->userRepository->findAll()
        ]);
    }

    /**
     * Permettant de creer un compte utilisateur.
     * 
     * @Route("/register", name="admin_account_register", methods={"POST","GET"})
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
            $hash = $this->passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            if ($this->save($user)) {
                $this->addFlash(
                    MessageConstant::SUCCESS_TYPE,
                    "Le compte de {$user->getFirstName()} a bien été créé ! "
                );
                return $this->redirectToRoute("admin_account_manage");
            }
        }
        return $this->render('back_office/account/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Login.
     * 
     * @Route("/login", name="admin_account_login", methods={"POST","GET"})
     *
     * @param AuthenticationUtils $authenticationUtils
     * 
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('back_office/account/login.html.twig', [
            'username' => $authenticationUtils->getLastUsername(),
            'hasError' => $authenticationUtils->getLastAuthenticationError()
        ]);
    }

    /**
     * Logout.
     * 
     * @Route("/logout", name="admin_account_logout")
     *
     * @return void
     */
    public function logout()
    {
    }

    /**
     * Permet de modifier le compte d'un utilisateur.
     * 
     * @Route("/{id}/edit", name="admin_account_edit", methods={"POST","GET"})
     *
     * @param User $user
     * @param Request $request
     * @return Response
     */
    public function edit(User $user, Request $request): Response
    {
        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->save($user)) {
                $this->addFlash(
                    MessageConstant::SUCCESS_TYPE,
                    "Le profil de {$user->getFirstName()} a bien été modifié avec succès"
                );
                return $this->redirectToRoute('admin_account_manage');
            }
            return $this->redirectToRoute('admin_account_edit', ['id' => $user->getId()]);
        }
        return $this->render('back_office/account/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer un compte.
     * 
     * @Route("/{id}/delete", name="admin_account_delete")
     *
     * @param User $user
     * @return Response
     */
    public function delete(User $user): Response
    {
        if ($this->remove($user)) {
            $this->addFlash(
                MessageConstant::SUCCESS_TYPE,
                "Le profil de {$user->getFirstName()} a bien été supprimé avec succès"
            );
        }
        return $this->redirectToRoute('account_manage');
    }
}