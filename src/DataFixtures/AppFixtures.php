<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }
    
    public function load(ObjectManager $manager)
    {
        $adminRole = new Role();
    
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        $adminUser = new User();
        $adminUser->setFirstname('Félix')
                  ->setName('Sagno')
                  ->setEmail('felix@sagnol.com')
                  ->setPassword($this->encoder->encodePassword($adminUser,'password'))
                  ->setPhone('0625248758')
                  ->addUserRole($adminRole);
        $manager->persist($adminUser);
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
