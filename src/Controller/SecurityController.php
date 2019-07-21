<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Config\Definition\Exception\Exception;


class SecurityController extends AbstractController
{
     /**
     * @Route("/registration", name="registration")
     */
    public function registration(ObjectManager $manager,Request $request,UserPasswordEncoderInterface  $encoder)
    {
        $manager=$this->getDoctrine()->getManager();
        $user=new User();
        
        $form=$this->createFormBuilder($user)->add('fullname',TextType::class)
        ->add('email',EmailType::class)->add('password',PasswordType::class)->getForm();
        $form->handleRequest($request);
        if($form->issubmitted() && $form->isValid()){
            $hash=$encoder->encodePassword($user,$user->getPassword());
            $user->setPassword($hash);
            
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('app_login');
        }   

        return $this->render('security/register.html.twig', [
            'controller_name' => 'SecurityController','formRegister'=>$form->createView()
        ]);

    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }
     /**
     * @Route("/logout", name="logout")
     */
      public function logout()
    {
        

        throw new Exception("this should never be reached manually");
        
    }
}
