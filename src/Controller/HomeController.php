<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @var ProductRepository
     */
    private $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $products = $this->repository->findLatest();
        return $this->render('home/index.html.twig', [
            'latestProducts' => $products
        ]);
    }
}
