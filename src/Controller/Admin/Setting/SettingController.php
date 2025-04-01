<?php

namespace App\Controller\Admin\Setting;

use DateTimeImmutable;
use App\Entity\Setting;
use App\Repository\SettingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\SettingFormType; // Importation ajoutée
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]
final class SettingController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em) 
    {
        
    }



    #[Route('/setting', name: 'admin_setting_index', methods: ['GET'])]
    public function index(SettingRepository $settingRepository): Response
    {
        $setting = $settingRepository->findAll();
        // dd($setting[0]);

        return $this->render('pages/admin/setting/index.html.twig', [

            "setting" => $setting[0],
        ]);
    }

    #[Route('/setting/{id<\d+>}/edit', name: 'admin_setting_edit', methods: ['GET','POST'])]
    
     public function edit(Setting $setting, Request $request): Response
    
     {
        $form = $this->createForm(SettingFormType::class, $setting);

        if ($form->isSubmitted() && $form->isValid()) {


            $setting->setUpdatedAt(new DateTimeImmutable());

            $this->em->persist($setting);
            $this->em->flush();

            $this->addFlash("success", "Les paramètres du site ont été modifiés.");

            return $this->redirectToRoute("admin_setting_index");
        }

        return $this->render('pages/admin/setting/edit.html.twig', [
            "form" => $form->createView(),
        ]);
    }
}
