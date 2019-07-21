<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\MicroPost;
use Symfony\Component\HttpFoundation\JsonResponse;
use  Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;

class LikesController extends AbstractController
{
    /**
     * @Route("/like/{id}", name="likes_like")
     */
    public function like(MicroPost $micropost){
    	/** var User $currentuser*/
    	$currentuser=$this->getUser();
    	$micropost->getLikedBy()->count();
    	
    	if(!$currentuser instanceof User){

    		return new JsonResponse([],Response::HTTP_UNAUTHORIZED);
    	}
    	$micropost->liked($currentuser);
    	$this->getDoctrine()->getManager()->flush();
    	return new JsonResponse(
    		[
    		'count'=>$micropost->getLikedBy()->count()
    	]);



    }

     /**
     * @Route("/unlike/{id}", name="likes_unlike")
     */
    public function unlike(MicroPost $micropost){
    	/** var User $currentuser*/
    	$currentuser=$this->getUser();
    	if(!$currentuser instanceof User){

    		return new JsonResponse([],Response::HTTP_UNAUTHORIZED);
    	}
    	$micropost->getLikedBy()->removeElement($currentuser);
    	$this->getDoctrine()->getManager()->flush();
    	return new JsonResponse([
    		'count'=>$micropost->getLikedBy()->count()
    	]);

    	
    }
}
