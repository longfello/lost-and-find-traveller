<?php $data = $model->search()->getData(); ?>
<table class="items">
<thead><tr>
	<th id="routes-grid_c0">Начальный пункт</th>
	<th id="routes-grid_c1">Конечный пункт</th>
	<th class="button-column" id="routes-grid_c2"> </th>
</tr></thead>
<?php foreach($data as $item):
$s1 = $item->startSettlement->name.'<br /><span class="desc">';
$s1 .= $item->startSettlement->kodTSt->socrname.", ";
if($item->startSettlement->idArea) $s1 .= $item->startSettlement->idArea->name . ' ' . mb_strtolower($item->startSettlement->idArea->kodTSt->socrname,'UTF-8') .', ';
$s1 .= $item->startSettlement->idRegion->name . ' ' . mb_strtolower($item->startSettlement->idRegion->kodTSt->socrname,'UTF-8');

$s2 = $item->endSettlement->name.'<br /><span class="desc">';
$s2 .= $item->endSettlement->kodTSt->socrname.", ";
if($item->endSettlement->idArea) $s2 .= $item->endSettlement->idArea->name . ' ' . mb_strtolower($item->endSettlement->idArea->kodTSt->socrname,'UTF-8') .', ';
$s2 .= $item->endSettlement->idRegion->name . ' ' . mb_strtolower($item->endSettlement->idRegion->kodTSt->socrname,'UTF-8');
?>
<tr class="odd">
	<td><?=$s1?></td>
	<td><?=$s2?></td>
	<td class="button-column">
		<a class="view" title="Просмотреть" href="/ru/routes/<?=$item->id_route?>"><img src="/assets/a9249018/gridview/view.png" alt="Просмотреть"/></a>
		<a class="update" title="Редактировать" href="/ru/routes/update/<?=$item->id_route?>"><img src="/assets/a9249018/gridview/update.png" alt="Редактировать"/></a>
		<form action="/ru/routes/delete/<?=$item->id_route?>" method="post"><a class="delete" title="Удалить" onclick="if(!confirm('Вы уверены, что хотите удалить данный элемент?')) return false; else { $(this).parent().submit();  return false; }" href="/ru/routes/delete/<?=$item->id_route?>"><img src="/assets/a9249018/gridview/delete.png" alt="Удалить"/></a></form>
	</td>
</tr>
<tr class="even"><td colspan="3">
Через
<?php for($i=0; $i<count($item->routePaths)-1; $i++) {
	if($i>0) print ' - ';
	if($item->routePaths[$i]->direction) print $item->routePaths[$i]->idPath->startSettlement->name;
	else print $item->routePaths[$i]->idPath->endSettlement->name;
}
?>
</td></tr>
<?php endforeach; ?>
</table>