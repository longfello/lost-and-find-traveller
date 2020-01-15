<?php

$language = Yii::app()->language;
	$status[0]="Новая";
	$status[1]="В работе";		
	$status[2]="Закрытая";	

?>
<tr class="<?=$idx%2==0?"even":"odd"?>">

	<td ><?php if(Yii::app()->user->checkAccess('PoputchikOrder.moderator')): ?>	<span class="control-icons"><a class="edit" href="/<?=$language?>/LostService/update/<?=$request->id_ls?>"></a><a  class="sms" href="#"></a>	</span>	<?php endif; ?></td> 
	<td><span class="id_ls"><?=$request->id_ls ?></span></td>
	<td><span class="status"><?=$status[$request->status] ?></span></td>
	<td><span class="confirm_code"><?=$request->confirm_code ?></span><div class="success">Код назначен и sms отправлена!</div><div class="error">Ошибка отправки sms!</div></td>
	<td><span class="id_thing"><?=$request->id_thing ?></span></td>
	<td><span class="name"><?=$request->name ?></span></td>
	<td><span class="contact_phone"><?=$request->contact_phone ?></span></td>
	<td><span class="absent_name"><?=$request->user->profile->second_name." ".$request->user->profile->first_name  ?></span></td>
	<td><span class="absent_contact_phone"><?=$request->user->username  ?></span></td>
	<td><span class="comment"><?=$request->comment  ?></span></td>
	
</tr>

