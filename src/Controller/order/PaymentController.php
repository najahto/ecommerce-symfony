<?php

namespace App\Controller\order;

use App\Entity\Order;
use App\Entity\Product;
use App\Helper\Cart;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{
    /**
     * @Route("/order/create-session/{reference}", name="payment.create.session")
     */
    public function index(string $reference, Cart $cart, EntityManagerInterface $em): RedirectResponse
    {
        $stripeProducts = [];
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';

        $order = $em->getRepository(Order::class)->findOneByReference($reference);

        if (!$order) {
            return $this->redirectToRoute('order');
        }

        foreach ($order->getOrderDetails()->getValues() as $productDetail) {
            $product = $em->getRepository(Product::class)->findOneByName($productDetail->getProduct());
            $stripeProducts[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $productDetail->getProduct(),
                        'images' => [$YOUR_DOMAIN . "/images/products/" . $product->getImage()],
                    ],
                    'unit_amount' => $productDetail->getPrice(),
                ],
                'quantity' => $productDetail->getQuantity(),
            ];
        }

        // shipping costs
        $stripeProducts[] = [
            'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                    'name' => 'Shipping: ' . $order->getCarrierName(),
                ],
                'unit_amount' => $order->getCarrierPrice(),
            ],
            'quantity' => 1,
        ];

        Stripe::setApiKey('sk_test_7OiBL9r9SjFpXQkkZOh7cZNO00X5rT39hg');

        $checkout_session = Session::create([
            'customer_email' => $this->getUser()->getEmail(),
            'payment_method_types' => ['card'],
            'line_items' => [
                $stripeProducts
            ],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/order/success/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/order/cancel/{CHECKOUT_SESSION_ID}',
        ]);
        $order->setStripeSession($checkout_session->id);
        $em->flush($order);

        return new RedirectResponse($checkout_session->url);
    }
}
