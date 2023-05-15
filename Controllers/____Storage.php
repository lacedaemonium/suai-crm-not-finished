<?php
namespace App\Controllers;

class Storage extends BaseController
{
    public function index()
    {    
         return view('storage');
    }
 
   public function store()
   {  
 
     helper(['form', 'url']);
	 
        $validated = $this->validate([
            'file' => [
                'uploaded[file]',
                'mime_in[file,image/jpg,image/jpeg,image/gif,image/png]',
                'max_size[file,4096]',
            ],
        ]);
 
        $msg = 'Выбран некорректный файл';
  
    if ($validated) {
            $avatar = $this->request->getFile('file');
            $newName = rand(0,12); $folder = '/666666/';
			$avatar->move(WRITEPATH.'files'.$folder, $newName);

          $data = [
 
            'name' =>  $avatar->getClientName(),
            //'size'  => $avatar->getClientMimeType()
          ];
		  
		$db = \Config\Database::connect();
        $builder = $db->table('files');
 
        $save = $builder->insert($data);
        $msg = 'Файл был загружен';
    }
 
       return redirect()->to( base_url('/storage') )->with('msg', $msg);
 
    }
}