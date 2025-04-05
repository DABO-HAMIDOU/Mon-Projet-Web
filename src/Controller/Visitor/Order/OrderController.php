<?php
namespace App\Controller\Visitor\Order;


use App\Entity\User;
use App\Form\OrderFormType;
use App\Service\OrderService;
// use App\Service\Cart\CartService;
// use App\Service\Cart\CartService;
use App\Service\Cart\CartService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/order')]
final class OrderController extends AbstractController{
    #[Route('/recap', name: 'app_order_index', methods:['GET'])]
    public function __construct(
        private CartService $cartService,
        private OrderService $orderService
    ) {}

    #[Route('/recap', name: 'app_order_index', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {

        /** 
         * 1- Récupérons l'utilisateur actuellement connecté
         * 
         * @var User 
         */
        $user = $this->getUser();


       // 2- Vérifier si cet utilisateur a enregistré au moins une adresse de livraison
        if (\count($user->getAddresses()->toArray()) == 0) {
            $this->addFlash('warning', "Vous devez enregistrer au moins une adresse de livraison avant de passer votre commande.");
            return $this->redirectToRoute('user_address_index');
        }

       // 3- Vérifier si les produits sont toujours dans le panier
        if (count($this->cartService->getCartItems()) <= 0) {
            $this->addFlash('warning', "Un problème est survenu, veuillez rajouter les produits au panier.");
            return $this->redirectToRoute('user_cart_index');
        }

    //     4- Créer le formulaire de commande
        $form = $this->createForm(OrderFormType::class, null, [
            "user" => $user
        ]);

      // 6- Associons au formulaire, les données de la requête
        $form->handleRequest($request);

    //    7- Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
           
            $address = $form->get('address')->getData();
            $carrier = $form->get('carrier')->getData();
            // dd($address, $carrier );

            $this->orderService->persist($address, $carrier);

            return $this->redirectToRoute('app_payment_index');
        }

        // 5- Passer le formulaire à la vue
        return $this->render('pages/visitor/order/index.html.twig', [
            "form" => $form->createView(),
            "cartItems" => $this->cartService->getCartItems()
        ]);
    }
}
