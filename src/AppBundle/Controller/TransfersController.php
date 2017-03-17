<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Transfers;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
// use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class TransfersController extends Controller
{

    /**
     * @Route("/transfers", name="transfers_list")
     */
    public function listAction(Request $request)
    {
        // $report = new Transfers;
    	$months = ['January'=>'01','February'=>'02','March'=>'03','April'=>'04','May'=>'05','June'=>'06','July'=>'07','August'=>'08','September'=>'09','October'=>'10','November'=>'11','December'=>'12'];
  		$current_month = date("m");

        $form = $this->createFormBuilder()
            ->add('year',ChoiceType::class,array('choices'=>array('2016'=>'2016','2017'=>'2017'),'data'=>'2017','attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px')))
            ->add('month',ChoiceType::class,array('choices'=>$months,'data'=>$current_month,'attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px')))
            ->add('Save',SubmitType::class,array('label'=>'Show report','attr'=>array('class'=>'btn btn-primary','style'=>'margin-bottom:15px')))
            ->getForm();

        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid() ){

            $em = $this->getDoctrine()->getRepository('AppBundle:Transfers');

   //          $users = $em->findBy( ['amount'=>200] );

			// print_r($users);

            $data = $form->getData();

            $month = date("F",mktime(0,0,0,$data['month'],1,1970));

			$fromDate = new \DateTime();
			$fromDate->setDate($data['year'], $data['month'], 1)->setTime(0,0,0);

			$toDate = clone $fromDate;
			$toDate->modify('+1 month');

			// $fromDate->setTime(0, 0, 0);

			// $toDate = clone $fromDate;
			// $toDate->modify('+1 day');
		    
		    $q = $em->createQueryBuilder('e')
		        ->where('e.date >= :fromDate')
		        ->andWhere('e.date < :toDate')
		        ->setParameter('fromDate', $fromDate)
		        ->setParameter('toDate', $toDate)
		        ->orderBy('e.id', 'ASC')
		         ->getQuery();

    		$transfers = $q->getResult();

	        $em = $this->getDoctrine()->getManager();
	        $companies = $em->getRepository("AppBundle:Companies")->getCompaniesArray();

			foreach ($transfers as $record) {
			    	$companies[$record->getCompanyId()]['use']+=$record->getAmount();
			    }    

			// print_r($companies);

            // $this->addFlash('notice','User saved!');

            // return $this->render('transfers/empty.html.twig');
			if ( $transfers ){
	        	return $this->render('transfers/show.html.twig',array('companies'=>$companies,'month'=>$month));
	        }else{
	        	return $this->render('transfers/empty.html.twig');
	        }
            // return $this->redirectToRoute('transfers_list');
        }

        return $this->render('transfers/index.html.twig',array('form'=>$form->createView()));

        // return $this->render('transfers/index.html.twig');
    }


    /**
     * @Route("/transfers/generate", name="transfers_generate")
     */
    public function generateAction(Request $request)
    {

        $users = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->findAll();
        // print_r($users);

        $em = $this->getDoctrine()->getRepository('AppBundle:Transfers');

		$delete = $this->getDoctrine()->getManager();
 		$delete->createQuery('DELETE FROM AppBundle\Entity\Transfers')->execute();
 
    	foreach ($users as $user) {
    		$em->makeTransferEntry($user);
	    }
        $this->addFlash('notice','New data generated!');

        return $this->redirectToRoute('transfers_list');
        // return $this->render('transfers/generate.html.twig');

    }

}