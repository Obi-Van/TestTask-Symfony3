<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Transfers;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * TransfersRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TransfersRepository extends \Doctrine\ORM\EntityRepository
{

	public function makeTransferEntry($user){


		// $rec = new Transfers;
		// $rec->setUserId(2);
		// print_r($rec);
		// $em = $this->getEntityManager();
		// $em->persist($rec);
		// $em->flush();


		$em = $this->getEntityManager();

		$monthly = rand(8,50);
		// $monthly = 1;
		for ($m=0; $m<6; $m++):							//monthly loop

			for ($i=0; $i < $monthly; $i++) { 				//dayly records
				$data = rand(100,10000000000)/1000000000;
				$year = date("Y", mktime(0, 0, 0, date("m")-$m, 1, date("Y")));
				$month = date("m", mktime(0, 0, 0, date("m")-$m, 1, date("Y")));
				$days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
				$date  = date("Y-m-d H:i:s" , mktime(rand(0,23), rand(0,59), rand(0,59), date("m")-$m  , rand(1,$days), date("Y")));	
				// $date  = mktime(rand(0,23), rand(0,59), rand(0,59), date("m")-$m  , rand(1,$days), date("Y"));	
				$resources = [
				'http://cake.dev/transfers/generate',
				'https://book.cakephp.org/3.0/en/views/helpers/html.html',
				'https://book.cakephp.org/3.0/en/orm/database-basics.html',
				'http://stackoverflow.com/questions/25065005/check-if-record-exists-in-cakephp3',
				'http://stackoverflow.com/questions/22855026/fields-created-and-modified-are-not-set-automatically-in-cakephp3-0-0dev-pr'
				];
				$resource = $resources[rand(0,4)];
		   
		        $transfer = new Transfers;
		        $transfer->setUserId($user->getId());
		        $transfer->setResource($resource);
		        $transfer->setDate($date);
		        $transfer->setAmount($data);
		        $transfer->setCompanyId($user->getCompanyId());
				// $em = $this->getEntityManager();
				$em->persist($transfer);
				$em->flush();

	            // $transfer = $this->patchEntity($transfer, 
	            // 	['id'=>null,'user_id'=>$user->id,'resource'=>$resource,'date'=>$date,'amount'=>$data,'company_id'=>$user->company_id]);
	            // if (!$this->save($transfer)) {
	            //     $this->Flash->error(__('The user could not be saved. Please, try again.'));
	            // }
			}

		endfor;


	}

}
