<?php
namespace App\Controllers;

//use App\Models\DealModel;
use CodeIgniter\Controller;

class FilesDownloader extends BaseController {
	
	public function __construct() {
			
		$this->db = db_connect();
		
		function checkIfAdmin() { //возвращает 1 если админ и 0 если юзер
			if(session()->get('role_id') === 'admin' || session()->get('role_id') === 'superadmin') { return true; } else { return false; }
		}
		
		function checkIfFilesExists() { //возвращает 1 если файл есть и 0 если нет
			$file_id = $_GET['id']; $file_id = preg_replace("/[^0-9]/", '', $_GET['id']);
			
			$db = \Config\Database::connect();
			$builder = $db->table('files');
			
			$query = $builder->getWhere(['id' => $file_id], null, null);
			
			foreach ($query->getResult() as $row) {
				if($row->id) { return true; }
			}
		
		return false;
		}
		
		function checkIfUserHasAccesToFile() {
			$user_id = session()->get('id');
			$file_id = $_GET['id']; $file_id = preg_replace("/[^0-9]/", '', $_GET['id']);  
			$db = \Config\Database::connect();
			//id родительского дела
			$builder = $db->table('files');
			$query = $builder->getWhere(['id' => $file_id], null, null);
			$row = $query->getResult();
			$row = get_object_vars($row[0]);
			$parent_deal_id = $row['parent_id']; 
			//id ответственного в родительском деле
			$builder = $db->table('deals_main');
			$query = $builder->getWhere(['id' => $parent_deal_id], null, null);
			$row = $query->getResult(); 
			$row = get_object_vars($row[0]);
			$respons_id = $row['respons_id']; 
			//даем права если совпадают
			if($respons_id === $user_id) { return true; }
		return false;
		}
		
		function downloadFile() {
			$file_id = $_GET['id']; $file_id = preg_replace("/[^0-9]/", '', $_GET['id']);
			$db = \Config\Database::connect();
			$builder = $db->table('files');
			$query = $builder->getWhere(['id' => $file_id], null, null);
			$row = $query->getResult();
			$row = get_object_vars($row[0]);
			$path_to_file = $row['path'];
			$file_name = $row['name'];
			$path_long = '/var/www/suai/writable'.$path_to_file;
			$extension = explode(".", $path_to_file); $extension = end($extension);
			if (file_exists($path_long)) {
				header('Content-Type: '.$extension);
				header('Content-Disposition: attachment; filename='.$file_name);
				header('Pragma: no-cache');
				readfile($path_long);
				exit;
			}
		}
	}	
	
	public function getFile() {
		//проверяем есть ли такой файл
		$isFileExists = checkIfFilesExists(); 
		if(!$isFileExists) { return 'net takogo file'; }			
		//проверяем есть ли на него права
		$access_to_file = checkIfAdmin();
		if(!$access_to_file) { 
			$user_has_access = checkIfUserHasAccesToFile(); 
			if(!$user_has_access) { return 'sorry net prav'; }
		}
		downloadFile();		
	}
	
	public function getOrderConsent() {
		require_once '/var/www/suai/vendor/autoload.php';
		
		$order_id = $_GET['id']; $order_id = preg_replace("/[^0-9]/", '', $_GET['id']);
		
		//достаем предыдущего ответственного, номер и дату поручения
		$db = \Config\Database::connect();
		$builder = $db->table('poruchenie');
		$query = $builder->getWhere(['id' => $order_id], null, null);
		$row = $query->getResult();
		//проверяем есть ли файл
		if(!$row) { 
			$data['message'] = 'Ошибка доступа'; 
			echo view('templates/header', $data);
			echo view('message');
			echo view('templates/footer');
			return;
		} 
		//проверяем есть ли права
		$row = get_object_vars($row[0]); 
		$parent_deal_id = $row['parent_id'];

		$db = \Config\Database::connect();
		$builder = $db->table('deals_main');
		$query = $builder->getWhere(['id' => $parent_deal_id], null, null);
		$row_main = $query->getResult();
		$row_main = get_object_vars($row_main[0]);
		$respons_id = $row_main['respons_id'];
		if($respons_id === session()->get('id') || session()->get('role_id') === 'admin' || session()->get('role_id') === 'admin') { 		
			$upal_namochenny = $row['previous_respons'];
			$poruch_number = $row['act_number'];
			$poruch_date = $row['act_date'];	
					
			$_doc = new \PhpOffice\PhpWord\TemplateProcessor('/var/www/suai/app/ThirdParty/PhpWord/Templates/OrderConsent.docx');
			$_doc->setValue('upal_namochenny', $upal_namochenny);
			$_doc->setValue('poruchenie_number', $poruch_number);
			$_doc->setValue('poruchenie_date', $poruch_date);
			$_doc->setValue('name', session()->get('firstname'));
			$_doc->setValue('company', session()->get('lastname'));
			$_doc->setValue('date', date("Y-m-d H:i:s"));
			
			$randomName = rand(1,9999999);
			$_doc->saveAs('/var/www/suai/writable/temp/'.$randomName.'.docx');
			$pdf_generator = shell_exec("export HOME=/tmp/ && /usr/bin/soffice --headless --convert-to pdf /var/www/suai/writable/temp/$randomName.docx --outdir /var/www/suai/writable/temp/");
			
			header('Content-Description: File Transfer');
			header('Content-Type: application/pdf');
			header('Content-Disposition: attachment; filename="Поручение подписанное.pdf"');
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Content-Length: ' . filesize('/var/www/suai/writable/temp/'.$randomName.'.pdf'));
			ob_clean();
			flush();
			readfile('/var/www/suai/writable/temp/'.$randomName.'.pdf');
			shell_exec ("rm -rf /var/www/suai/writable/temp/$randomName.pdf && rm -rf /var/www/suai/writable/temp/$randomName.docx");
			exit;
		} 
		
		else { 
			$data['message'] = 'Ошибка доступа'; 
			echo view('templates/header', $data);
			echo view('message');
			echo view('templates/footer');
			return; 
		}
	}
	
