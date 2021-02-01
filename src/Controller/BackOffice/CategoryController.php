<?php

namespace App\Controller\BackOffice;

use App\Constant\MessageConstant;
use App\Controller\BaseController;
use App\Entity\Categories;
use App\Form\CategoryType;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CategoryController.
 * 
 * @Route("/admin/categories")
 * 
 * @author Mickael Nambinintsoa <mickael.nambinintsoa07081999@gmail.com>
 */
class CategoryController extends BaseController
{
    /** @var CategoriesRepository */
    private CategoriesRepository $categoryRepo;

    /**
     * CategoryController constructor.
     *
     * @param EntityManagerInterface $em
     * @param CategoriesRepository $categoryRepo
     */
    public function __construct(EntityManagerInterface $em, CategoriesRepository $categoryRepo)
    {
        parent::__construct($em);
        $this->categoryRepo = $categoryRepo;
    }

    /**
     * Permet d'avoir toutes les categories.
     * 
     * @Route("/", name="admin_category_manage", methods={"POST","GET"})
     *
     * @return Response
     */
    public function manage(): Response
    {
        return $this->render('back_office/category/manage.html.twig', [
            'categories' => $this->categoryRepo->findAll()
        ]);
    }

    /**
     * Permet d'ajouter une nouvelle categorie.
     * 
     * @Route("/new", name="admin_category_new", methods={"POST","GET"})
     *
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $category = new Categories();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setOwner($this->getUser());
            if ($this->save($category)) {
                $this->addFlash(
                    MessageConstant::SUCCESS_TYPE,
                    "La catégorie a été ajoutée avec succès !"
                );
                return $this->redirectToRoute('admin_category_manage');
            }
        }
        return $this->render('back_office/category/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de modifier une categorie.
     * 
     * @Route("/{id}/edit", name="admin_category_edit", methods={"POST","GET"})
     *
     * @param Categories $category
     * @param Request $request
     * @return Response
     */
    public function edit(Categories $category, Request $request): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setOwner($this->getUser());
            if ($this->save($category)) {
                $this->addFlash(
                    MessageConstant::SUCCESS_TYPE,
                    "La catégorie {$category->getTitle()} a bien été modifiée avec succès !"
                );
                return $this->redirectToRoute('admin_category_manage');
            }
        }
        return $this->render('back_office/category/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer une categorie.
     * 
     * @Route("/{id}/delete", name="admin_category_delete")
     *
     * @param Categories $category
     * @return Response
     */
    public function delete(Categories $category): Response
    {
        if ($this->remove($category)) {
            $this->addFlash(
                MessageConstant::SUCCESS_TYPE,
                "La catégorie {$category->getTitle()} a bien été supprimée avec succès !"
            );
        }
        return $this->redirectToRoute('admin_category_manage');
    }
}
