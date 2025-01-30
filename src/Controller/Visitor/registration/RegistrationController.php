<?php

namespace App\Controller\Visitor\registration;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    public function __construct(private EmailVerifier $emailVerifier)
    {
    }

    #[Route('/register', name: 'visitor_registration_register', methods:['GET','POST'])]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // dd('pause');

              // encodons le  password
            /** @var string $password */
            $passwordhashed= $userPasswordHasher->hashPassword($user, $form->get('password')->getData());

            // Initialisons la propriété password avec le mot dec passe encodé
            $user->setPassword($passwordhashed);
          
            // inserons les données en base avec entityManager
            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('Visitor_registration_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('syllatechno999@gmail.com', 'SyllaTechno'))
                    ->to((string) $user->getEmail())
                    ->subject('validation de votre compte sur le site SyllaTechno')
                    ->htmlTemplate('emails/confirmation_email.html.twig')
            );

            // do anything else you need here, like send an email

            return $this->redirectToRoute('visitor_waiting_for_email_verification');
        }

        return $this->render('pages/visitor/registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
    //créeons une route de verification du mail après validation
    #[Route('/register/visitor_waiting_for_email_verification', name: 'visitor_waiting_for_email_verification', methods:['GET'])]
    public function waitingForEmailVerification(): Response
    {
       return $this->render('pages/visitor/registration/waiting_for_email_verification.html.twig');
    }

    #[Route('/verify/email', name: 'Visitor_registration_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator, UserRepository $userRepository): Response
    {
        $id = $request->query->get('id');

        if (null === $id) {
            return $this->redirectToRoute('visitor_registration_register');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('visitor_registration_register');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('visitor_registration_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Votre compte a bien été vérifié. Vous pouvez maintenant vous connecter.');

        return $this->redirectToRoute('app_welcome_visitor_index');
    }
}
