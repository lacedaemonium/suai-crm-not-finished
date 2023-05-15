<?php
//var_dump($header);die;

function onlyAdminCanRedact() {
	if (session()->get('role_id') != 'superadmin' && (session()->get('role_id') != 'admin')) {
		echo 'disabled';
	}
}

function onlyUserCanRedact() {
	if (session()->get('role_id') != 'user') {
	echo 'disabled';
	}
}
?>

<div class="container mt-3">
	<?php //print_r($header);die;?>
	<?php if ($header === 'fuckoff') : ?>
		<div class="row">
			<div class="col-12 col-sm-6 mt-3">
				<div class="alert alert-danger">
					<div>ДОСТУП ЗАПРЕЩЕН</div>
				</div>
			</div>
		</div>
		
	<?php else: 
	
	//file functions
	//for tabs
	function isTabKeepingAv($header) {
		if($header['tabs_statuses']['keeping_av'] !== '1') {
			echo 'disabled';
		}
	}
	function isTabExpertiseAv($header) {
		if($header['tabs_statuses']['expertise_av'] !== '1' || $header['is_respons_changed'] === '1') {
			echo 'disabled';
		}
	}
	function isTabUnder10kAv($header) {
		if($header['tabs_statuses']['realiz_under_10k_av'] !== '1' || $header['is_respons_changed'] === '1') {
			echo 'disabled';
		}
	}
	function isTabMore10kAv($header) {
		if($header['tabs_statuses']['realiz_more_10k_av'] !== '1' || $header['is_respons_changed'] === '1') {
			echo 'disabled';
		}
	}
	function isTabUtilizAv($header) {
		if($header['tabs_statuses']['utiliz_av'] !== '1' || $header['is_respons_changed'] === '1') {
			echo 'disabled';
		}
	}
	function isTabDestroyAv($header) {
		if($header['tabs_statuses']['destroy_av'] !== '1' || $header['is_respons_changed'] === '1') {
			echo 'disabled';
		}
	}
	function isTabPererabAv($header) {
		if($header['tabs_statuses']['pererab_av'] !== '1' || $header['is_respons_changed'] === '1') {
			echo 'disabled';
		}
	}
	function isTabOtsenkaAv($header) {
		if($header['tabs_statuses']['otsenka_av'] !== '1' || $header['is_respons_changed'] === '1') {
			echo 'disabled';
		}
	}	
