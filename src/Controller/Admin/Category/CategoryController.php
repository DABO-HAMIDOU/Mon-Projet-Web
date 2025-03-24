<?php

namespace App\Controller\Admin\Category;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\CategoryRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')] // ✅ Correction ici : enlever le '/' final
class CategoryController extends AbstractController
{
    // ✅ Correction de la signature du constructeur
    public function __construct(private EntityManagerInterface $em, private CategoryRepository $categoryRepository) {}

    #[Route('/category/list', name: 'admin_category_index', methods: ['GET','POST'])]
    public function index(): Response
    {
        return $this->render('pages/admin/category/index.html.twig', [
            'categories' => $this->categoryRepository->findAll(),
        ]);
    }

    #[Route('/category/create', name: 'admin_category_create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setCreatedAt(new DateTimeImmutable());
            $category->setUpdatedAt(new DateTimeImmutable());
            $this->em->persist($category);
            $this->em->flush(); 

            $this->addFlash("success", "La catégorie a été ajoutée avec succès !");
            return $this->redirectToRoute("admin_category_index");
        }

        return $this->render('pages/admin/category/create.html.twig', [
            "form" => $form->createView()
        ]);
    }

    #[Route('/category/{id<\d+>}/edit', name: 'admin_category_edit', methods: ['GET', 'PUT'])]
    public function edit(Category $category, Request $request): Response
    {
        // ✅ Déplacer la création du formulaire au début
        $form = $this->createForm(CategoryFormType::class, $category,['method'=>"PUT"]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setUpdatedAt(new DateTimeImmutable());
            $this->em->flush(); // ✅ Correction ici : pas besoin de transmettre un paramètre à flush()

            $this->addFlash("success", "La catégorie a été modifiée avec succès !");
            return $this->redirectToRoute("admin_category_index");
        }

        return $this->render('pages/admin/category/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView()
        ]);
    }


    #[Route('/category/{id<\d+>}/delete', name: 'admin_category_delete', methods: ['DELETE'])]
    public function delete(Category $category, Request $request): Response
{
        // Vérification du token CSRF
        if ($this->isCsrfTokenValid("delete_category_" . $category->getId(), $request->request->get('_csrf_token'))) {
        // Suppression de la catégorie
        $this->em->remove($category);
        $this->em->flush();  // Correction ici : pas besoin de paramètre dans flush()

        // Message flash de succès
        $this->addFlash("success", "La catégorie a été supprimée avec succès !");
    } else {
        // Si le token est invalide, on pourrait ajouter un message d'erreur
        $this->addFlash("error", "Token CSRF invalide. Suppression échouée.");
    }

    // Redirection après suppression
    return $this->redirectToRoute("admin_category_index");
}

}
