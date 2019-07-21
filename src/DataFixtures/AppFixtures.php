<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;


class AppFixtures extends Fixture
{

	private $encoder;

public function __construct(UserPasswordEncoderInterface $encoder)
{
    $this->encoder = $encoder;
}
    public function load(ObjectManager $manager)
    {
       $user=new User();
       $user->setFullname('abdelwahedyosri');
       $user->setEmail('Abdelwahed_yosri@yahoo.com');
       $user->setPassword($this->encoder->encodePassword(

       	$user,'123456'
       ));
       $user->setRoles(['ROLE_ADMIN']);
       $manager->persist($user);

        $manager->flush();
    }
}
