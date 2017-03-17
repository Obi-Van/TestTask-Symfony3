<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Entity\Companies;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Asset\Package;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserController extends Controller
{
    /**
     * @Route("/", name="Users_list")
     */
    public function listAction(Request $request)
    {
        


        $users = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->findAll();

        /**
        *   @var $paginator Knp\Component\Pager\Paginator
        */

        $paginator = $this->get('knp_paginator');

        $result = $paginator->paginate(
            $users,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',3)
            );
        
        return $this->render('users/index.html.twig', array('users'=>$result));
    }

    /**
     * @Route("/user/create", name="create_user")
     */
    public function createAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $list = $em->getRepository("AppBundle:Companies")->getCompaniesList();

        $user = new User;

        $form = $this->createFormBuilder($user)
            ->add('name',TextType::class,array('attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px')))
            ->add('email',EmailType::class,array('attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px')))
            ->add('Company_id',ChoiceType::class,array('choices'=>$list,'attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px')))
            ->add('Save',SubmitType::class,array('label'=>'Create User','attr'=>array('class'=>'btn btn-primary','style'=>'margin-bottom:15px')))
            ->getForm();

        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid() ){

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('notice','User saved!');

            return $this->redirectToRoute('Users_list');
        }

        return $this->render('users/create.html.twig',array('form'=>$form->createView()));
    }

    /**
     * @Route("/user/edit/{id}", name="edit_user")
     */
    public function editAction($id,Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:User');
        $user = $repository->find($id);

        $company = $this->getDoctrine()->getRepository('AppBundle:Companies');
        $temp = $company->findAll();
        $companies = array();
        foreach ($temp as $comp) {
            $companies[$comp->getName()]=$comp->getId();
        }        

        $form = $this->createFormBuilder($user)
            ->add('name',TextType::class,array('attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px')))
            ->add('email',EmailType::class,array('attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px')))
            ->add('Company_id',ChoiceType::class,array('choices'=>$companies,'attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px')))
            ->add('Save',SubmitType::class,array('label'=>'Save User','attr'=>array('class'=>'btn btn-primary','style'=>'margin-bottom:15px')))
            ->getForm();

        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid() ){

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('notice','User saved!');
            return $this->redirectToRoute('Users_list');
        }

        if ( $user ){

            return $this->render('users/edit.html.twig',array('user'=>$user,'form'=>$form->createView()));
        
        }else{
            $this->addFlash(
            'notice',
            'No any user was found!'
            );
            return $this->redirectToRoute('Users_list');
        }

    }

    /**
     * @Route("/user/details/{id}", name="detailes_user")
     */
    public function detailsAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:User');
        $user = $repository->find($id);

        $company = $this->getDoctrine()->getRepository('AppBundle:Companies');
        $temp = $company->findAll();
        $companies = array();
        foreach ($temp as $comp) {
            $companies[$comp->getId()]=$comp->getName();
        }

        if ( $user ){
            return $this->render('users/details.html.twig',array('user'=>$user,'companies'=>$companies));
        }else{
            $this->addFlash(
            'notice',
            'No any user was found!'
            );
            return $this->redirectToRoute('Users_list');
        }
    }

 /**
     * @Route("/user/delete/{id}", name="delete_user")
     */
    public function deleteAction($id)
    {
        $repository = $this->getDoctrine()->getManager();
        $user = $repository->getRepository('AppBundle:User')->find($id);
        $name = $user->getName();
        if ( $user ){
            $repository->remove($user);
            $repository->flush();
            $this->addFlash(
            'notice',
            'User: '.$name.' deleted!'
            );
        }else{
            $this->addFlash(
            'notice',
            'No any user was found!'
            );
        }
        return $this->redirectToRoute('Users_list');
    }

}
