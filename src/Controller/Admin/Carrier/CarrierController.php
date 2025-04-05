<?php

namespace App\Controller\Admin\Carrier;

use DateTimeImmutable;
use App\Entity\Carrier;
use App\Form\CarrierFormType;
use App\Repository\CarrierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]
class CarrierController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em,
        private CarrierRepository $carrierRepository
    ) {}
    #[Route('/carriers', name: 'admin_carrier_index', methods: ['GET'])]
    public function index(): Response
    {
        $carriers = $this->carrierRepository->findAll();
        return $this->render('pages/admin/carrier/index.html.twig', compact('carriers'));
    }


    #[Route('/carrier/create', name: 'admin_carrier_create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $carrier = new Carrier();

        $form = $this->createForm(CarrierFormType::class, $carrier);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $carrier->setCreatedAt(new DateTimeImmutable());
            $carrier->setUpdatedAt(new DateTimeImmutable());

            $this->em->persist($carrier);
            $this->em->flush();

            $this->addFlash("success", "Le livreur a été ajouté avec succès.");

            return $this->redirectToRoute("admin_carrier_index");
        }

        return $this->render('pages/admin/carrier/create.html.twig', [
            "form" => $form->createView()
        ]);
    }


    #[Route('/carrier/{id<\d+>}/edit', name: 'admin_carrier_edit', methods: ['GET', 'PUT'])]
    public function edit(Carrier $carrier, Request $request): Response
    {
        $form = $this->createForm(CarrierFormType::class, $carrier, [
            "method" => "PUT"
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $carrier->setUpdatedAt(new DateTimeImmutable());

            $this->em->persist($carrier);
            $this->em->flush();

            $this->addFlash("success", "Le livreur a été modifié.");

            return $this->redirectToRoute("admin_carrier_index");
        }

        return $this->render("pages/admin/carrier/edit.html.twig", [
            "form" => $form->createView()
        ]);
    }


    #[Route('/carrier/{id<\d+>}/delete', name: 'admin_carrier_delete', methods: ['DELETE'])]
    public function delete(carrier $carrier, Request $request): Response
    {
        if ($this->isCsrfTokenValid("delete_carrier_" . $carrier->getId(), $request->request->get('_crsf_token'))) {
            $this->em->remove($carrier);
            $this->em->flush();

            $this->addFlash("success", "Le livreur a été supprimé.");
        }

        return $this->redirectToRoute("admin_carrier_index");
    }
}
