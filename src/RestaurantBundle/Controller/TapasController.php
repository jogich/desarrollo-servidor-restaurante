<?php

namespace RestaurantBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use RestaurantBundle\Entity\Tapas;
use RestaurantBundle\Form\TapasType;
use Symfony\Component\HttpFoundation\Request;

class TapasController extends Controller
{
    /**
     * @Route("/new/tapa", name="new-tapa")
     */
    public function newAction(Request $request)
    {
        $tapa = new Tapas();
        $form = $this->createForm(TapasType::class, $tapa);

        $form->handleRequest($request);
        if ($form->isValid() && $form->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            
            $date = new \DateTime('now');            
            $tapa->setFechaCreacion($date);

            $em->persist($tapa);
            $em->flush();   

            return $this->redirectToRoute('show-tapa');
        }
        return $this->render('RestaurantBundle:tapa:new.html.twig', array('tapa_form' => $form->createView()));
    }

    /**
     * @Route("/show/tapa", name="show-tapa")
     */
    public function showAction()
    {
        $em = $this->getDoctrine()->getManager();

        $tapa = $em->getRepository('RestaurantBundle:Tapas')->findAll();

        return $this->render('RestaurantBundle:tapa:tapa.html.twig', array('tapas' => $tapa));
    }

    /**
     * @Route("/show/{id}/tapa", name="show-one-tapa")
     */
    public function showOneAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $tapa = $em->getRepository('RestaurantBundle:Tapas')->findOneById($id);

        return $this->render('RestaurantBundle:tapa:show-one-tapa.html.twig', array('tapa' => $tapa));
    }

    /**
     * @Route("/update/{id}/tapa", name="update-tapa")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $tapa = $em->getRepository('RestaurantBundle:Tapas')->findOneById($id);

        $form = $this->createForm(TapasType::class, $tapa);
        $form->handleRequest($request);
        if ($form->isValid() && $form->isSubmitted()) {

            $date = new \DateTime('now');
            $tapa->setFechaActulizacion($date);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($tapa);
            $em->flush();
            return $this->redirectToRoute('show-tapa');
        }
        return $this->render('RestaurantBundle:tapa:update.html.twig', array('tapa_form' => $form->createView()));
    }

    /**
     * @Route("/remove/{id}/tapa", name="remove-tapa")
     */
    public function removeAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $tapa = $em->getRepository('RestaurantBundle:Tapas')->findOneById($id);

        $em->remove($tapa);
        $em->flush();
        
        return $this->redirectToRoute('show-tapa');
    }
}
