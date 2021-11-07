<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{


    /**
     * @var ProductRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em, ProductRepository $repository)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/products", name="products.index")
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $products = $paginator->paginate(
            $this->repository->findAll(),
            $request->query->getInt('page', 1),
            5
        );
        return $this->render('product/index.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * @Route("/product/{slug}/{id}", name="product.show")
     */
    public function show(Product $product, string $slug): Response
    {
        if ($product->getSlug() !== $slug) {
            return $this->redirectToRoute(
                'product.show',
                [
                    'id' => $product->getId(),
                    'slug' => $product->getSlug(),
                ],
                301
            );
        }
        return $this->render('product/show.html.twig', [
            'controller_name' => 'ProductController',
            'product' => $product
        ]);
    }
}
