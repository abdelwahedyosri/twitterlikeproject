<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

class FollowingContoller extends AbstractController
{
    /**
     * @Route("/follow/{id}", name="following_follow")
     * 
     */
    public function follow(User $usertofollow,$id)
    {
        /** @var User $currentuser */
    	$currentuser=$this->getUser();
    	
    	$currentuser->getFollowing()->add($usertofollow);
    	$this->getdoctrine()->getManager()->flush();
        return $this->redirectToRoute(
        	'micropost_user',['fullname'=>$usertofollow->getFullname()]

        );
            
       
    }

    /**
     * @Route("/unfollow/{id}", name="following_unfollow")
     * 
     */
    public function unfollow(User $usertounfollow,$id)
    {
        /** @var User $currentuser */
    	$currentuser=$this->getUser();
    	
    	$currentuser->getFollowing()->removeElement($usertounfollow);
    	$this->getdoctrine()->getManager()->flush();
        return $this->redirectToRoute(
        	'micropost_user',['fullname'=>$usertounfollow->getFullname()]

        );
            
       
    }



   }
