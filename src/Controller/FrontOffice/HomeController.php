<?php

namespace App\Controller\FrontOffice;

use App\Controller\BaseController;
use App\Repository\ItemsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController.
 * 
 * @author Mickael Nambinintsoa <mickael.nambinintsoa07081999@email.com>
 */
class HomeController extends BaseController
{
    /**
     * @Route("/", name="app_home", methods={"GET"})
     */
    public function index(ItemsRepository $itemsRepository): Response
    {
        return $this->render('front_office/home/index.html.twig', [
            'items' => $itemsRepository->findAll()
        ]);
    }
}
