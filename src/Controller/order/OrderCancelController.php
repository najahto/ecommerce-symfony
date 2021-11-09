<?php

namespace App\Controller\order;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderCancelController extends AbstractController
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
     * @Route("/order/cancel/{stripeSessionId}", name="order.cancel")
     */
    public function index($stripeSessionId): Response
    {
        $order = $this->em->getRepository(Order::class)->findOneByStripeSession($stripeSessionId);
        if (!$order || $order->getUser() != $this->getUser()) {
            $this->redirectToRoute('home');
        }

        return $this->render('order/cancel.html.twig', [
            'order' => $order,
        ]);
    }
}
