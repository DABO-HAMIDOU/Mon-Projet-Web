<?php
namespace App\Controller\User\Address;


use DateTimeImmutable;
use App\Entity\Address;
use App\Form\AddressFormType;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/user')]
class AddressController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {}

    #[Route('/addresses', name: 'user_address_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('pages/user/address/index.html.twig');
    }

    #[Route('/address/create', name: 'user_address_create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        /** @var Address */
        $address = new Address();

        $form = $this->createForm(AddressFormType::class, $address);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $address->setCreatedAt(new DateTimeImmutable());
            $address->setUpdatedAt(new DateTimeImmutable());
            $address->setUser($this->getUser());

            $this->em->persist($address);

            $this->em->flush();

            $this->addFlash("success", "L'address a été ajoutée.");

            return $this->redirectToRoute('user_address_index');
        }

        return $this->render('pages/user/address/create.html.twig', [
            "form" => $form->createView()
        ]);
    }

    #[Route('/address/{id<\d+>}/edit', name: 'user_address_edit', methods: ['GET', 'PUT'])]
    public function edit(Address $address, Request $request): Response
    {

        $form = $this->createForm(AddressFormType::class, $address, [
            "method" => "PUT"
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $address->setUpdatedAt(new DateTimeImmutable());
            $address->setUser($this->getUser());

            $this->em->persist($address);

            $this->em->flush();

            $this->addFlash("success", "L'address a été modifié.");

            return $this->redirectToRoute('user_address_index');
        }

        return $this->render('pages/user/address/edit.html.twig', [
            "form" => $form->createView()
        ]);
    }


    #[Route('/address/{id<\d+>}/delete', name: 'user_address_delete', methods: ['DELETE'])]
    public function delete(Address $address, Request $request): Response
    {
        if ($this->isCsrfTokenValid("delete_address_" . $address->getId(), $request->request->get('_csrf_token'))) {
            $this->em->remove($address);
            $this->em->flush();

            $this->addFlash("success", "L'addresse a été supprimée.");
        }

        return $this->redirectToRoute("user_address_index");
    }
}
