<?php
namespace App\DataFixtures;

use DateTimeImmutable;
use App\Entity\Setting;  // Assurez-vous d'importer la classe Setting
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SettingFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $setting = $this->createSetting();  // Corrigé : appeler la méthode createSetting
        $manager->persist($setting);        // Corrigé : persister l'objet $setting
        $manager->flush();
    }

    public function createSetting(): Setting  // Corrigé : la méthode doit retourner un objet de type Setting
    {
        $setting = new Setting();
        $setting->setwebSiteName('sylla-techno');
        $setting->setwebsiteUrl('sylla-techno.com');
        $setting->setDescription('Ajouter des produits intéressants');
        $setting->setEmail('syllatechno@gmail.com');
        $setting->setPhone('07 07 07 07 07');  // Corrigé : orthographe de setPhone
        $setting->setStreet('Rue de Paris');   // Corrigé : orthographe de setStreet
        $setting->setCity('Romainville');
        $setting->setpostalCode('75019');
        $setting->setState('France');
        $setting->setFacebookLink('https://www.facebook.com/');
        $setting->setInstagramLink('https://www.instagram.com/');
        $setting->setTiktokLink('https://www.tiktok.com/');
        $setting->setCreateAt(new DateTimeImmutable());
        $setting->setUpdatedAt(new DateTimeImmutable());

        return $setting;
    }
}



