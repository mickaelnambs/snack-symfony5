<?php

namespace App\Controller\BackOffice;

use App\Constant\MessageConstant;
use App\Controller\BaseController;
use App\Entity\Items;
use App\Form\ItemType;
use App\Repository\ItemsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ItemController.
 * 
 * @Route("/admin/items")
 * 
 * @author Mickael Nambinintsoa <mickael.nambinintsoa07081999@gmail.com>
 */
class ItemController extends BaseController
{
    /** @var ItemsRepository */
    private ItemsRepository $itemRepo;

    /**
     * ItemController constuctor.
     *
     * @param EntityManagerInterface $em
     * @param ItemsRepository $itemRepo
     */
    public function __construct(EntityManagerInterface $em, ItemsRepository $itemRepo)
    {
        parent::__construct($em);
        $this->itemRepo = $itemRepo;
    }

    /**
     * Permet d'avoir tous les items.
     * 
     * @Route("/", name="admin_item_manage", methods={"POST","GET"})
     *
     * @return Response
     */
    public function manage(): Response
    {
        return $this->render('back_office/item/manage.html.twig', [
            'items' => $this->itemRepo->findAll()
        ]);
    }

    /**
     * Permet d'ajouter un item.
     * 
     * @Route("/new", name="admin_item_new", methods={"POST","GET"})
     *
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $item = new Items();
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->save($item)) {
                $this->addFlash(
                    MessageConstant::SUCCESS_TYPE,
                    "L'item a été ajouté avec succès !"
                );
                return $this->redirectToRoute('admin_item_manage');
            }
        }
        return $this->render('back_office/item/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de modifier un item.
     * 
     * @Route("/{id}/edit", name="admin_item_edit", methods={"POST","GET"})
     *
     * @param Items $item
     * @param Request $request
     * @return Response
     */
    public function edit(Items $item, Request $request): Response
    {
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->save($item)) {
                $this->addFlash(
                    MessageConstant::SUCCESS_TYPE,
                    "L'item {$item->getName()} a bien été modifié avec succès !"
                );
                return $this->redirectToRoute('admin_item_manage');
            }
        }
        return $this->render('back_office/item/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer un item.
     * 
     * @Route("/{id}/delete", name="admin_item_delete")
     *
     * @param Items $item
     * @return Response
     */
    public function delete(Items $item): Response
    {
        if ($this->remove($item)) {
            $this->addFlash(
                MessageConstant::SUCCESS_TYPE,
                "L'item {$item->getName()} a bien été supprimé avec succès !"
            );
        }
        return $this->redirectToRoute('admin_item_manage');
    }
}