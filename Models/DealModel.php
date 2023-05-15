<?php
namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\HTTP\IncomingRequest;

class DealModel extends Model {
	
	//protected $table = 'qusers';
	protected $allowedFields = ['MLN', 'fka', 'status', 'respons_id'];
	//protected $beforeInsert = ['beforeInsert'];
	//protected $beforeUpdate = ['beforeUpdate'];
	
	protected $table = 'deals_main';
	  
    public function getDealsList() {
		$is_admin = false;
		
		if (session()->get('role_id') === 'superadmin' || session()->get('role_id') === 'admin') { $is_admin = true; }
		
        if($is_admin === true) { 
			
			$result = $this->findAll();
		
			$deal_info = []; $k = 0;
			foreach ($result as $res) {
				$deal_info[$k]['id'] = $res['id'];
				$deal_info[$k]['MLN'] = $res['MLN'];
				$deal_info[$k]['fka'] = $res['fka'];
				$deal_info[$k]['status'] = $res['status'];
				$deal_info[$k]['respons_id'] = $res['respons_id'];
				
				//дата последней активности
				$db = \Config\Database::connect(); $builder = $db->table('history');
				$result1 = $builder->where(['parent_id' => $res['id']])->get()->getResult(); $result1 = get_object_vars($result1[0]); $deal_info[$k]['date'] = $result1['date_'];
				//наименование имущества
				$db = \Config\Database::connect(); $builder = $db->table('deals_main_info');
				$result1 = $builder->where(['parent_id' => $res['id']])->get()->getResult(); $result1 = get_object_vars($result1[0]); $deal_info[$k]['stuff_name'] = $result1['stuff_name'];
				$result1 = $builder->where(['parent_id' => $res['id']])->get()->getResult(); $result1 = get_object_vars($result1[0]); $deal_info[$k]['stuff_quantity'] = $result1['stuff_quantity'];
				$result1 = $builder->where(['parent_id' => $res['id']])->get()->getResult(); $result1 = get_object_vars($result1[0]); $deal_info[$k]['contract'] = $result1['contract'];
				$result1 = $builder->where(['parent_id' => $res['id']])->get()->getResult(); $result1 = get_object_vars($result1[0]); $deal_info[$k]['court_decision'] = $result1['court_decision'];
				/*удалить нахер
				//активное поручение
				//$db = \Config\Database::connect(); $builder = $db->table('poruchenie');
				//$result1 = $builder->where('parent_id',$res['id'])->where('is_active','1')->get()->getResult(); $result1 = get_object_vars($result1[0]); $deal_info[$k]['poruchenie'] = $result1['number'];
				//поверенный
				//$db = \Config\Database::connect(); $builder = $db->table('responsible_organizations');
				//$result1 = $builder->where('parent_id',$res['id'])->where('is_active','1')->get()->getResult(); $result1 = get_object_vars($result1[0]); $deal_info[$k]['responsible_organization'] = $result1['title'];
				*/
				$k++;
			}
			return $deal_info;
	}
		else { 
			$user_id = session()->get('id');
			$result = $this->asArray()->where(['respons_id' => $user_id])->findAll();
			
			$deal_info = []; $k = 0;
			
			foreach ($result as $res) {
				$deal_info[$k]['id'] = $res['id'];
				$deal_info[$k]['fka'] = $res['fka'];
				$deal_info[$k]['MLN'] = $res['MLN'];
				$deal_info[$k]['status'] = $res['status'];
				$deal_info[$k]['respons_id'] = $res['respons_id'];
				
				//дата последней активности
				$db = \Config\Database::connect(); $builder = $db->table('history');
				$result1 = $builder->where(['parent_id' => $res['id']])->get()->getResult(); $result1 = get_object_vars($result1[0]); $deal_info[$k]['date'] = $result1['date_'];
				//наименование имущества
				$db = \Config\Database::connect(); $builder = $db->table('deals_main_info');
				$result1 = $builder->where(['parent_id' => $res['id']])->get()->getResult(); $result1 = get_object_vars($result1[0]); $deal_info[$k]['stuff_name'] = $result1['stuff_name'];
				$result1 = $builder->where(['parent_id' => $res['id']])->get()->getResult(); $result1 = get_object_vars($result1[0]); $deal_info[$k]['stuff_quantity'] = $result1['stuff_quantity'];
				$result1 = $builder->where(['parent_id' => $res['id']])->get()->getResult(); $result1 = get_object_vars($result1[0]); $deal_info[$k]['contract'] = $result1['contract'];
				$result1 = $builder->where(['parent_id' => $res['id']])->get()->getResult(); $result1 = get_object_vars($result1[0]); $deal_info[$k]['court_decision'] = $result1['court_decision'];
				//активное поручение
				//$db = \Config\Database::connect(); $builder = $db->table('poruchenie');
				//$result1 = $builder->where('parent_id',$res['id'])->where('is_active','1')->get()->getResult(); $result1 = get_object_vars($result1[0]); $deal_info[$k]['poruchenie'] = $result1['number'];
				//поверенный
				//$db = \Config\Database::connect(); $builder = $db->table('responsible_organizations');
				//$result1 = $builder->where('parent_id',$res['id'])->where('is_active','1')->get()->getResult(); $result1 = get_object_vars($result1[0]); $deal_info[$k]['responsible_organization'] = $result1['title'];
				$k++;
			}
			return $deal_info;
		}
    }
	
	
	public function getOneDeal($id) {
		
		$result = false;
		$access = false;
		
		$user_id = session()->get('id'); 
		
		//А001МР78 пропускать везде
		
		if (session()->get('role_id') === 'superadmin' || session()->get('role_id') === 'admin') { 
			$access = true;
			$info = $this->asArray()->where(['id' => $id])->findAll();
			if (@$info[0]['id'] === null) { return 'fuckoff'; }
		}
		
		if ($access === false) {
			//если не АМР то проверяем есть ли права на доступ к делу
			
			$db = \Config\Database::connect();
			$builder = $db->table('deals_main');
		
			$result = $builder->where(['id' => $id])->get()->getResult();
			$result = get_object_vars($result[0]);
			$respons_id_for_check = $result['respons_id'];
			
			if ($respons_id_for_check === $user_id) { $access = true; } else { return 'fuckoff'; }
			
			if ($access) { $info = $this->asArray()->where(['id' => $id])->findAll(); }
		}
		
		//print_r($info); die;
				
		$deal_info = [];
		foreach ($info as $res) {
			//deals_main
			$deal_info['id'] = $res['id'];
			$deal_info['MLN'] = $res['MLN'];
			$deal_info['fka'] = $res['fka'];
			$deal_info['status'] = $res['status'];
			$deal_info['respons_id'] = $res['respons_id'];
			$deal_info['is_respons_changed'] = $res['is_respons_changed'];
			//technical information - tabs statuses
			$deal_info['tabs_statuses'] = [];
			$db = \Config\Database::connect(); $builder = $db->table('deal_statuses_tech');
			$result1 = $builder->getWhere(['parent_id' => $deal_info['id']], null, null);
			foreach ($result1->getResult() as $row) {
				$deal_info['tabs_statuses']['keeping_av'] = $row->keeping_av;
				$deal_info['tabs_statuses']['expertise_av'] = $row->expertise_av;
				$deal_info['tabs_statuses']['realiz_under_10k_av'] = $row->realiz_under_10k_av;
				$deal_info['tabs_statuses']['realiz_more_10k_av'] = $row->realiz_more_10k_av;
				$deal_info['tabs_statuses']['utiliz_av'] = $row->utiliz_av;
				$deal_info['tabs_statuses']['destroy_av'] = $row->destroy_av;
				$deal_info['tabs_statuses']['pererab_av'] = $row->pererab_av;
				$deal_info['tabs_statuses']['otsenka_av'] = $row->otsenka_av;
			}
			//poruchenie
			$db = \Config\Database::connect(); $builder = $db->table('poruchenie');
			$result1 = $builder->getWhere(['parent_id' => $deal_info['id']], null, null); 
			$deal_info['poruchenie'] = []; $k = 0;
			foreach ($result1->getResult() as $row) {
				$deal_info['poruchenie'][$k]['id'] = $row->id;
				$deal_info['poruchenie'][$k]['parent_id'] = $row->parent_id;
				$deal_info['poruchenie'][$k]['act_number'] = $row->act_number;
				$deal_info['poruchenie'][$k]['act_date'] = $row->act_date;
				$deal_info['poruchenie'][$k]['file'] = $row->file_;
				$deal_info['poruchenie'][$k]['is_active'] = $row->is_active;
				$k++;
			}  
			//history
			$db = \Config\Database::connect(); $builder = $db->table('history');
			$result1 = $builder->getWhere(['parent_id' => $deal_info['id']], null, null); 
			$deal_info['history'] = []; $k = 0;
			foreach ($result1->getResult() as $row) {
				$deal_info['history'][$k]['id'] = $row->id;
				$deal_info['history'][$k]['parent_id'] = $row->parent_id;
				$deal_info['history'][$k]['date'] = $row->date_;
				$deal_info['history'][$k]['user'] = $row->user;
				$deal_info['history'][$k]['company'] = $row->company;
				$deal_info['history'][$k]['change'] = $row->change; //возможно будет массив
				$k++;
			}
			//deals_main_info
			$db = \Config\Database::connect(); $builder = $db->table('deals_main_info');
			$result1 = $builder->getWhere(['parent_id' => $deal_info['id']], null, null); 
			//$deal_info['deals_main_info'] = []; //$k = 0;
			foreach ($result1->getResult() as $row) {
				$deal_info['deals_main_info']['id'] = $row->id;
				$deal_info['deals_main_info']['parent_id'] = $row->parent_id;
				$deal_info['deals_main_info']['stuff_name'] = $row->stuff_name;
				$deal_info['deals_main_info']['stuff_quantity'] = $row->stuff_quantity;
				$deal_info['deals_main_info']['contract'] = $row->contract;
				$deal_info['court_decision'] = $row->court_decision;
				$deal_info['deals_main_info']['upal_namochenny'] = $row->upal_namochenny;
				$deal_info['deals_main_info']['sklad_id'] = $row->sklad_id;
				$deal_info['deals_main_info']['imusch_objem'] = $row->imusch_objem;
				//$k++;
			}
			//deals_main_keeping
			$db = \Config\Database::connect(); $builder = $db->table('deals_main_keeping');
			$result1 = $builder->getWhere(['parent_id' => $deal_info['id']], null, null); 
			if($result1) {
				$deal_info['deals_main_keeping'] = []; $k = 0;
				foreach ($result1->getResult() as $row) {
					$deal_info['deals_main_keeping'][$k]['id'] = $row->id;
					$deal_info['deals_main_keeping'][$k]['parent_id'] = $row->parent_id;
					$deal_info['deals_main_keeping'][$k]['type'] = $row->type;
					$deal_info['deals_main_keeping'][$k]['file_id'] = $row->file_id;
					$deal_info['deals_main_keeping'][$k]['file_size'] = $row->file_size;
					$deal_info['deals_main_keeping'][$k]['user_date'] = $row->user_date;
					$deal_info['deals_main_keeping'][$k]['date_really_added'] = $row->date_really_added;
					$k++;
				}
			}
			//resp_organiz_sklady
			$db = \Config\Database::connect(); $builder = $db->table('resp_organiz_sklady');
			$result1 = $builder->getWhere(['qusers_id' => $deal_info['respons_id'], 'is_active' => '1'], null, null); 
			$deal_info['sklady'] = []; $k = 0;
			$deal_info['response_org'] = [];
			foreach ($result1->getResult() as $row) {
				$deal_info['sklady'][$k]['id'] = $row->id;
				$deal_info['sklady'][$k]['address'] = $row->address;
				$k++;
			}
		}
		return $deal_info;		
	}
	
