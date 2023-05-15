<?php
namespace App\Controllers;

use App\Models\DealModel;
use CodeIgniter\Controller;

class Deals extends BaseController {
	public function showDeals() {
		$data = [];
		
		$model = new DealModel();
        
        $data = [
			'deals'  => $model->getDealsList(),
			'responsible_users' => $model->createNewDealPageLoad()
			];

		if(empty($data['deals'])) { $data['deals'] = ''; }

		echo view('templates/header', $data);
		echo view('deals');
		echo view('templates/footer');
	}
	
	public function filterDeals() {
		$data = [];
		
		//$data = array('title' => 'фильтрованный список', 'header' => 'test 2');
		
		/*
		это поиск и нам нужно принять форму, обработать данные, отфильтровать данные из БД
		и передать в вид	
		*/
		
		echo view('templates/header', $data);
		echo view('deals');
		echo view('templates/footer');
	}
}