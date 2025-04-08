<?php
namespace App\Service;

use App\Entity\User;
use DateTimeImmutable;
use App\Entity\Address;
use App\Entity\Carrier;
use App\Entity\OrderItem;
use App\Service\Cart\CartService;
use App\Entity\Order;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class OrderService
{
    public function __construct(
        private EntityManagerInterface $em,
        private Security $security,
        private CartService $cartService
    ) {}

    public function persist(Address $address, Carrier $carrier): void
    {
        $order = new Order();

        /** @var User */
        $user = $this->security->getUser();

        $order
            ->setUser($user)
            ->setUserEmail($user->getEmail())
            ->setDeliveryFirstName($address->getFirstName())
            ->setDeliveryLastName($address->getLastName())
            ->setDeliveryEmail($address->getEmail())
            ->setDeliveryPhone($address->getPhone())
            ->setDeliveryStreet($address->getStreet())
            ->setDeliveryPostalCode($address->getPostalCode())
            ->setDeliveryCity($address->getCity())
            ->setDeliveryCountry($address->getCountry())
            ->setTotalAmount($this->cartService->getCartTotalAmount())
            ->setStatus(Order::STATUS_PENDING)
            ->setCarrierName($carrier->getName())
            ->setOrderedAt(new DateTimeImmutable())
            ->setUpdatedAt(new DateTimeImmutable())
        ;

        $this->em->persist($order);

        foreach ($this->cartService->getCartItems() as $cartItem) {
            $orderItem = new OrderItem();

            $orderItem
                ->setTheOrder($order)
                ->setProduct($cartItem->product)
                ->setProductName($cartItem->product->getName())
                ->setProductPrice($cartItem->product->getSellingPrice())
                ->setProductQuantity($cartItem->quantity)
                ->setTotalAmount($cartItem->getAmount())
            ;

            $this->em->persist($orderItem);
        }

        $this->em->flush();
    }
}
