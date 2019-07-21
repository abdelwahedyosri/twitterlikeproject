<?php



namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\MicroPost;
use App\Entity\User;
use  Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;


class MicroPostVoter extends Voter
{
    Const EDIT="edit";
    Const DELETE="delete";
    private $decisionManager;
    public function __construct(AccessDecisionManagerInterface $decisionManager){

        $this->decisionManager=$decisionManager;
    }
    protected function supports($attribute, $subject)
    {
        if(!in_array($attribute, [self::EDIT,self::DELETE])){
            return false;
        }
        if(!$subject instanceof MicroPost){
            return false;
        }
        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
       if($this->decisionManager->decide($token,[User::ROLE_ADMIN])){
        return true;
       }

       $AtuthenticatedUser=$token->getUser();
       if(!$AtuthenticatedUser instanceof User){
        return false;
       }
       $micropost=$subject;

        return $micropost->getUser()->getID()===$AtuthenticatedUser->getID() ;
    }
}
