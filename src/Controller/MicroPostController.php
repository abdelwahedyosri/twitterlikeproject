<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\MicroPost;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use  Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;




class MicroPostController extends AbstractController
{
    /**
     * @Route("/index", name="index")
     */
    public function index(ObjectManager $manager,Request $request)
    {
        /** var User $currentuser*/
        $currentuser=$this->getUser();
        if ($currentuser instanceof User){
        $repository=$this->getDoctrine()->getRepository(MicroPost::class);
        $posts=$repository-> FindAllByUsers($currentuser->getFollowing());
             
        }else{
            $repository=$this->getDoctrine()->getRepository(MicroPost::class);
            $posts=$repository->findBy([],['time'=>'Desc']);
        }

    	$repository=$this->getDoctrine()->getRepository(MicroPost::class);
       
        return $this->render('micro_post/index.html.twig',[

            'posts'=>$posts

        ]);
	
        
    }

    /**
     * @Route("/show_micropost/{id}", name="show_micropost")
     */
    public function show_micropost(ObjectManager $manager,Request $request,$id)
    {

         $repository=$this->getDoctrine()->getRepository(MicroPost::class);
         $post=$posts=$repository-> Find($id);
        
       
        return $this->render('micro_post/raw-post.html.twig',[

            'post'=>$post

        ]);
    
        
    }



     /**
     * @Route("user/{fullname}", name="micropost_user")
     */
    public function userpost(ObjectManager $manager,Request $request,$fullname)
    {
        $repository=$this->getDoctrine()->getRepository(User::class);
         $userwithpost=$repository-> findOneBy(['fullname'=>$fullname]);
       
        $posts=$userwithpost->getMicroPosts();
       /* var_dump($userwithpost);
        die();*/

        return $this->render('micro_post/user-posts.html.twig',[

            'posts'=>$posts,
            'user'=>  $userwithpost

        ]);
    
        
    }

    
/**
     * @Route("/micropost_create", name="micropost_create")
     */

    public function create(ObjectManager $manager,Request $request,FlashBagInterface $flashBag,TokenStorageInterface $tokenStorage)
    {

    	 
$user=$tokenStorage->getToken()->getUser();

    		$manager=$this->getDoctrine()->getManager();
			$micropost=new MicroPost();
            if(isset($user)){
            $micropost->setUser($user);
            }
				

    	$form=$this->createFormBuilder($micropost)->add('text',TextareaType::class)
    	->getForm();

    	$form->handleRequest($request);
    	if($form->issubmitted() && $form->isValid()){
		if(!$micropost->getId()){
			$micropost->setTime(new \dateTime());
		}
		
    		$manager->persist($micropost);
    		$manager->flush();
           
    		
    		$flashBag->add('notice','the post was created');

    		return $this->redirectToRoute('index');
			}

        return $this->render('micro_post/add.html.twig', [
            'controller_name' => 'ArticleController','formmicropost'=>$form->createView()
        ]);
    }
/**
     * @Route("/micropost_delete/{id}", name="micropost_delete")
     */

    public function delete($id,Request $request,ObjectManager $manager,FlashBagInterface $flashBag){
        $manager=$this->getDoctrine()->getManager();
        $thismicropost=$manager->getRepository(MicroPost::class)->find($id);
        $manager->remove($thismicropost);
        $manager->flush();
        $flashBag->add('success','the post was deleted');
        return $this->redirectToRoute('index');

    }
    /**
     * @Route("/micropost_edit/{id}", name="micropost_edit")
     */

     public function Edit(MicroPost $micropost,$id,Request $request,ObjectManager $manager){
       
        
      $form=$this->createFormBuilder($micropost)->add('text',TextType::class)
        ->getForm();

       
        dump($request);
       $form->handleRequest($request);
       
        if($form->issubmitted() && $form->isValid()){
        $manager->persist($micropost);
        $manager->flush();
        $this->addFlash('notice',"your post has been updated");
        return $this->redirectToRoute('index');
      
    }

        return $this->render('micro_post/edit.html.twig',['formMicroPost'=>$form->createView()]);
    }

}
