<?php
namespace App\Controller\Visitor\Payment;


use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/payment')]
final class PaymentController extends AbstractController
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly EntityManagerInterface $entityManager
    ) {}

    #[Route('/', name: 'app_payment_index', methods: ['GET'])]
    public function index(): Response
    {
        // 🔒 Récupère l'utilisateur connecté
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('warning', 'Vous devez être connecté pour accéder à la page de paiement.');
            return $this->redirectToRoute('app_login');
        }

        // 📦 Récupère la dernière commande de l'utilisateur (ex: en attente ou en cours)
        $order = $this->entityManager
            ->getRepository(Order::class)
            ->findOneBy(
                ['user' => $user, 'status' => Order::STATUS_PENDING], // Filtrage par utilisateur et statut
                ['orderedAt' => 'DESC'] // Tri par la date de la commande, de la plus récente à la plus ancienne
            );
            
          

        if (!$order) {
            $this->addFlash('danger', 'Aucune commande trouvée.');
            return $this->redirectToRoute('app_cart_index');
        }

        return $this->render('pages/visitor/payment/index.html.twig', [
            'order' => $order,
        ]);
    }

    // Nouvelle route pour valider le paiement
    #[Route('/validate', name: 'app_payment_validate', methods: ['GET', 'POST'])]
    public function validate(): Response
    {
        // Vérifie que l'utilisateur est connecté
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('warning', 'Vous devez être connecté pour accéder à la page de validation.');
            return $this->redirectToRoute('app_login');
        }

        // // Récupère la dernière commande en attente
        $order = $this->entityManager
            ->getRepository(Order::class)
            ->findOneBy(
                ['user' => $user, 'status' => Order::STATUS_PENDING],
                ['orderedAt' => 'DESC']
            );

        if (!$order) {
            $this->addFlash('danger', 'Aucune commande trouvée.');
            return $this->redirectToRoute('app_cart_index');
        }

        // Affiche la page de validation du paiement
        return $this->render('pages/visitor/payment/validate.html.twig');
    }

    #[Route('/process', name: 'app_payment_process', methods: ['GET','POST'])]
    public function process(Request $request): Response
    {
        // Vérifie que l'utilisateur est connecté
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('warning', 'Vous devez être connecté pour procéder au paiement.');
            return $this->redirectToRoute('app_login');
        }

        // Récupère la dernière commande de l'utilisateur
        $order = $this->entityManager
            ->getRepository(Order::class)
            ->findOneBy(
                ['user' => $user, 'status' => Order::STATUS_PENDING],
                ['orderedAt' => 'DESC']
            );

        if (!$order) {
            $this->addFlash('danger', 'Aucune commande trouvée.');
            return $this->redirectToRoute('app_cart_index');
        }

        // Récupérer les informations du formulaire
        $cardNumber = $request->request->get('card_number');
        $expirationDate = $request->request->get('expiration_date');
        $cvv = $request->request->get('cvv');

        // Simuler un paiement
        $paymentSuccess = $this->simulatePayment($cardNumber, $expirationDate, $cvv);

        if ($paymentSuccess) {
            // Marquer la commande comme payée
            $order->setStatus(Order::STATUS_PAID);
            $this->entityManager->flush();

            $this->addFlash('success', 'Le paiement a été effectué avec succès !');
            return $this->redirectToRoute('app_order_success');
        } else {
            $this->addFlash('danger', 'Le paiement a échoué. Vérifiez vos informations et réessayez.');
            return $this->redirectToRoute('app_payment_validate');
        }
    }


    // Méthode pour afficher la page de succès de commande
    #[Route('/order/success', name: 'app_order_success')]
    public function orderSuccess(): Response
    {
        // Affiche la page de succès après le paiement
        return $this->render('pages/visitor/payment/order_success.html.twig');
    }

    private function simulatePayment($cardNumber, $expirationDate, $cvv): bool
    {
        // Simuler un paiement pour les tests
        return true; // Toujours réussi pour les tests
    }
}

