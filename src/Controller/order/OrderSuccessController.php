<?php

namespace App\Controller\order;

use App\Entity\Order;
use App\Helper\Cart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderSuccessController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/order/success/{stripeSessionId}", name="order.success")
     */
    public function index($stripeSessionId, Cart $cart): Response
    {
        $order = $this->em->getRepository(Order::class)->findOneByStripeSession($stripeSessionId);
        if (!$order || $order->getUser() != $this->getUser()) {
            $this->redirectToRoute('home');
        }

        if (!$order->getIsPaid()) {
            $cart->removeAll();
            $order->setIsPaid(1);
            $this->em->flush($order);
        }

        return $this->render('order/success.html.twig', [
            'order' => $order,
        ]);
    }
}
