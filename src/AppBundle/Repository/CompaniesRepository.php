<?php

namespace AppBundle\Repository;

/**
 * CompaniesRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CompaniesRepository extends \Doctrine\ORM\EntityRepository
{
 
    public function getCompaniesList(){
        $comp = $this->findAll();
        foreach ($comp as $company) {
        	$companies[$company->getName()]=$company->getId();
        }
        return $companies;
    }

    public function getCompaniesArray(){
        $comp = $this->findAll();
        foreach ($comp as $company) {
        	$companies[$company->getId()]['name']=$company->getName();
        	$companies[$company->getId()]['id']=$company->getId();
        	$companies[$company->getId()]['quota']=$company->getQuota();
        	$companies[$company->getId()]['use']=0;
        }
        return $companies;
    }

}