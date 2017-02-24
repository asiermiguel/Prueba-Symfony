<?php

namespace AMP\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use AMP\UserBundle\Entity\User;
use AMP\UserBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class UserController extends Controller
{
    public function indexAction()
    {
   		$em = $this->getDoctrine()->getManager();
   		
   		$users = $em->getRepository('AMPUserBundle:User')->findAll();
   		/*
   		$resultado = 'Lista de usuarios <br/>';
   		
   		foreach ($users as $user){
   			$resultado .= 'Usuarios: ' . $user->getUsername() . '- Email: ' . $user->getEmail() .'<br/>';
   		}
   		return new Response($nombre);
   		*/
   		
   		return $this->render('AMPUserBundle:User:index.html.twig',array('users'=>$users));
    }
    
    public function  viewAction($id){
    	$repository = $this->getDoctrine()->getRepository('AMPUserBundle:User');
    	$user = $repository->find($id);
    	 //$user = $repository->findOneByUsername($id);
    	return new Response('Usuario: '. $user->getUsername(). ' con email: ' . $user->getEmail() );
    }
    
    public function addAction(){
    	$user = new User();
    	$form = $this->createCreateForm($user);
    	return $this->render('AMPUserBundle:User:add.html.twig',array('form'=>$form->createView()));
    	
    }
    private function createCreateForm(User $entity){
    	$form = $this->createForm(UserType::class,$entity,array('action'=>$this->generateUrl('amp_user_create'),'method'=>'POST'));
   		return $form;
    }
    public function createAction(Request $request){
    	$user = new User();
    	$form = $this->createCreateForm($user);
    	$form->handleRequest($request);
    	
    	if($form->isValid())
    	{
    		$password = $form->get('password')->getData();
    		$encoder =$this->container->get('security.password_encoder');
    		$encoded =$encoder->encodePassword($user,$password);
    		$user->setPassword($encoded);
    		
    		
    		
    		$em = $this->getDoctrine()->getManager();
    		$em->persist($user);
    		$em->flush();
    		
    		return $this->redirectToRoute('amp_user_index');
    	}
    	return $this->render('AMPUserBundle:User:add.html.twig',array('form'=>$form->createView()));
    }
    
     
}
;