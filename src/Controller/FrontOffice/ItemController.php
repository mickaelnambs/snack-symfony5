<?php

namespace App\Controller\FrontOffice;

use App\Entity\Items;
use App\Controller\BaseController;
use App\Repository\ItemsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ItemController.
 * 
 * @author Mickael Nambinintsoa <mickael.nambinintsoa07081999@gmail.com>
 */
class ItemController extends BaseController
{
    /** @var ItemsRepository */
    protected ItemsRepository $itemRepository;

    /**
     * ItemController constructor.
     *
     * @param ItemsRepository $itemRepository
     */
    public function __construct(ItemsRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    /**
     * Permet d'afficher tous les items.
     * 
     * @Route("/items", name="item_index", methods={"POST","GET"})
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('front_office/item/index.html.twig', [
            'items' => $this->itemRepository->findAll()
        ]);
    }

    /**
     * Permet d'afficher le detail d'un item.
     * 
     * @Route("/{slug}-{id}", name="item_show", requirements={"slug": "[a-z0-9\-]*"})
     *
     * @param string $slug
     * @param Items $item
     * 
     * @return Response
     */
    public function show(string $slug, Items $item)
    {
        if ($item->getSlug() !== $slug) {
            $this->redirectToRoute('item_show', [
                'id' => $item->getId(),
                'slug' => $item->getSlug()
            ]);
        }
        return $this->render('front_office/item/show.html.twig', [
            'item' => $item
        ]);
    }

    /**
     * @Route("/search", name="item_search", methods={"GET"})
     *
     * @param Request $request
     * @return Response
     */
    public function search(Request $request): Response
    {
        $query = $request->query->get('q', '');
        $limit = $request->query->get('l', 10);

        if (!$request->isXmlHttpRequest()) {
            return $this->render('front_office/item/search.html.twig', ['query' => $query]);
        }

        $foundItems = $this->itemRepository->findBySearchQuery($query, $limit);

        $results = [];
        foreach ($foundItems as $item) {
            $results[] = [
                'name' => htmlspecialchars($item->getName(), ENT_COMPAT | ENT_HTML5),
                'description' => htmlspecialchars($item->getDescription(), ENT_COMPAT | ENT_HTML5),
                'price' => htmlspecialchars($item->getPrice(), ENT_COMPAT | ENT_HTML5),
                'date' => $item->getCreatedAt(),
                'url' => $this->generateUrl('item_show', ['slug' => $item->getSlug(), 'id' => $item->getId()]),
            ];
        }

        return $this->json($results);
    }
}
