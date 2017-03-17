<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Companies;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
// use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
// use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CompaniesController extends Controller
{
    /**
     * @Route("/companies", name="companies_list")
     */
    public function listAction()
    {
        $companies = $this->getDoctrine()
            ->getRepository('AppBundle:Companies')
            ->findAll();

        return $this->render('companies/index.html.twig', array('companies'=>$companies));
        // return $this->render('companies/index.html.twig');
    }

    /**
     * @Route("/companies/create", name="companies_create")
     */
    public function createAction(Request $request){

        $company = new Companies;

        $form = $this->createFormBuilder($company)
            ->add('name',TextType::class,array('attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px')))
            ->add('quota',NumberType::class,array('attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px')))
            ->add('Save',SubmitType::class,array('label'=>'Create Company','attr'=>array('class'=>'btn btn-primary','style'=>'margin-bottom:15px')))
            ->getForm();

        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid() ){

            $em = $this->getDoctrine()->getManager();
            $em->persist($company);
            $em->flush();

            $this->addFlash('notice','Company saved!');

            return $this->redirectToRoute('companies_list');
        }

        return $this->render('companies/create.html.twig',array('form'=>$form->createView()));

    }

}