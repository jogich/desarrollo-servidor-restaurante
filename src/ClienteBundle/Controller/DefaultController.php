<?php

namespace ClienteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ClienteBundle\Entity\Cliente;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use ClienteBundle\Form\ClienteType;

class DefaultController extends Controller
{
    /**
     * @Route("/cliente/new", name="new-cliente")
     */
    public function newAction(Request $request)
    {
    	$cliente = new Cliente();

    	$form = $this->createForm(ClienteType::class, $cliente);

    	$form->handleRequest($request);
    	if ($form->isValid() && $form->isSubmitted()) {
    		
    		$password = $this->get('security.password_encoder')->encodePassword($cliente, $cliente->getPlainPassword());

    		$cliente->setPassword($password);

			$em = $this->getDoctrine()->getManager();
    		$em->persist($cliente);
			$em->flush();

			return $this->redirectToRoute('show-cliente');
    	}
        return $this->render('ClienteBundle:Default:new.html.twig', array('client_new' => $form->createView()));
	}
	
	/**
	 * @Route("/cliente/show", name="show-cliente")
	 */
	public function showAction()
	{
		$em = $this->getDoctrine()->getManager();

		$cliente = $em->getRepository('ClienteBundle:Cliente')->findAll();

		return $this->render('ClienteBundle:Default:index.html.twig', array('cliente' => $cliente));
	}
}
