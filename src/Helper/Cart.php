<?php

namespace App\Helper;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart
{

    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(SessionInterface $session, EntityManagerInterface $em)
    {
        $this->session = $session;
        $this->em = $em;
    }

    public function getAll($cart): array
    {
        $cartItems = [];

        if ($cart->get()) {
            foreach ($cart->get() as $id => $quantity) {
                $product = $this->em->find(Product::class, $id);
                if (!$product) {
                    $this->remove($id);
                }
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $quantity
                ];
            }
        }
        return $cartItems;
    }

    public function add(int $id)
    {
        $cart = $this->session->get('cart', []);

        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        $this->session->set('cart', $cart);
    }

    public function decrease($id)
    {
        $cart = $this->session->get('cart', []);

        if ($cart[$id] > 1) {
            $cart[$id]--;
        } else {
            unset($cart[$id]);
        }

        return $this->session->set('cart', $cart);
    }

    public function remove($id)
    {
        $cart = $this->session->get('cart', []);
        unset($cart[$id]);

        return $this->session->set('cart', $cart);
    }

    public function removeAll()
    {
        $this->session->remove('cart');
    }

    public function get()
    {
        return $this->session->get('cart');
    }
}