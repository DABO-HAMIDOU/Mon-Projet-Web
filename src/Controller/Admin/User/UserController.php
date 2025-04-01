<?php
namespace App\Controller\Admin\User;


use App\Entity\User;
use App\Form\EditRolesFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]
final class UserController extends AbstractController
{

    public function __construct(private EntityManagerInterface $em) 
    {

    }
    #[Route('/user/list', name: 'admin_user_index', methods:['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('pages/admin/user/index.html.twig', [
    "users" => $userRepository->findAll()
        ]);
    }

    #[Route('/user/{id<\d+>}/edit-roles', name: 'admin_user_edit_roles', methods: ['GET', 'PUT'])]
    public function editRoles(User $user, Request $request): Response
    {
        $form = $this->createForm(EditRolesFormType::class, $user, [
            "method" => "PUT"
        ]);

    dump($user);
    
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($user);
            $this->em->flush();
          
            $this->addFlash("success", "Les rôles de {$user->getFirstName()} 
            {$user->getLastName()} ont été modifiés.");
         
            return $this->redirectToRoute('admin_user_index');
       }
     
        return $this->render('pages/admin/user/edit_roles.html.twig', [
            "form" => $form->createView()
            
        ]);
    }
}
