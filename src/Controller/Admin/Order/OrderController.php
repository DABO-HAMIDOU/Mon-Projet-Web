<?php

namespace App\Controller\Admin\Order;

use App\Entity\Order;
use App\Repository\OrderRepository;
use App\Form\EditOrderStatusFormType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]
class OrderController extends AbstractController
{
    public function __construct(
        private OrderRepository $orderRepository,
        private EntityManagerInterface $em
    )
    {
        
    }

    #[Route('/order/list', name: 'admin_order_index', methods:['GET'])]
    public function index(): Response
    {
        return $this->render('pages/admin/order/index.html.twig', [
            "orders" => $this->orderRepository->findAll()
        ]);
    }

    #[Route('/order/{id<\d+>}/edit/status', name: 'admin_order_status_edit', methods:['GET','POST'])]
    public function editStatus(Order $order, Request $request): Response
    {
        $form = $this->createForm(EditOrderStatusFormType::class, $order);

        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ) 
        {
          
            $order->setUpdatedAt(new DateTimeImmutable());

            $this->em->persist($order);
            $this->em->flush();

            $this->addFlash('success', "Le status de la commande a été modifié.");

            return $this->redirectToRoute('admin_order_index');
        }

        return $this->render('pages/admin/order/edit_status.html.twig', [
            "form" => $form->createView()
        ]);
    }


    #[Route('/order/{id<\d+>}/delete', name: 'admin_order_delete', methods: ['DELETE'])]
    public function delete(Order $order, Request $request) : Response
    {
        if ( $this->isCsrfTokenValid("delete_order_" . $order->getId(), $request->request->get('_crsf_token')) ) 
        {
            $this->em->remove($order);
            $this->em->flush();

            $this->addFlash("success", "La commande a été supprimée.");
        }

        return $this->redirectToRoute("admin_order_index");
    }
}