	public function createNewDealPageLoad() { //лишние айдишники исключены
		$info['responsible_ids'] = [];
		$db = \Config\Database::connect(); $builder = $db->table('qusers');
		
		$builder->select('id, firstname, lastname');
		$query = $builder->get();

		$k = 1;
		foreach ($query->getResult() as $row) {
			if ($k<6) { $k++; continue; }
			//if ($k == '6') { $k++; continue; }
			$info['responsible_ids'][$k]['id'] = $row->id;
			$info['responsible_ids'][$k]['firstname'] = $row->firstname;
			$info['responsible_ids'][$k]['lastname'] = $row->lastname;
			$k++;
		}
		
	return $info;
	}
	
	public function putNewDealIntoDB($deal_id, $post_data, $post_files) { 
			//вставляем основную инфу
			$db = \Config\Database::connect();
			$pQuery = $db->prepare(function($db) {
				return $db->table('deals_main_info')
						  ->insert([
							   'parent_id'    => '',
							   'stuff_name'   => '',
							   'stuff_quantity'   => '',
							   'stuff_ed_izm' => '',
							   'contract'   => '',
							   'court_decision'   => '',
							   'upal_namochenny' => ''
							   //'country' => 'US'
						  ]);
			});
			$parent_id    = $deal_id;
			$stuff_name   = json_encode($post_data['stuff-name']);
			$stuff_quantity    = json_encode($post_data['stuff-quant']);
			$stuff_ed_izm = json_encode($post_data['stuff-ed-izm']);
			$contract   = $post_data['contract'];
			$court_decision    = $post_data['court_decision'];
			$upal_namochenny = $post_data['upal_namochenny'];

			$pQuery->execute($parent_id, $stuff_name, $stuff_quantity, $stuff_ed_izm, $contract, $court_decision, $upal_namochenny);
			
			//вставляем строчку с вкладками
			$db = \Config\Database::connect();
			$pQuery = $db->prepare(function($db) {
				return $db->table('deal_statuses_tech')
						  ->insert([
							   'parent_id'		=> '',
							   'keeping_av' 	=> '',
							   'expertise_av'  	 => '',
							   'realiz_under_10k_av'   => '',
							   'realiz_more_10k_av'   => '',
							   'utiliz_av'  	 => '',
							   'destroy_av'		 => '',
							   'pererab_av' => '',
							   'otsenka_av' => ''
						  ]);
			});
			$parent_id = $deal_id;
			$keeping_av = '1';
			if ($post_data['status'] === 'experise') { $expertise_av = '0'; } else { $expertise_av = '0'; }
			if ($post_data['status'] === 'realize_less_10k') { $realize_less_10k = '0'; } else { $realize_less_10k = '0'; }
			if ($post_data['status'] === 'realize_more_10k') { $realize_more_10k = '0'; } else { $realize_more_10k = '0'; }
			if ($post_data['status'] === 'utilization') { $utilization = '0'; } else { $utilization = '0'; }
			if ($post_data['status'] === 'destroying') { $destroying = '0'; } else { $destroying = '0'; }
			if ($post_data['status'] === 'pererabotka') { $pererabotka = '0'; } else { $pererabotka = '0'; }
			if ($post_data['status'] === 'otsenka') { $otsenka = '0'; } else { $otsenka = '0'; }

			$pQuery->execute($parent_id, $keeping_av, $expertise_av, $realize_less_10k, $realize_more_10k, $utilization, $destroying, $pererabotka, $otsenka);
			
			//вставляем историю
			$db = \Config\Database::connect();
			$pQuery = $db->prepare(function($db) {
				return $db->table('history')
						  ->insert([
							   'parent_id'		=> '',
							   //'date_' 	=> '',
							   'company'  	 => '',
							   'change'   => '',
							   'user'   => '',
						  ]);
			});
			$parent_id = $deal_id;
			//$date = '1';
			$company = session()->get('lastname');
			$change = 'Дело создано';
			$user = session()->get('firstname');

			$pQuery->execute($parent_id, $company, $change, $user);
			
			//сохраняем файлы актов пп
			$fileNumberInOrder = '0';
			foreach($post_files['act_pp_files'] as $file) {
				  if ($file->isValid() && ! $file->hasMoved()) {
					$newName = $file->getRandomName();
					$file->move(WRITEPATH.'files/'.$parent_id.'/poruchenie/', $newName);
					
					//пишем в files
					$db = \Config\Database::connect();
					$pQuery = $db->prepare(function($db) {
						return $db->table('files')
								  ->insert([
									   'parent_id'		=> '',
									   'name'  	 => '',
									   'file_size'   => '',
									   'path'   => '',
									   'type' => ''
								  ]);
					});
					$parent_id = $deal_id;
					$filename = $newName;
					$filesize = $file->getSize();
					$filepath = '/files/'.$parent_id.'/poruchenie/'.$newName;
					$filetype = 'poruchenie';

					$pQuery->execute($parent_id, $filename, $filesize, $filepath, $filetype);
					//id вставленного файла
					$file_id = $db->insertID();
					
					//вставляем в acts pp
					$db = \Config\Database::connect();
					$pQuery = $db->prepare(function($db) {
						return $db->table('poruchenie')
								  ->insert([
									   'parent_id'		=> '',
									   'act_number'  	 => '',
									   'act_date'   => '',
									   'file_'   => '',
									   'is_active' => '',
								  ]);
					});
					$parent_id = $deal_id;
					$act_number = $post_data['new_act-pp-number'][$fileNumberInOrder];
					$act_date = $post_data['new_act-pp-date'][$fileNumberInOrder];
					$file_ = $file_id;
					$is_active = '1';

					$pQuery->execute($parent_id, $act_number, $act_date, $file_, $is_active);
					
					$fileNumberInOrder++;
				  }
			}
	
	//return $results;
	}
	
