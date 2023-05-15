<?php
namespace App\Controllers;

use App\Models\DealModel;
use CodeIgniter\Controller;

class DealEdit extends BaseController {
	
	protected $table = 'deals_main';

	public function showForm() {
		$uri = service('uri'); $deal_id = $uri->getSegment(3); $deal_id = preg_replace("/[^,.0-9]/", '', $deal_id);
		
		$model = new DealModel();
				
		$data = [
            'header'  => $model->getOneDeal($deal_id),
			'responsible_users' => $model->createNewDealPageLoad()
        ];
		//print_r($data); die;		
		echo view('templates/header', $data);
		echo view('deal_edit');
		echo view('templates/footer');
		
	}
	
	public function postForm() { //обновление дела через отправку post формы
		//var_dump($_POST);die;
		$uri = service('uri'); $deal_id = $uri->getSegment(3);
			
		$data['validation'] = ''; $poruchenieValidated = true; $htmlFormValidated = true;
		$data['message'] = ''; $photosValidated = true;
		//$data['header'] = false;
		$htmlFormValidated = false;
		
		//здесь все правила формы которые мы валидируем
		$rules = [
			//'act_keeping_upload_date' => [
			//	'label'  => 'act_keeping_upload_date',
			//	'rules'  => 'required|valid_date',
			//	'errors' => [
			//		'required' => 'Укажите дату подписания поручения',
			//		'valid_date' => 'Дата подписания поручения указана неверно'
			//	]
			//],
			//'sklad_address' => [
			//	'label'  => 'sklad_address',
			////	'rules'  => 'required|numeric',
			//	'errors' => [
			//		'required' => 'Укажите адрес склада, где хранится имущество',
			//		'numeric' => 'Незивестная ошибка'
			//	]
			//]
		];
		
		//if (!$this->validate($rules)) {
		if ($this->validate($rules)) {
			$data['validation'] = $this->validator; 
				echo view('templates/header', $data);
				echo view('message');
				echo view('templates/footer');
				return;
			}
		
		else {
			//Форма валидировалась без ошибок
			$htmlFormValidated = true;
		}	

		//проверяем файл поручения
		if($poruchenie = $this->request->getFiles('file_poruchenie_upload')) {
			foreach($poruchenie['file_poruchenie_upload'] as $por) {
			//$poruchenieValidated = false;
			  if ($por->isValid() && ! $por->hasMoved()) {
				  $poruchenieValidated = true;
			  }
		   }
		}
		
		//проверяем файлы фотографий
		if($poruchenie = $this->request->getFiles('files_photo_keeping_upload')) {
			//$photosValidated = false;
			foreach($poruchenie['files_photo_keeping_upload'] as $photo) {
			
			  if ($photo->isValid() && ! $photo->hasMoved()) {
				  $photosValidated = true;
			  }
		   }
		}
		
		
		$model = new DealModel();
		if ($poruchenieValidated && $photosValidated) { 
			//валидировано;
			$post_data = $this->request->getPost(); //var_dump($post_data); die;
			$post_files = $this->request->getFiles();	
			$deal_updating = $model->redactExistingDealByPostForm($deal_id, $post_data, $post_files);
		} 
		else { 
			//не валидировалось
			echo 'not valid'; die; }
		
		//если обновилось с ошибками
		if($deal_updating['status'] === false) { 
			$data['message'] = $deal_updating['message'];
			echo view('templates/header', $data);
			echo view('message');
			echo view('templates/footer');
			return;
		} 
		
		//если без ошибок
		//пишем историю 
		$deal_history = $model->overlookDealFormAndCreateHistory($deal_id, $post_data);
		
		//загружаем новую страницу с этой же сделкой
		$data = [
            'header'  => $model->getOneDeal($deal_id),
			'responsible_users' => $model->createNewDealPageLoad(),
			'message' => 'Изменения сохранены'
		];
		
		echo view('templates/header', $data);
		echo view('deal_edit');
		echo view('templates/footer');
		
	}
	
	public function newForm() {
		
		helper(['form', 'url']);
		
		$model = new DealModel();
		
		$data = [
            'data'  => $model->createNewDealPageLoad(),
        ];
		
		$data['validation'] = '';
		$data['showForm'] = true;
		$data['message'] = '';
		
		//print_r($data); die;
		
		echo view('templates/header', $data);
		echo view('new_deal');
		echo view('templates/footer');
		
	}
	
	
	public function newFormWorkOut() {  
	helper(['form', 'url']);
		
		$htmlFormValidated = false; $filesAreValidated = false;
		$data['validation'] = '';
		$data['showForm'] = true;
		$data['message'] = '';
		
		$rules = [
			'MLN' => [
				'label'  => 'MLN',
				'rules'  => 'required|min_length[3]',
				'errors' => [
					'required' => 'Требуется указать MLN',
					'min_length' => 'MLN должен содержать не менее 3 символов',
				]
			],

			'court_decision' => [
				'label'  => 'court_decision',
				'rules'  => 'required|min_length[3]',
				'errors' => [
					'required' => 'Требуется указать пароль',
					'min_length' => 'Решение суда должно содержать не менее 3 символов',
				]
			],
			'act_pp_files' => [
				'rules' => 'uploaded[act_pp_files]|max_size[act_pp_files,15000]|ext_in[act_pp_files,jpg,jpeg,png,docx,doc,pdf,rtf,txt]', 
				'errors' => [
					'max_size' => 'Файл либо слишком большой либо не подходит по расширению. Допускаются файлы до 15 мегабайт в расширениях: .jpg, .png, .docx, .pdf'
					]
				]
			];

		
		if (!$this->validate($rules))
			$data['validation'] = $this->validator;
		else {
			//Форма валидировалась без ошибок
			$htmlFormValidated = true;
		}
		
		//проверяем файлы
		if($imagefile = $this->request->getFiles())	{
		foreach($imagefile['act_pp_files'] as $file) {
			//Файл валидировался без ошибок
			$filesAreValidated = false;
			  if ($file->isValid() && ! $file->hasMoved()) {
				  $filesAreValidated = true;
			  }
		   }
		}
		
		if($htmlFormValidated && $filesAreValidated) {
			//все валидировалось! Пишем!
			$model = new DealModel();
			$post_data = $this->request->getPost();
			$post_files = $this->request->getFiles();
			
			$newDeal = [
					'MLN' => $this->request->getPost('MLN'),
					'fka' => $this->request->getPost('fka'),
					'status' => $this->request->getPost('status'),
					'respons_id' => $this->request->getPost('responsible'),
				];

			$model->insert($newDeal);
			$new_deal_id = $model->getInsertID();
				
			//создаем сущности в других таблицах
			$creatingNewDeal = $model->putNewDealIntoDB($new_deal_id, $post_data, $post_files);

			$data['showForm'] = false; 
			$data['message'] = 'Новое дело успешно добавлено! Перейдите в раздел <a href="/deals">список всех дел</a>, чтобы увидеть его.';
		}
		
		echo view('templates/header', $data);
		echo view('new_deal');
		echo view('templates/footer');	
		
	}
}