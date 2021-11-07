<?php

namespace App\Controller;

use App\Entity\Product;
use App\Helper\Cart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{

    /**
     * @Route("/cart", name="cart")
     */
    public function index(Cart $cart): Response
    {

        return $this->render('cart/index.html.twig', [
            'cart' => $cart->getAll($cart),
        ]);
    }

    /**
     * @Route("/cart/add/{id}", name="cart.add")
     */
    public function add(int $id, Cart $cart): Response
    {
        $cart->add($id);
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/decrease/{id}", name="cart.decrease")
     */
    public function decrease(int $id, Cart $cart): Response
    {
        $cart->decrease($id);
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/remove/{id}", name="cart.remove")
     */
    public function remove(int $id, Cart $cart): Response
    {
        $cart->remove($id);
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/clear", name="cart.clear")
     */
    public function clear(Cart $cart): Response
    {
        $cart->removeAll();
        return $this->redirectToRoute('cart');
    }
}