	public function getWarehouseInvoice() {
		require_once '/var/www/suai/vendor/autoload.php';
		
		$order_id = $_GET['id']; $order_id = preg_replace("/[^0-9]/", '', $_GET['id']);
		
		//проверяем есть ли дело
		$db = \Config\Database::connect();
		$builder = $db->table('deals_main');
		$query = $builder->getWhere(['id' => $order_id], null, null);
		$row_main = $query->getResult();
		if(!$row_main) { 
			$data['message'] = 'Ошибка доступа'; 
			echo view('templates/header', $data);
			echo view('message');
			echo view('templates/footer');
			return;
		} 
		//проверяем есть ли права
		$row_main = get_object_vars($row_main[0]); 
		$respons_id = $row_main['respons_id'];
		if($respons_id !== session()->get('id') && session()->get('role_id') !== 'admin' && session()->get('role_id') !== 'superadmin' ) { 
			$data['message'] = 'Ошибка доступа'; 
			echo view('templates/header', $data);
			echo view('message');
			echo view('templates/footer');
		}
		else {
			//достаем фку
			$db = \Config\Database::connect();
			$builder = $db->table('deals_main');
			$query = $builder->getWhere(['id' => $order_id], null, null);
			$row = $query->getResult();
			$row = get_object_vars($row[0]);
			$fka = $row['fka'];
			//поручение
			$builder = $db->table('poruchenie');
			$query = $builder->getWhere(['parent_id' => $order_id, 'is_active' => '1'], null, null);
			$row = $query->getResult();
			$row = get_object_vars($row[0]);
			$act = $row['act_number'];
			//достаем имущество и адрес склада
			$builder = $db->table('deals_main_info');
			$query = $builder->getWhere(['parent_id' => $order_id], null, null);
			$row = $query->getResult();
			$row = get_object_vars($row[0]);
			$contract = $row['contract'];
			$court_decision = $row['court_decision'];
			$imuschestvo = json_decode($row['stuff_name']);
			$imuschestvo = implode(', ', $imuschestvo);
			$sklad_id = $row['sklad_id'];
			
			$builder = $db->table('resp_organiz_sklady');
			$query = $builder->getWhere(['id' => $sklad_id], null, null);
			$row = $query->getResult();
			//если вдруг нет склада
			if(!$row) { $data['message'] = 'Ошибка доступа';  echo view('templates/header', $data); echo view('message');	echo view('templates/footer'); return; }
			$sklad_address = get_object_vars($row[0]); 
			$sklad_address = $sklad_address['address'];	
			
			$_doc = new \PhpOffice\PhpWord\TemplateProcessor('/var/www/suai/app/ThirdParty/PhpWord/Templates/WarehouseInvoice.docx');
			$_doc->setValue('date', date("d.m.Y"));
			$_doc->setValue('contract', $contract);
			$_doc->setValue('firstname', session()->get('firstname'));
			$_doc->setValue('lastname', session()->get('lastname'));
			$_doc->setValue('sklad_address', $sklad_address);
			$_doc->setValue('fka', $fka);
			$_doc->setValue('court_decision', $court_decision);
			$_doc->setValue('imuschestvo', $imuschestvo);
			$_doc->setValue('osnovanie', $act);

			$randomName = rand(1,9999999);
			$_doc->saveAs('/var/www/suai/writable/temp/'.$randomName.'.docx');
			$pdf_generator = shell_exec("export HOME=/tmp/ && /usr/bin/soffice --headless --convert-to pdf /var/www/suai/writable/temp/$randomName.docx --outdir /var/www/suai/writable/temp/");
			
			header('Content-Description: File Transfer');
			header('Content-Type: application/pdf');
			header('Content-Disposition: attachment; filename="Складская квитанция.pdf"');
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Content-Length: ' . filesize('/var/www/suai/writable/temp/'.$randomName.'.pdf'));
			ob_clean();
			flush();
			readfile('/var/www/suai/writable/temp/'.$randomName.'.pdf');
			shell_exec ("rm -rf /var/www/suai/writable/temp/$randomName.pdf && rm -rf /var/www/suai/writable/temp/$randomName.docx");
			exit;
		}
	}
}