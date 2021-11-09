<?php

namespace App\Controller\account;

use App\Entity\Address;
use App\Form\AddressType;
use App\Helper\Cart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountAddressController extends AbstractController
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
     * @Route("/account/address", name="account.address")
     */
    public function index(): Response
    {
        $addresses = $this->getUser()->getAddresses();
        return $this->render('account/address/index.html.twig', [
            'addresses' => $addresses
        ]);
    }

    /**
     * @Route("/account/address/new", name="account.address.new")
     */
    public function new(Request $request, Cart $cart): Response
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $address->setUser($this->getUser());
            $this->em->persist($address);
            $this->em->flush();

            if ($cart->get()) {
                return $this->redirectToRoute('order');
            } else {
                $this->addFlash('success', 'Your address added successfully');
                return $this->redirectToRoute('account.address');
            }
        }
        return $this->render('account/address/new.html.twig', [
            'address' => $address,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/account/address/{id}", name="account.address.edit",methods={"GET", "POST"})
     */
    public function edit(Address $address, Request $request): Response
    {
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Your address updated successfully');
            return $this->redirectToRoute('account.address');
        }
        return $this->render('account/address/edit.html.twig', [
            'address' => $address,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/account/address/{id}", name="account.address.delete", methods={"DELETE"})
     */
    public function delete(Address $address, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete' . $address->getId(), $request->request->get('_token'))) {
            $this->em->remove($address);
            $this->em->flush();
            $this->addFlash('success', 'address deleted successfully');
        }
        return $this->redirectToRoute('account.address');
    }
}
