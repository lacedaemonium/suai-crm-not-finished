<?php if ((session()->get('role_id') !== 'admin') && (session()->get('role_id') !== 'superadmin')) :?>
	<div class="container mt-3">	
		<div class="col-12 mt-5">
			<div class="alert alert-danger">
				<div>Доступ к запрашиваемой странице запрещен</div>
			</div>
		</div>
	</div>
	
<?php elseif (@$validation) :?>
<div class="container">
	<div class="col-12 mt-5">
		<div class="alert alert-danger">
			<?php echo $validation->listErrors(); ?>
		</div>
	</div>
</div>

<?php elseif ($showForm) :?>
<div class="container mt-3">
	<h1>Добавление нового дела</h1> 
	<br>
	<div>Чтобы добавить новое дело: занесите главную информацию о деле на вкладку "Общая информация", прикрепите докуметы о передаче на повереннного и укажите статус на вкладке "Поручение", затем нажмите нажмите "Сохранить"</div>
	
	<hr>
	<form name="new_deal" method="post" accept-charset="utf-8" enctype="multipart/form-data">
	
	<div class="tabs mt-5">
				<input type="radio" name="inset" value="" id="tab_1" checked>
				<label for="tab_1" id="label-tab-1">Общая<br>информация<br><br></label>

				<input type="radio" name="inset" value="" id="tab_2">
				<label for="tab_2" id="label-tab-2">Поручение<br><br><br></label>
				
				<input type="radio" disabled name="inset" value="" id="tab_3">
				<label for="tab_3" class="lable-disabled" id="label-tab-3">Хранение<br> <br> <br></label>
				
				<input type="radio" name="inset" disabled value="" id="tab_4">
				<label class="lable-disabled" for="tab_4">Экспертиза<br> <br> <br></label>
				
				<input type="radio" name="inset" disabled value="" id="tab_7">
				<label class="lable-disabled"  for="tab_7">Утилизация<br> <br> <br></label>
							
				<input type="radio" name="inset" disabled value="" id="tab_8">
				<label class="lable-disabled" for="tab_8">Уничтожение<br> <br> <br></label>
				
				<input type="radio" name="inset" disabled value="" id="tab_9">
				<label class="lable-disabled" for="tab_9">Переработка<br> <br> <br></label>
				
				<input type="radio" name="inset" disabled value="" id="tab_11"> 
				<label class="lable-disabled" for="tab_11">Оценка<br><br> <br> </label>
				
				<input type="radio" disabled name="inset" value="" id="tab_5">
				<label for="tab_5" class="lable-disabled" id="label-tab-5">Реализация<br>до 10.000 руб.<br> <br> </label>
				
				<input type="radio" disabled name="inset" value="" id="tab_6">
				<label for="tab_6" class="lable-disabled" id="label-tab-6">Реализация<br>свыше 10.000 руб.<br> <br> </label>
				
				<div id="txt_1">
				<hr>
					<div class="container col-12">
						<div class="row">
							<div class="col-sm-2 mt-5" style="text-align: right;">Номер MLN:</div> 
							<div class="col-sm-3 mt-5"><input required name="MLN" type="textarea" value=""></div>
							<div class="col-sm-2 mt-5" style="text-align: right;">Решение суда:</div> 
							<div class="col-sm-3 mt-5"><input required name="court_decision" type="textarea" value=""></div>
						</div>
						
						<div class="row">
							<div class="col-sm-2 mt-5" style="text-align: right;">Ф-ка:</div>
							<div class="col-sm-3 mt-5"><input required name="fka" type="textarea" value=""></input></div>
							<div class="col-sm-2 mt-5" style="text-align: right;">Уполномоченный орган:</div> 
							<div class="col-sm-3 mt-5"><input required name="upal_namochenny" type="textarea"></div>
						</div>
						
						<div class="row">
							<div class="col-sm-2 mt-5" style="text-align: right;">Контракт:</div>
							<div class="col-sm-3 mt-5"><input name="contract" type="textarea" value=""></input><br><span style="font-size: 8pt">(необязательно)</span></div>
						</div>
					</div>
						
						<hr>
						
					<div class="container col-12">
						<div class="row">
						<script>
						let imusch_number = '1';
						function delete_im_line(imusch_number) {
							event.preventDefault();
							$('#imuschestvo_'+imusch_number).last().remove()
						}
						function add_imuschesto_line() {
							event.preventDefault(); 
							$('#imuschestvo_row').append('<div id=imuschestvo_'+imusch_number+'><div class="col-sm-4" style="text-align: right;"><div class="mt-3"><input required size="45" name="stuff-name[]" value="" type="textarea"></div></div><div class="col-sm-2" style="text-align: right;"><div class="mt-3"><input required size="9" name="stuff-quant[]" value="" min="1" type="number"></div></div><div class="col-sm-3" style="text-align: right;"><div class="mt-3"><input size="9" required name="stuff-ed-izm[]" value="шт." type="textarea"></div></div><div class="col-sm-2"><a class="btn btn-success mt-2" onclick="add_imuschesto_line();" href="#">+</a><a class="btn btn-danger mt-2" style="margin-left: 5px" onclick="delete_im_line('+imusch_number+');" href="#">-</a></div>');
							imusch_number++;
						}
						</script>
						
							<div class="col-sm-3 mt-5" style="text-align: right;">перечень имущества:</div>
							<div class="col-sm-3 mt-5" style="text-align: right;">количество:</div>
							<div class="col-sm-3 mt-5" style="text-align: right;">единицы измерения:</div>

						</div>
						
						<div class="row" id="imuschestvo_row"><div>
							<div class="col-sm-4" style="text-align: right;">
								<div class="mt-3"><input size="45" required name="stuff-name[]" value="" type="textarea"></div>
							</div>
							
							<div class="col-sm-2" style="text-align: right;">
								<div class="mt-3"><input size="9" required name="stuff-quant[]" value="" min="1" type="number"></div>
							</div>
							
							<div class="col-sm-3" style="text-align: right;">
								<div class="mt-3"><input size="9" required name="stuff-ed-izm[]" value="шт." type="textarea"></div>
							</div>
							
							<div class="col-sm-2">
								<div class="mt-2">
									<a class="btn btn-success" onclick="add_imuschesto_line();" href="#">+</a>
								</div>
							</div>

						</div></div>
					</div>
				<hr>
				</div>
				
				<div id="txt_2">
					<hr>
					<script>
						function addOneMoreActField() {
							event.preventDefault();
							$('#act_pp_uploading').append('<div class="row"><div class="col-sm-2 mt-5" style="text-align: right;">Дата поручения:</div><div class="col-sm-3 mt-5"><input required type="date" name="new_act-pp-date[]" value=""></input></div></div><div class="row"><div class="col-sm-2 mt-5" style="text-align: right;">Номер поручения:</div><div class="col-sm-3 mt-5"><input required name="new_act-pp-number[]" value=""></input></div></div><div class="row"><br><div class="form-group"><div class="col-sm-2 mt-5" style="text-align: right;"></div><div class="col-sm-3 mt-5"><input type="file" name="act_pp_files[]" class="form-control" id="file"></div></div></div>');
						}
						</script>					
					<div id="act_pp_uploading">
						<div class="row">
								<div class="col-sm-2 mt-5" style="text-align: right;">Дата поручения:</div> 
								<div class="col-sm-3 mt-5"><input required type="date" name="new_act-pp-date[]" value=""></input></div>
						</div>
						
						<div class="row">
								<div class="col-sm-2 mt-5" style="text-align: right;">Номер поручения:</div> 
								<div class="col-sm-3 mt-5"><input required name="new_act-pp-number[]" value=""></input></div>
						</div>
					
						<div class="row"><br>
							<div class="form-group">
								<div class="col-sm-2 mt-5" style="text-align: right;"></div> 
								<div class="col-sm-3 mt-5"><input required type="file" name="act_pp_files[]" class="form-control" id="file"></div>
							</div> 
						</div>
						
					</div>						
						
					<div class="row"><br>
						<div class="col-sm-2 mt-5" style="text-align: right;"></div> 
						<div class="col-sm-3 mt-5"><a class="btn btn-warning" onclick="addOneMoreActField();">Добавить еще одно поручение</a></div> 
					</div>
					
					<hr>
					
					<div class="row mb-5">
						<div class="col-sm-4 mt-3" style="text-align: right;">Назначить поверенным:</div>
						<div class="col-sm-4 mt-3">
							<select name="responsible">
								<?php foreach ($data['responsible_ids'] as $org) : ?>
								<option value="<?=$org['id'];?>">
									<?=$org['lastname']." (" .$org['firstname'].")";?>
								</option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>

				<hr>

					<div class="row">
						<div class="col-sm-4 mt-3" style="text-align: right;">Статус поручения:</div>
						<div class="col-sm-4 mt-3">
							<select name="status">
								<option id="status-select-1" value="keeping">Только хранение</option>
								<option id="status-select-2" value="expert">Экспертиза</option>
								<option id="status-select-5" value="utilization">Утилизация</option>
								<option id="status-select-6" value="destroying">Уничтожение</option>
								<option id="status-select-7" value="pererabotka">Переработка</option>
								<option id="status-select-8" value="otsenka">Оценка</option>
								<option id="status-select-3" value="realize_less_10k">Реализация до 10.000 руб.</option>
								<option id="status-select-4" value="realize_more_10k">Реализация свыше 10.000 руб.</option>
							</select>
						</div>
					</div>

					
				</div>
				
				<div id="txt_3">
				<select>
				<?php //foreach ($header['response_org'] as $org) :?>
					<?php// print_r($org); ?>
				<?php //endforeach; ?>
				</select>
				</div>
				
				<div id="txt_4">
				</div>
				
				<div id="txt_5">
				</div>
				
				<div id="txt_6">
				</div>
				
				<div id="txt_7">
				</div>
				
				<div id="txt_8">
				</div>
	</div>
			
			<div class="mt-5">
				<div class="form-group">
					<button type="submit" id="send_form" class="btn btn-primary">Сохранить</button>
				</div>
			</div>
		</div>
	</form>
</div>

<?php elseif ($message) :?>
<div class="container">
	<div class="col-12 mt-5">
		<div class="alert alert-success">
			<?php echo $message; ?>
		</div>
	</div>
</div>

<?php endif; ?>