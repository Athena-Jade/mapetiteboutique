<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    
    public function load(ObjectManager $manager)
    {
        //utilisation de Faker pour créer des fausses données
        $faker = Factory::create('fr FR');

        //création d'un utilisateur
        $user = new User();
        $user->setEmail('user@test.com')
            ->setFirstname($faker->firstname())
            ->setLastname($faker-> lastname());
          
        $Password = $this->encoder->encodePassword($user, 'password');
        $user->setPassword($Password);


        //création de 5 catégories
        for ($i=0; $i < 5; $i++) { 
            $category = new Category();
        
            $category->setName($faker->word());
            $manager->persist($category);
                

            //création de dix produits
            for ($i = 0; $i < 10; $i++){
                $product = new Product();
            
                
                $product->setName($faker->word(3, true))
                    ->setSlug($faker->slug(3))
                    ->setSubtitle('lalala')
                    ->setPrice('15')
                    ->setIllustration('public/assets/img/manteau.jpg')
                    ->setDescription($faker->text(100))
                    ->setIsBest('le meilleur')
                    ->setIsNewArrival('les arrivages')
                    ->setIsSpecialOffer('les offres speciales')
                    ->setIsFeatured('les produits stars')
                    ->setCategory($category);
                    
                $manager->persist($product);
            
                $manager->persist($user);
            
                $manager->flush();
            }   
    
        }
    
    }
}