	public function overlookDealFormAndCreateHistory($deal_id, $post_data) {
		$history_array = array();
		if(session()->get('role_id') === 'admin' || session()->get('role_id') === 'superadmin') {
			$history_array[] = 'Номер МЛН: '.$post_data['MLN'];
			$history_array[] = 'Ф-ка: '.$post_data['fka'];
			$history_array[] = 'Контракт: '.$post_data['contract'];
			if(@post_data['court_decision'] !== null) { $history_array[] = 'Решение суда: '.$post_data['court_decision']; }
			$history_array[] = 'Уполномоченный орган: '.$post_data['upal_namochenny'];
				foreach($post_data['stuff-name'] as $im) {
					$im_arr[] = $im;
				}
				foreach($post_data['stuff-quant'] as $qu) {
					$qu_arr[] = $qu;
				}
			$history_array[] = 'Имущество: '.implode(', ', $im_arr).' в количестве '.implode(', ', $qu_arr);
			//ответственный
			$db = \Config\Database::connect(); $builder = $db->table('qusers');
			$resultResp = $builder->getWhere(['id' => $post_data['responsible']], null, null); 
			foreach ($resultResp->getResult() as $row) {
				$respons_name = $row->firstname;
				$respons_org = $row->lastname;
			}
			$history_array[] = 'Ответственный: '.$respons_org.' ('.$respons_name.')';
			//статус
			if(@$post_data['status'] === 'keeping') { $status_hist = 'хранение'; }
			if(@$post_data['status'] === 'expert')  { $status_hist = 'экспертиза'; }
			if(@$post_data['status'] === 'realize_less_10k') { $status_hist = 'реализация до 10 тыс.'; }
			if(@$post_data['status'] === 'realize_more_10k') { $status_hist = 'реализация свыше 10 тыс.'; }
			if(@$post_data['status'] === 'utilization')  { $status_hist = 'утилизация'; }
			if(@$post_data['status'] === 'destroying') { $status_hist = 'уничтожение'; }
			if(@$post_data['status'] === 'pererabotka') { $status_hist = 'переработка'; }
			if(@$post_data['status'] === 'otsenka') { $status_hist = 'оценка'; }
			$history_array[] = 'Статус: '.$status_hist;
		}
		//поручение(я) росимущества
		if(@$_FILES['act_pp_files']['size'][0]>1) {
			$k = 0;			
			while (@$_FILES['act_pp_files']['name'][$k]) {
				$history_array[] = 'Загружен файл '.$_FILES['act_pp_files']['name'][$k].' размером '.$_FILES['act_pp_files']['size'][$k].' байт с поручением №'.$post_data['new_act-pp-number'][$k].' от '.$post_data['new_act-pp-date'][$k];
				$k++;
			} 
		}
		//акт приема-передачи
		if(@$_FILES['file_poruchenie_upload']['size'][0]>1) { $history_array[] = 'Загружен файл акта приема-передачи '.$_FILES['file_poruchenie_upload']['name'][0].' размером '.$_FILES['file_poruchenie_upload']['size'][0].' байт'; } 
		//складская квитанция
		if(@$_FILES['files_warehouse_invoice_upload']['size'][0]>1) { $history_array[] = 'Загружен файл подписанной складской квитанции '.$_FILES['files_warehouse_invoice_upload']['name'][0].' размером '.$_FILES['files_warehouse_invoice_upload']['size'][0].' байт'; } 
		//фото принятого на хранение
		if(@$_FILES['files_photo_keeping_upload']['size'][0]>1) {
			$k = 0;			
			while (@$_FILES['files_photo_keeping_upload']['name'][$k]) {
				$history_array[] = 'Загружена фотография '.$_FILES['files_photo_keeping_upload']['name'][$k].' размером '.$_FILES['files_photo_keeping_upload']['size'][$k].' байт с принятым на хранением имуществом';
				$k++;
			} 
		}
		//объем имущества на складе
		if(isset($post_data['imusch_objem'])) { $history_array[] = 'Занимаемый объем: '.$post_data['imusch_objem'].' м3'; } 
		
		//адрес склада
		if(isset($post_data['sklad_address'])) { 
			$db = \Config\Database::connect(); $builder = $db->table('resp_organiz_sklady');
			$resultSklad = $builder->getWhere(['id' => $post_data['sklad_address']], null, null); 
			foreach ($resultSklad->getResult() as $row) {
				$sklad_address = $row->address;
			}	
		$history_array[] = 'Адрес склада хранения: '.$sklad_address;
		}
				
		//вставляем историю
		$db = \Config\Database::connect();
		$pQuery = $db->prepare(function($db) {
			return $db->table('history')
					  ->insert([
						   'parent_id'		=> '',
						   //'date_' 	=> '',
						   'company'  	 => '',
						   'change'   => '',
						   'user'   => '',
					  ]);
		});
		$parent_id = $deal_id;
		//$date = '1';
		$company = session()->get('lastname');
		$change = implode("\n", $history_array);
		$user = session()->get('firstname');
		
		$pQuery->execute($parent_id, $company, $change, $user);
	}
	
