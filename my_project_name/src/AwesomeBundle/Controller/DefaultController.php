<?php

namespace AwesomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/" , name="home")
     */
    public function indexAction()
    {

        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        //var_dump($currentUser);  // check if user is connected or not and display Ticket if connected

        $toDisplay = 1;
        if($currentUser == 'anon.'){
            $toDisplay = 0;
        }else{
            $toDisplay = 1;
        }

               // var_dump($toDisplay);
        return $this->render('AwesomeBundle:Default:index.html.twig', array(
            'toDisplay' => $toDisplay
        ));
    }

    /**
     * @Route("profile" , name="profile")
     */
    public function profileAction()
    {
        return $this->render('AwesomeBundle:Default:profile.html.twig');
    }



}
