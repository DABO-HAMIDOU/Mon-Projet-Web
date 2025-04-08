<?php

namespace App\Controller\Visitor\Contact;

use DateTimeImmutable;
use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ContactController extends AbstractController
{

    // Injection de EntityManagerInterface dans le constructeur
    public function __construct(private EntityManagerInterface $em) 
    {

    }


    #[Route('/contact', name: 'visitor_contact_index', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $em ): Response
    {
        // Crée une nouvelle instance de l'entité Contact
        $contact = new Contact();

        // Crée le formulaire
        $form = $this->createForm(ContactType::class, $contact);

        // Gère la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Récupère les données du formulaire
            $firstName = $contact->getFirstName();
            $lastName = $contact->getLastName();
            $email = $contact->getEmail();
            $phone = $contact->getPhone();

            // // Affiche les données récupérées (pour debugging)
            // dd($firstName, $lastName, $email, $phone);

            // Enregistrer les données dans la base de données
            $contact->setCreatedAt(new DateTimeImmutable());
            $contact->setUpdatedAt(new DateTimeImmutable());
            $this->em->persist($contact);
            $this->em->flush();
            // Afficher un message de succès ou rediriger
            $this->addFlash('success', 'Votre message a été envoyé avec succès !');
            return $this->redirectToRoute('visitor_contact_index'); // Ou vers une autre page
        }
        
        // Passer le formulaire à la vue
        return $this->render('pages/visitor/contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