?>

	<?php if(@$message) : ?>
		<div class="row">
			<div class="col-12">
				<div class="alert alert-success">
					<div><?php print_r($message);?></div>
				</div>
			</div>
		</div>
	<?php endif; ?>
	
	<h1 class="mt-5">Дело <?php print_r($header['fka']); ?></h1>
		<form method="post" enctype="multipart/form-data"> 
			<div class="tabs mt-5">
				<input type="radio" name="inset" value="" id="tab_1" checked>
				<label class="lable-" for="tab_1">Общая<br>информация<br><br></label>

				<input type="radio" name="inset" value="" id="tab_2">
				<label class="lable-" for="tab_2">Поручение<br><br><br></label>
							
				<input type="radio" name="inset" value="" id="tab_3">
				<label class="lable-" for="tab_3">Хранение<br> <br> <br></label>
							
				<input type="radio" name="inset" <?php isTabExpertiseAv($header); ?> value="" id="tab_4">
				<label class="lable-<?php isTabExpertiseAv($header); ?>" for="tab_4">Экспертиза<br> <br> <br></label>
				
				<input type="radio" name="inset" <?php isTabUtilizAv($header); ?> value="" id="tab_7">
				<label class="lable-<?php isTabUtilizAv($header); ?>"  for="tab_7">Утилизация<br> <br> <br></label>
							
				<input type="radio" name="inset" <?php isTabDestroyAv($header); ?> value="" id="tab_8">
				<label class="lable-<?php isTabDestroyAv($header); ?>" for="tab_8">Уничтожение<br> <br> <br></label>
				
				<input type="radio" name="inset" <?php isTabPererabAv($header); ?> value="" id="tab_9">
				<label class="lable-<?php isTabPererabAv($header); ?>" for="tab_9">Переработка<br> <br> <br></label>
				
				<input type="radio" name="inset" <?php isTabOtsenkaAv($header); ?> value="" id="tab_11"> 
				<label class="lable-<?php isTabOtsenkaAv($header); ?>" for="tab_11">Оценка<br><br> <br> </label>
							
				<input type="radio" name="inset" <?php isTabUnder10kAv($header); ?> value="" id="tab_5">
				<label class="lable-<?php isTabUnder10kAv($header); ?>" for="tab_5">Реализация<br>до 10.000 руб.<br> <br> </label>
							
				<input type="radio" name="inset" <?php isTabMore10kAv($header); ?> value="" id="tab_6">
				<label class="lable-<?php isTabMore10kAv($header); ?>" for="tab_6">Реализация<br>свыше 10.000 руб.<br> <br> </label>
				


				<input type="radio" name="inset" value="" id="tab_10">
				<label for="tab_10">История<br>изменений<br><br></label>

				<div id="txt_1">
				<hr>
				
					<div class="container col-12">
						<div class="row">
							<div class="col-sm-2 mt-5" style="text-align: right;">Номер MLN:</div> 
							<div class="col-sm-3 mt-5">
								<input required name="MLN" type="textarea" <?php onlyAdminCanRedact(); ?> value="<?=$header['MLN'];?>"></div>
							<div class="col-sm-2 mt-5" style="text-align: right;">Решение суда:</div> 
							<div class="col-sm-3 mt-5"><input required name="court_decision" type="textarea" <?php onlyAdminCanRedact(); ?> value="<?=$header['court_decision'];?>"></div>
						</div>
						
						<div class="row">
							<div class="col-sm-2 mt-5" style="text-align: right;">Ф-ка:</div>
							<div class="col-sm-3 mt-5">
								<input required name="fka" type="textarea" <?php onlyAdminCanRedact(); ?> value="<?=$header['fka']; ?>"></input></div>
							<div class="col-sm-2 mt-5" style="text-align: right;">Уполномоченный орган:</div> 
							<div class="col-sm-3 mt-5"><input required name="upal_namochenny" type="textarea" <?php onlyAdminCanRedact(); ?> name="upal_namochenny" value="<?=$header['deals_main_info']['upal_namochenny'];?>"></div>
						</div>
						
						<div class="row">
							<div class="col-sm-2 mt-5" style="text-align: right;">Контракт:</div>
							<div class="col-sm-3 mt-5">
							<input name="contract" type="textarea" <?php onlyAdminCanRedact();?> value="<?=$header['deals_main_info']['contract'];?>"></input><br><span style="font-size: 8pt">(необязательно)</span></div>
						</div>
					
				
						<div class="row">
							<div class="col-sm-4 mt-5" style="text-align: right;">Перечень имущества:</div>
							<div class="col-sm-4 mt-5" style="text-align: right;">кол-во:</div>
						</div>
						
						<div class="row">
							<div class="col-sm-5" style="text-align: right;">
								<?php $stuff = json_decode($header['deals_main_info']['stuff_name']); foreach ($stuff as $st) : ?>
								<div class="mt-3"><input required <?php onlyAdminCanRedact(); ?> size="40" name="stuff-name[]" value="<?=$st?>" type="textarea"></div>
								<?php endforeach; ?>
							</div>
							
							<div class="col-sm-4" style="text-align: right;">
								<?php $stuff = json_decode($header['deals_main_info']['stuff_quantity']); foreach ($stuff as $st) : ?>
								<div class="mt-3"><input required <?php onlyAdminCanRedact(); ?> size="30" name="stuff-quant[]" value="<?=$st?>" type="textarea"></div>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
				<hr>
				</div>
				
				<div id="txt_2">
				<hr>
					<?php foreach ($header['poruchenie'] as $act) : ?>
					<div class="mt-5">
						<div class="row">
							<div class="col-sm-2 mt-2" style="text-align: right;">Статус:</div> 
							<div class="col-sm-3 mt-2">
								<?php if($act['is_active'] === '1') { echo '<span style="color: green"><b>Активно</b></span>'; } else { echo '<span style="color: red;">Неактивно</span>'; }?>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-2 mt-2" style="text-align: right;">Дата поручения:</div> 
							<div class="col-sm-3 mt-2">
								<input disabled type="date" value="<?php print_r($act['act_date']); ?>"></input>
							</div>
						</div>
						
						<div class="row">
							<div class="col-sm-2 mt-2" style="text-align: right;">Номер поручения:</div> 
							<div class="col-sm-3 mt-2">
								<input disabled value="<?php print_r($act['act_number']); ?>"></input>
							</div>
						</div>
						
						<div class="row">
							<div class="col-sm-2 mt-2" style="text-align: right;">Файл:</div> 
							<div class="col-sm-3 mt-2"><a href="/getFile?id=<?php print_r($act['file']);?>">Скачать</a></p>
							</div>
						</div>
						
					</div>
					<!-- <p>Айдишка: <input type="textarea" disabled value="<?php //print_r($act['id']); ?>"></input> </p>
						<p>Айди дела: <input type="textarea" disabled value="<?php //print_r($act['parent_id']); ?>"></input> </p>
						<p>Номер акта: <input type="textarea" disabled ></input> </p>
						<p>Дата акта: <input type="textarea" disabled></input> </p>
						<p>Активность: <input type="textarea" disabled value="<?php //print_r($act['is_active']); ?>"></input> </p> -->
					<?php endforeach; ?>
						
					<hr>
					
					<?php if (session()->get('role_id') === 'admin' || session()->get('role_id') === 'superadmin') : ?>
					
						<script>
							function addOneMoreActField() {
								event.preventDefault();
								$('#act_pp_uploading').append('<div class="row"><div class="col-sm-2 mt-5" style="text-align: right;">Дата поручения:</div><div class="col-sm-3 mt-5"><input required type="date" name="new_act-pp-date[]" value=""></input></div></div><div class="row"><div class="col-sm-2 mt-5" style="text-align: right;">Номер поручения:</div><div class="col-sm-3 mt-5"><input required name="new_act-pp-number[]" value=""></input></div></div><div class="row"><br><div class="form-group"><div class="col-sm-2 mt-5" style="text-align: right;"></div><div class="col-sm-3 mt-5"><input type="file" name="act_pp_files[]" class="form-control" id="file"></div></div></div>');
							}
						</script>
							
						<div id="act_pp_uploading">
							<div class="row">
								<div class="col-sm-2" style="text-align: right;"></div> 
								<div class="col-sm-3"><a class="btn btn-warning" onclick="addOneMoreActField();">Добавить еще одно поручение</a></div> 
							</div>						
						</div>	
						
						<hr>
						
						<div class="row mb-5">
							<div class="col-sm-4 mt-3" style="text-align: right;">Поверенный:</div>
							<div class="col-sm-4 mt-3">
								<?php if (session()->get('role_id') === 'admin' || session()->get('role_id') === 'superadmin') : ?>
									<select name="responsible"> 
										<?php foreach ($responsible_users['responsible_ids'] as $org) : ?>
											<option <?php if( $org['id'] === $header['respons_id']) { echo 'selected'; } ?>  value="<?=$org['id']?>"><?=$org['lastname']?></option>
										<?php endforeach; ?>
									</select>
								<?php endif; ?>
							</div>
						</div>
						
						<hr>
						
						<div class="row">
							<div class="col-sm-4 mt-3" style="text-align: right;">Статус поручения:</div>
							<div class="col-sm-4 mt-3">
								<select <?php onlyAdminCanRedact();?> name="status">
									<option id="status-select-1" <?php if($header['status'] === 'keeping') echo 'selected';?> value="keeping">Только хранение</option>
									<option id="status-select-2" <?php if($header['status'] === 'expert') echo 'selected';?> value="expert">Экспертиза</option>
									<option id="status-select-5" <?php if($header['status'] === 'utilization') echo 'selected';?> value="utilization">Утилизация</option>
									<option id="status-select-6" <?php if($header['status'] === 'destroying') echo 'selected';?> value="destroying">Уничтожение</option>
									<option id="status-select-7" <?php if($header['status'] === 'pererabotka') echo 'selected';?> value="pererabotka">Переработка</option>
									<option id="status-select-8" <?php if($header['status'] === 'otsenka') echo 'selected';?> value="otsenka">Оценка</option>
									<option id="status-select-3" <?php if($header['status'] === 'realize_less_10k') echo 'selected';?> value="realize_less_10k">Реализация до 10.000 руб.</option>
									<option id="status-select-4" <?php if($header['status'] === 'realize_more_10k') echo 'selected';?> value="realize_more_10k">Реализация свыше 10.000 руб.</option>

								</select>
							</div>
						</div>
						
					<?php endif; ?>
				</div>
				
				<div id="txt_3">
					
					<hr>
					
					<div class="row">							
						<div class="col-sm-2" style="text-align: right;"></div> 
						<div class="col-sm-3"><a class="btn btn-info" href="/getOrderConsent?id=<?=$act['id']?>">Заполнить уведомление о приеме</a><br><span style="font-size: 8pt; color: grey;">(на активное в данный момент поручение)</span></div>
					</div>
					
						<div class="container mt-5" id="act_keeping_upload"><b>Приложите акт приема-передачи</b>
							<div class="row mt-3">
									<div class="col-sm-2 mt-3" style="text-align: right;">Дата принятия на хранение:</div> 
									<div class="col-sm-3 mt-3"><input type="date" name="act_keeping_upload_date" value=""></input></div>
							</div>
						
							<div class="row mt-3"><br>
								<div class="form-group">
									<div class="col-sm-2 mt-3" style="text-align: right;">Подписанный акт П/П:</div> 
									<div class="col-sm-3 mt-3"><input type="file" name="file_poruchenie_upload[]" class="form-control" id="file"></div>
								</div> 
							</div>
						</div>	
						
						<?php if (@$header['deals_main_keeping'][0]) : ?>
						<div class="container mt-5"><b>Акты приема-передачи, подписанные ранее</b>
							<?php foreach ($header['deals_main_keeping'] as $keeping_file) : 
								 if($keeping_file['type'] === 'poruchenie_signed') : ?>
										<div class="row mt-3">
											<div class="col-sm-2 mt-3" style="text-align: right;">Дата поручения:</div> 
											<div class="col-sm-3 mt-3">
												<input disabled type="date" value="<?=$keeping_file['user_date'];?>"></input>
											</div>
										</div>
										
										<div class="row">
											<div class="col-sm-2 mt-3" style="text-align: right;">Файл:</div> 
											<div class="col-sm-3 mt-3"><a href="/getFile?id=<?=$keeping_file['file_id'];?>">Скачать (<?=$keeping_file['file_size'] >> 10; echo ' кб'; ?>)</a></p>
											</div>
										</div>
								<?php endif;
							endforeach; ?>
						</div>							
						<?php endif; ?>
					

						
					<hr>
					
					<?php if (@$header['deals_main_keeping'][0]) : ?>
						<div class="container mt-5 mb-5"><b>Фотографии, загруженные ранее</b><br><br>
							<?php foreach ($header['deals_main_keeping'] as $keeping_photo) : 
								 if($keeping_photo['type'] === 'keeping_photo') : ?>				
									<div class="row">
										<div class="col-sm-2" style="text-align: right;">Фотография:</div> 
										<div class="col-sm-3"><a href="/getFile?id=<?php echo $keeping_photo['file_id']; ?>">Скачать (<?=$keeping_photo['file_size'] >> 10; echo ' кб'; ?>)</a></p>
										</div>
									</div>
								<?php endif;
							endforeach; ?>
						</div>							
					<?php endif; ?>
					
						<div class="container mt-5"><b>Приложите фотографии принятого на хранение имущества</b></div>

						<div class="row">
							<div class="form-group">
								<div class="col-sm-2 mt-5" style="text-align: right;">Фотографии принятого имущества:</div> 
								<div class="col-sm-3 mt-5"><input type="file" name="files_photo_keeping_upload[]" class="form-control" multiple></div>
							</div> 
						</div>
						
					<hr>
						<div class="container mt-5"><b>Объем, занимаемый имуществом на складе</b></div>
						
						<div class="row">
							<div class="form-group">
								<div class="col-sm-2 mt-5" style="text-align: right;">Объем:</div> 
								<div class="col-sm-3 mt-5"><input name="imusch_objem" type="number" min="0" step="0.001" value="<?php if(isset($header['deals_main_info']['imusch_objem'])) { echo $header['deals_main_info']['imusch_objem'];}?>"> м³</div>
							</div>
						</div>
					<hr>
						
					<?PHP //if(@$header['sklady'][0]) : ?>
						<div class="container mt-5"><b>Выберите склад, где хранится имущество</b></div>

						<div class="row">
							<div class="form-group">
								<div class="col-sm-2 mt-5" style="text-align: right;">Адрес:</div> 
								<div class="col-sm-3 mt-5"> 
										<select name="sklad_address"> 
										    <option disabled selected value> --- выберите склад --- </option>
											<?php foreach ($header['sklady'] as $sklad) : ?>
												<option <?php if($header['deals_main_info']['sklad_id'] === $sklad['id']) { echo 'selected'; }?> value="<?=$sklad['id']?>"><?=$sklad['address']?></option>
											<?php endforeach; ?>
										</select>
								</div>
							</div> 
						</div>
						
						<div class="row">							
							<div class="col-sm-2" style="text-align: right;"></div>
							<div class="col-sm-3"><button <?php if($header['deals_main_info']['sklad_id'] === null) { echo 'disabled'; }?> class="btn btn-info" onclick="downloadInvoice();">Заполнить складскую квитанцию</button><br><span style="font-size: 8pt; color: grey;">(должен быть выбран и сохранен адрес склада)</span> 
							<script>function downloadInvoice() { 
								event.preventDefault(); 
								window.location.href = '/getWarehouseInvoice?id=<?=$header['id']?>' }</script>
							</div>						
						</div>
						
						<div class="container mt-5"><b>Приложите заполненную и принятую Росимуществом складскую квитанцию</b></div>

						<div class="row">
							<div class="form-group">
								<div class="col-sm-2 mt-5" style="text-align: right;">Одобренная складская квитанция:</div> 
								<div class="col-sm-3 mt-5"><input type="file" name="files_warehouse_invoice_upload[]" class="form-control"></div>
							</div> 
						</div>
						
						<?php if (@$header['deals_main_keeping'][0]) : ?>
							<?php foreach ($header['deals_main_keeping'] as $signed_invoice) :
								 if($signed_invoice['type'] === 'warehouse_invoice_signed') : ?>
									<br>								 
									<div class="row">
										<div class="col-sm-2" style="text-align: right;">Ранее одобренная квитанция:</div> 
										<div class="col-sm-3"><a href="/getFile?id=<?php echo $signed_invoice['file_id']; ?>">Скачать (<?=$signed_invoice['file_size'] >> 10; echo ' кб'; ?>)</a></p>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-2" style="text-align: right;">Дата:</div> 
										<div class="col-sm-3"><?php $add_date = new DateTime($signed_invoice['date_really_added']); echo $add_date->Format('Y-m-d'); ?></div>
									</div>
								<?php endif;
							endforeach; ?>
						<?php endif; ?>
						
					<hr>
					<?PHP //endif; ?>

				</div>
					
				
				<div id="txt_4">
					<hr>
						Выбор эксперта из базы экспертов<br><br>
						Автозаполнение уведомления эксперта: данные + выбранный эксперт + фотографии которые человек загрузил на этапе хранение - кнопка генерации файла<br><br>
						Загрузка самого уведомления + дата<br><br>
						Экспертное заключение - приложить файл<br><br>
					<hr>
				</div>
			
				<div id="txt_5">
				</div>
				
				<div id="txt_6">
				</div>
				
				<div id="txt_7">
				</div>
				
				<div id="txt_8">
				</div>
				
				<div id="txt_9">Переработка
				</div>
				
				<div id="txt_10">
					<hr>
					
					<?php $header['history'] = array_reverse($header['history']); foreach ($header['history'] as $change) :?>
						<div class="container">
							<div class="row">
								<div class="col-sm mt-3">
									<p>Дата: <input type="textarea" disabled value="<?php echo $change['date']; ?>"></input> </p>
								</div>
								<div class="col-sm mt-3">
									<p>Пользователь: <input type="textarea" disabled value="<?php echo $change['user']; ?>"></input> </p>
								</div>
								<div class="col-sm mt-3">
									<p>Компания: <input type="textarea" disabled value="<?php echo $change['company']; ?>"></input> </p>
								</div>
							</div>
						  
							<div class="row mt-2">Внес изменения:</div>
							<div class="row mt-2">
								<textarea disabled style="width: 90%; height: 120px;"><?php print_r($change['change']);?></textarea>
							</div>
						</div>
						
						<hr>
					<?php endforeach; ?>

				</div>
				
				<div id="txt_11">Оценка
				</div>
				
			</div>
			
			<div class="mt-5 mb-5"><button class="btn btn-primary" type="submit">Сохранить</button></div>
		</form>
	<?php endif; ?>
</div>