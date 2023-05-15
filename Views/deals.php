<div class="container">
	<div class="row">
		<div class="col-12">
			<div class="mt-5">
			<h1>Список моих дел</h1>
				<hr>
				<p style="text-align: center; color: grey;"><br> ЗДЕСЬ БУДЕТ ПОЛЕ ПОИСКА - НО ПОКА ПРОСТО МАКЕТ <br> </p>
					<form method="POST"> 
						<h5>Номер ф-ки</h5>
						<input type="text" disabled name="username" value="" size="50">
					 
						<h5>Номер поручения</h5>
						<input type="text" disabled name="password" value="" size="50">
					 
						<div><br><button disabled type="button" class="btn btn-secondary">Искать</button></div>
					</form>
					<br> 
				<hr>
			</div>
			
			<?PHP if (session()->get('role_id')==='superadmin' ||
					 (session()->get('role_id')==='admin')) : ?>
			<div class="mt-5"><a href="/deals/new" class="btn btn-primary">ДОБАВИТЬ ДЕЛО</a>
			<?PHP endif; ?>
			
				<div class="mt-5">
					<table class="table table-bordered">
						<tbody>
							<tr>
								<th>Номер MLN</th>
								<th>Номер ф-ки</th>
								<th>Решение суда</th>
								<th>Последняя активность</th>
								<th>Имущество</th>
								<!--<th>Номер активного поручения</th>-->
								<th>Поверенный</th>
								<th>Контракт</th>
								<th>Статус</th>
							</tr>

							<?php if(is_array($deals)) :?>
							
							<tr>
								<?php foreach($deals as $deal) :?>
									<td><?=$deal['MLN']?></td>
									<td><a href="/deals/edit/<?php echo $deal['id']?>"><?=$deal['fka']?></a></td>
									<td><?=$deal['court_decision']?></td>
									<td><?=$deal['date']?></td>
									<td><?php $deal_ = json_decode($deal['stuff_name']); 
											$i = 0; foreach ($deal_ as $d) {
												if ($i>1) { echo '......'; continue; }
												echo $d.'<br>';
												$i++;
											}?>
											</td>
									<!--<td><=$deal['poruchenie']></td>-->
									<td><?php foreach($responsible_users['responsible_ids'] as $user){
											if ($user['id'] === $deal['respons_id']) { 
												echo $user['lastname']; } 
										}	?>
									</td>
									<td><?=$deal['contract']?></td>
									<td><?php 
										if($deal['status'] === 'keeping') { echo 'Хранение'; } 
										elseif ($deal['status'] === 'expert') { echo 'Экспертиза'; } 
										elseif ($deal['status'] === 'realize_less_10k') { echo 'Реализация до 10000 руб.'; } 
										elseif ($deal['status'] === 'realize_more_10k') { echo 'Реализация свыше 10000 руб.'; } 
										elseif ($deal['status'] === 'utilization') { echo 'Утилизация'; } 
										elseif ($deal['status'] === 'destroying') { echo 'Уничтожение'; } 
										else { echo 'Неизвестно'; }?></td>
									<tr>
								<?php endforeach?>
								
							</tbody>
							</table>
								
							<?php else : ?>
								
								</tbody></table>
								<div>Дела за вашей ответственностью не найдены</div>
								
							<?php endif; ?>
 				</div>
			</div>
		</div>
	</div>
</div>