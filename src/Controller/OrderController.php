<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Form\OrderType;
use App\Helper\Cart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
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
     * @Route("/order", name="order")
     */
    public function index(Cart $cart, Request $request): Response
    {
        if (!$this->getUser()->getAddresses()->getValues()) {
            return $this->redirectToRoute('account.address.new');
        }
        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser(),
        ]);

        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'cart' => $cart->getAll($cart)
        ]);
    }

    /**
     * @Route("/order/recap", name="order.recap", methods={"POST"})
     */
    public function add(Cart $cart, Request $request): Response
    {
        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // save my order Order()
            $order = new Order();
            $carriers = $form->get('carriers')->getData();

            $delivery = $form->get('addresses')->getData();
            $delivery_content = $delivery->getFirstname() . ' ' . $delivery->getLastname();
            $delivery_content .= '<br/>' . $delivery->getPhone();
            if ($delivery->getCompany()) {
                $delivery_content .= '<br/>' . $delivery->getCompany();
            }
            $delivery_content .= '<br/>' . $delivery->getAddress();
            $delivery_content .= '<br/>' . $delivery->getPostalCode() . ' ' . $delivery->getCity();
            $delivery_content .= '<br/>' . $delivery->getCountry();

            $order->setUser($this->getUser());
            $order->setCarrierName($carriers->getName());
            $order->setCarrierPrice($carriers->getPrice());
            $order->setDelivery($delivery_content);
            $order->setIsPaid(0);

            $this->em->persist($order);

            // save my products OrderDetails()
            foreach ($cart->getAll() as $product) {
                $orderDetails = new OrderDetails();
                $orderDetails->setMyOrder($order);
                $orderDetails->setProduct($product['product']->getName());
                $orderDetails->setQuantity($product['quantity']);
                $orderDetails->setPrice($product['product']->getPrice());
                $orderDetails->setTotal(($product['product']->getPrice() * $product['quantity']));
                $this->em->persist($orderDetails);
            }

            $this->em->flush();

            return $this->render('order/add.html.twig', [
                'cart' => $cart->getAll($cart),
                'carrier' => $carriers,
                'delivery' => $delivery_content
            ]);
        }
        return $this->redirectToRoute('cart');

    }
}
