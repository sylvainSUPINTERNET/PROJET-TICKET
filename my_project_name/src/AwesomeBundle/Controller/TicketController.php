<?php

namespace AwesomeBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AwesomeBundle\Entity\Ticket;
use AwesomeBundle\Entity\User;
use AwesomeBundle\Form\TicketType;
use AwesomeBundle\Entity\Message;

/**
 * Ticket controller.
 *
 * @Route("/ticket")
 */
class TicketController extends Controller
{

    /**
     * Lists all Ticket entities.
     *
     * @Route("/", name="ticket_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $currentUser = $this->get('security.token_storage')->getToken()->getUser();

        //Verif avant si user nul or not if user is null render login ELSE render $ticket



        $currentUserName = $this->get('security.token_storage')->getToken()->getUser()->getUserName();
        $currentUserId = $this->get('security.token_storage')->getToken()->getUser()->getId();
        $currentUserRole = $this->get('security.token_storage')->getToken()->getUser()->getRoles();

       // var_dump($currentUserName);
        //var_dump($currentUserId);
       // var_dump($currentUserRole);


        //var_dump($tickets = $em->getRepository('AwesomeBundle:Ticket')->findBy(array('id' => $currentUserId)));

        if($currentUserRole[0] == 'ROLE_USER'){
            $tickets = $em->getRepository('AwesomeBundle:Ticket')->findBy(array(
                 'user' => $currentUserId //user_id (ref to ManyToOne into Ticket.php
             ));

            /*
            foreach($tickets as $ticket) {
                var_dump($ticket->getUserCanSee());
                $ticketsUser[] = $em->getRepository('AwesomeBundle:Ticket')->findBy(array(
                    'user' => $ticket->getUserCanSee() //user_id (ref to ManyToOne into Ticket.php
                ));
            }

                var_dump($ticketsUser);
            */

            //get ticket for that user
            //display sur twig if exist else no

         }else{
             $tickets = $em->getRepository('AwesomeBundle:Ticket')->findAll();
         }


        return $this->render('ticket/index.html.twig', array(
            'tickets' => $tickets
        ));
    }

    /**
     * Creates a new Ticket entity.
     *
     * @Route("/new", name="ticket_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $user = $this->getUser();

        if ($user == null){
            echo("Must be login");
            exit();
        }

        $ticket = new Ticket();
        $form = $this->createForm('AwesomeBundle\Form\TicketType', $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ticket->setDate(new \DateTime("now"));
            $ticket->setUser($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($ticket);
            $em->flush();

            return $this->redirectToRoute('ticket_show', array('id' => $ticket->getId()));
        }

        return $this->render('ticket/new.html.twig', array(
            'ticket' => $ticket,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Ticket entity.
     *
     * @Route("/{id}", name="ticket_show")
     * @Method({"GET", "POST"})
     */
    public function showAction(Ticket $ticket, $id,Request $request)
    {
        $deleteFormTicket = $this->createDeleteForm($ticket);

        $message = new Message();
        $form = $this->createForm('AwesomeBundle\Form\MessageType', $message);

        $messageRepository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AwesomeBundle:Message')
        ;

        $messages = $messageRepository->findByTicket($ticket);

        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
       // var_dump($currentUser);  // check if user is connected or not and display Ticket if connected
        $currentUserRole = $this->get('security.token_storage')->getToken()->getRoles();
        $currentUserName = $this->get('security.token_storage')->getToken()->getUserName();

        $msgError = "";
        $msgOk = "";
        $getUserToAllow = $request->get('idUserToAllow');
        if(!empty($getUserToAllow)){
            $userManager = $this->get('fos_user.user_manager');
            $user = $userManager->findUserBy(array('id' => $_POST['idUserToAllow']));
            if(!empty($user)){
                $em = $this->getDoctrine()->getManager();
                $user = $em->find('AwesomeBundle:User', $user);
                $msgOk = "Utilisateur : " . $user->getUserName() . " can see that ticket now ! ";
                $ticket = $em->find('AwesomeBundle:Ticket', $id);
                $ticket->addUserCanSee($user);
                $em->flush();

            }else{
               // var_dump("ID => ".$_POST['idUserToAllow']." est attribué à aucun utilisateur");
                $msgError = "l'id ".$_POST['idUserToAllow']." est attribué à aucun utilisateur";
            }
        }
        return $this->render('ticket/show.html.twig', array(
            'ticket' => $ticket,
            'username' =>$currentUserName,
            'messages' => $messages,
            'delete_form_ticket' => $deleteFormTicket->createView(),
            'form' => $form->createView(),
            "msgError" => $msgError,
            "msgOk" => $msgOk,
        ));
    }

    /**
     * Displays a form to edit an existing Ticket entity.
     *
     * @Route("/{id}/edit", name="ticket_edit")
     * @Method({"GET", "POST"})
     */

    public function editAction(Request $request, Ticket $ticket)
    {
        $deleteForm = $this->createDeleteForm($ticket);
        $editForm = $this->createForm('AwesomeBundle\Form\TicketType', $ticket);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ticket);
            $em->flush();

            return $this->redirectToRoute('ticket_edit', array('id' => $ticket->getId()));
        }

        return $this->render('ticket/edit.html.twig', array(
            'ticket' => $ticket,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Ticket entity.
     *
     * @Route("/{id}", name="ticket_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Ticket $ticket)
    {
        $form = $this->createDeleteForm($ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($ticket);
            $em->flush();
        }

        return $this->redirectToRoute('ticket_index');
    }

    /**
     * Creates a form to delete a Ticket entity.
     *
     * @param Ticket $ticket The Ticket entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Ticket $ticket)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ticket_delete', array('id' => $ticket->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