	public function redactExistingDealByPostForm($deal_id, $post_data, $post_files) {

		//если редактировал админ или супер админ, возможно изменились все поля
		//значит их все нужно проверить
		if (session()->get('role_id') === 'admin' || session()->get('role_id') === 'superadmin') {
				//проверяем изменился ли ответственный
				$db = \Config\Database::connect(); $builder = $db->table('deals_main'); $query = $builder->getWhere(['id' => $deal_id], null, null);
				$row = $query->getResult(); $row = get_object_vars($row[0]); $otvetstvennyi = $row['respons_id'];
				if($otvetstvennyi !== $post_data['responsible']) {
					//снова закрываем все кроме хранения
					$db      = \Config\Database::connect();
					$builder = $db->table('deal_statuses_tech');	
					$data = [
					    'keeping_av' => '1',
					    'expertise_av' => '0',
						'realiz_under_10k_av' => '0',
						'realiz_more_10k_av' => '0',
						'utiliz_av' => '0',
						'destroy_av' => '0',
						'pererab_av' => '0',
						'otsenka_av' => '0',
					];
					$builder->where('parent_id', $deal_id);
					$builder->update($data);
					
					//ставим 1 что изменился ответственный
					$builder = $db->table('deals_main');	
					$data = [
						'is_respons_changed' => '1'
					];
					$builder->where('id', $deal_id);
					$builder->update($data);
					
				}
				//пишем изменения основных полей
				$db      = \Config\Database::connect();
				$builder = $db->table('deals_main');	
				$data = [
					'MLN' => $post_data['MLN'],
					'fka' => $post_data['fka'],
					'status' => $post_data['status'],
					'respons_id' => $post_data['responsible'],
				];
				$builder->where('id', $deal_id);
				$builder->update($data);

				//и дополнительных
				$db      = \Config\Database::connect();
				$builder = $db->table('deals_main_info');	
				$data = [
					'stuff_name' => json_encode($post_data['stuff-name']),
					'stuff_quantity' => json_encode($post_data['stuff-quant']),
					'contract' => $post_data['contract'],
					'court_decision' => $post_data['court_decision'],
					'upal_namochenny' => $post_data['upal_namochenny']
				];
				$builder->where('parent_id', $deal_id);
				$builder->update($data);
				
				//сохраняем файлы актов поручений если они добавлены
				if(@$post_files['act_pp_files'][0] !== null) {
					
					//ставим всем остальным поручениям 0 в актуальность
					$builder = $db->table('poruchenie');	
					$data = [
						'is_active' => '0'
					];
					$builder->where('parent_id', $deal_id);
					$builder->update($data);
							
					$fileNumberInOrder = '0';
					foreach($post_files['act_pp_files'] as $file) {
						  if ($file->isValid() && ! $file->hasMoved()) {
							$newName = $file->getRandomName();
							$file->move(WRITEPATH.'files/'.$deal_id.'/poruchenie/', $newName);
							
							//пишем в files
							$db = \Config\Database::connect();
							$pQuery = $db->prepare(function($db) {
								return $db->table('files')
										  ->insert([
											   'parent_id'		=> '',
											   'name'  	 => '',
											   'file_size'   => '',
											   'path'   => '',
											   'type' => ''
										  ]);
							});
							$filename = $newName;
							$filesize = $file->getSize();
							$filepath = '/files/'.$deal_id.'/poruchenie/'.$newName;
							$filetype = 'poruchenie';

							$pQuery->execute($deal_id, $filename, $filesize, $filepath, $filetype);
							//id вставленного файла
							$file_id = $db->insertID();

							//вставляем в acts pp
							$db = \Config\Database::connect();
							$pQuery = $db->prepare(function($db) {
								return $db->table('poruchenie')
										  ->insert([
											   'parent_id'		=> '',
											   'act_number'  	 => '',
											   'act_date'   => '',
											   'file_'   => '',
											   'is_active' => '',
										  ]);
							});
							$parent_id = $deal_id;
							$act_number = $post_data['new_act-pp-number'][$fileNumberInOrder];
							$act_date = $post_data['new_act-pp-date'][$fileNumberInOrder];
							$file_ = $file_id;
							$is_active = '1';

							$pQuery->execute($parent_id, $act_number, $act_date, $file_, $is_active);
							
							$fileNumberInOrder++;
						}
					}
				}
		}		
		//конец админских полей

		//сохраняем адрес склада если он указан
		if(isset($post_data['sklad_address'])) {
			$sklad_address = $post_data['sklad_address']; 
			//проверим есть ли такой и принадлежит ли он этой конторе
			$quser_id = session()->get('id');
			$db = \Config\Database::connect(); $builder = $db->table('resp_organiz_sklady');
			$result1 = $builder->getWhere(['qusers_id' => $quser_id, 'id' => $sklad_address], null, null); 
			$res1 = $result1->getResult();
			if ($res1 || (session()->get('role_id') === 'admin' || session()->get('role_id') === 'superadmin')) { //если есть такая то идем дальше, равно как если это админы
					//и вставляем склад в инфо о сделке
					$db = \Config\Database::connect();
					$builder = $db->table('deals_main_info');	
					$data = [
						'sklad_id' => $sklad_address
					];
					$builder->where('parent_id', $deal_id);
					$builder->update($data);
			}
			else { return array ('status' => false, 'message' => 'Склад может менять только ответственный за хранение имущества или администратор системы'); }
		}
		
		//сохраняем объем если он указан
		if(isset($post_data['imusch_objem'])) {
			$volume = $post_data['imusch_objem']; 
			$db = \Config\Database::connect();
			$builder = $db->table('deals_main_info');	
			$data = [
				'imusch_objem' => $volume
			];
			$builder->where('parent_id', $deal_id);
			$builder->update($data);
		}
		
		//сохраняем файл подписанного поручения
		foreach($post_files['file_poruchenie_upload'] as $file) {
			if ($file->isValid() && ! $file->hasMoved()) {
				  
				$newName = $file->getRandomName();
				$file->move(WRITEPATH.'files/'.$deal_id.'/keeping/poruchenie_signed/', $newName);
				//пишем в files
				$db = \Config\Database::connect();
				$pQuery = $db->prepare(function($db) {
					return $db->table('files')
							  ->insert([
								   'parent_id'		=> '',
								   'name'  	 => '',
								   'file_size'   => '',
								   'path'   => '',
								   'type' => ''
							  ]);
				});
				$parent_id = $deal_id;
				$filename = $newName;
				$filesize = $file->getSize();
				$filepath = '/files/'.$deal_id.'/keeping/poruchenie_signed/'.$newName;
				$filetype = 'keeping_poruchenie_signed';
				$pQuery->execute($parent_id, $filename, $filesize, $filepath, $filetype);
				//id вставленного только что поручения
				$poruchenie_file_id = $db->insertID();
				
				//пишем что responsible_changed больше не 1, т.к. поручение принято
				if($poruchenie_file_id) {
					$builder = $db->table('deals_main');	
					$data = [
						'is_respons_changed' => '0'
					];
					$builder->where('id', $deal_id);
					$builder->update($data);
					}
				
				//пишем в keeping
				$db = \Config\Database::connect();
				$pQuery = $db->prepare(function($db) {
					return $db->table('deals_main_keeping')
							  ->insert([
								   'parent_id'		=> '',
								   'type'  	 => '',
								   'file_id'   => '',
								   'file_size' => '',
								   'user_date'   => ''
							  ]);
				});
				$parent_id = $deal_id;
				$type = 'poruchenie_signed';
				$fileid = $poruchenie_file_id;
				$filesize = $file->getSize();
				$userdate = $post_data['act_keeping_upload_date'];
				$pQuery->execute($parent_id, $type, $fileid, $filesize, $userdate);
				
				//открываем вторую вкладку если надо
				$builder = $db->table('deals_main');
				$query = $builder->getWhere(['id' => $deal_id], null, null);
				$row = $query->getResult(); 
				$row = get_object_vars($row[0]);
				$status = $row['status']; 
			}
		}
		
		//пишем видимость вкладов в зависимости от статуса
		if(@$post_data['status'] === 'expert')  { $db = \Config\Database::connect(); $builder = $db->table('deal_statuses_tech'); $data = ['expertise_av' => '1']; $builder->where('parent_id', $deal_id); $builder->update($data); }
		if(@$post_data['status'] === 'realize_less_10k') { $db = \Config\Database::connect(); $builder = $db->table('deal_statuses_tech'); $data = ['realiz_under_10k_av' => '1']; $builder->where('parent_id', $deal_id); $builder->update($data); }
		if(@$post_data['status'] === 'realize_more_10k') { $db = \Config\Database::connect(); $builder = $db->table('deal_statuses_tech'); $data = ['realiz_more_10k_av' => '1']; $builder->where('parent_id', $deal_id); $builder->update($data); }
		if(@$post_data['status'] === 'utilization')  { $db = \Config\Database::connect(); $builder = $db->table('deal_statuses_tech'); $data = ['utiliz_av' => '1']; $builder->where('parent_id', $deal_id); $builder->update($data); }
		if(@$post_data['status'] === 'destroying') { $db = \Config\Database::connect(); $builder = $db->table('deal_statuses_tech'); $data = ['destroy_av' => '1']; $builder->where('parent_id', $deal_id); $builder->update($data); }
		if(@$post_data['status'] === 'pererabotka') { $db = \Config\Database::connect(); $builder = $db->table('deal_statuses_tech'); $data = ['pererabotka_av' => '1'];$builder->where('parent_id', $deal_id); $builder->update($data); }
		if(@$post_data['status'] === 'otsenka') { $db = \Config\Database::connect(); $builder = $db->table('deal_statuses_tech'); $data = ['otsenka_av' => '1'];$builder->where('parent_id', $deal_id); $builder->update($data); }
				
		//сохраняем файлы загруженных фотографий имущества
		foreach($post_files['files_photo_keeping_upload'] as $file) {
			if ($file->isValid() && ! $file->hasMoved()) {
				  
				$newName = $file->getRandomName();
				$file->move(WRITEPATH.'files/'.$deal_id.'/keeping/photos/', $newName);

				$db = \Config\Database::connect();
				$pQuery = $db->prepare(function($db) {
					return $db->table('files')
							  ->insert([
								   'parent_id'		=> '',
								   'name'  	 => '',
								   'file_size'   => '',
								   'path'   => '',
								   'type' => ''
							  ]);
				});
				$parent_id = $deal_id;
				$filename = $newName;
				$filesize = $file->getSize();
				$filepath = '/files/'.$deal_id.'/keeping/photos/'.$newName;
				$filetype = 'keeping_photo';
						
				$pQuery->execute($parent_id, $filename, $filesize, $filepath, $filetype);
				//id вставленной только что фотографии
				$photo_file_id = $db->insertID();
				
				//пишем в keeping
				$db = \Config\Database::connect();
				$pQuery = $db->prepare(function($db) {
					return $db->table('deals_main_keeping')
							  ->insert([
								   'parent_id'		=> '',
								   'type'  	 => '',
								   'file_id'   => '',
								   'file_size' => '',
							  ]);
				});
				$parent_id = $deal_id;
				$type = 'keeping_photo';
				$fileid = $photo_file_id;
				$filesize = $file->getSize();
				$pQuery->execute($parent_id, $type, $fileid, $filesize);
			}
		}
		
		//сохраняем складскую квитанцию
		foreach($post_files['files_warehouse_invoice_upload'] as $invoice) {
			if ($invoice->isValid() && ! $invoice->hasMoved()) {
				  
				$newName = $invoice->getRandomName(); 		
				$invoice->move(WRITEPATH.'files/'.$deal_id.'/keeping/warehouse_invoice_signed/', $newName);
				//пишем в files
				$db = \Config\Database::connect();
				$pQuery = $db->prepare(function($db) {
					return $db->table('files')
							  ->insert([
								   'parent_id'		=> '',
								   'name'  	 => '',
								   'file_size'   => '',
								   'path'   => '',
								   'type' => ''
							  ]);
				});
				$parent_id = $deal_id;
				$filename = $newName; 
				$filesize = $invoice->getSize();
				$filepath = '/files/'.$deal_id.'/keeping/warehouse_invoice_signed/'.$newName;
				$filetype = 'warehouse_invoice_signed';
				$pQuery->execute($parent_id, $filename, $filesize, $filepath, $filetype);
				//id вставленного только что файла
				$invoice_file_id = $db->insertID();
				
				//пишем в keeping
				$db = \Config\Database::connect();
				$pQuery = $db->prepare(function($db) {
					return $db->table('deals_main_keeping')
							  ->insert([
								   'parent_id'		=> '',
								   'type'  	 => '',
								   'file_id'   => '',
								   'file_size' => ''
							  ]);
				});
				$parent_id = $deal_id;
				$type = 'warehouse_invoice_signed';
				$fileid = $invoice_file_id;
				$filesize = $invoice->getSize();
				$pQuery->execute($parent_id, $type, $fileid, $filesize);
			}
		}
	return array( 'status' => true, 'message' => '');
	}
	
}