<style type="text/css">.container { width: 100% !important; }</style>
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'import-form',
	'enableAjaxValidation'=>false,
		// 'htmlOptions' => array('enctype'=>'multipart/form-data'),
)); ?>
<? echo CHtml::hiddenField("Shops[id_mall]", $data['selected_mall']->id) ?>

<div class="helper_import">
	<strong>Внимание!</strong> После произведения импорта, магазины, которые отсутствуют в выгрузке, автоматически удаляться с ТРЦ и с планировок этажей этого ТРЦ. Информация по цветам:
	<ul>
		<li><div class="squrle exist"></div> - Значит что магазин существует в этом ТРЦ, просто будут изменены параметры, телефон, сайт, товарная группа и т.п.</li>
		<li><div class="squrle havnt"></div> - Значит что магазина нет в нашей базе данных, он будет добавлен автоматически</li>
		<li><div class="squrle havnt_but_in_db"></div> - Значит что магазина нет в ТРЦ, но он есть в нашей базе данных, данные подгрузятся из нее</li>
		<li><div class="squrle remove"></div> - Если строка имеет такой цвет, значит позиция будет удалена, если ячейка имеет такой цвет, это значит, что данные не определились и подставились по умолчанию</li>
	</ul>
	Чтобы изменения применились для <strong>ТРЦ <? echo $data['selected_mall']->title ?></strong>, нажмите внизу кнопку Применить импорт.
</div>
<br>
<table class="import" style="margin-left:50px;">
	<thead>
		<tr>
			<td>Название магазина</td>
			<td>Основная товарная группа</td>
			<td>E-Mail</td>
			<td>Контакты</td>
			<td>Сайт</td>
			<td>Скидка malloko</td>
			<td>Тип магазина</td>
		</tr>
	</thead>
	<tbody>
		<? foreach($data['prepare'] as $key => $prepare) { ?>
			<tr class="<? echo $prepare['exist_shop'] ?>">
				<td>
					<? echo CHtml::hiddenField("Shops[data][{$key}][id_shop]",$prepare['id_shop']) ?>
					<? echo CHtml::hiddenField("Shops[data][{$key}][type]",$prepare['exist_shop']) ?>
					<? echo CHtml::hiddenField("Shops[data][{$key}][Shops][title]",$prepare['shop_name']) ?>
					<? echo $prepare['shop_name'] ?>
				</td>
				<? if($prepare['exist_shop'] == 'remove'){ ?>
					<td><? echo $prepare['shop_group'] ?></td>
					<td><? echo $prepare['shop_email'] ?></td>
					<td><? echo $prepare['shop_phone'] ?></td>
					<td><? echo $prepare['shop_site'] ?></td>
					<td><? echo $prepare['shop_discount'] ?></td>
					<td></td>
				<? }else{ ?>

					
					<td<? echo (!$prepare['shop_group_allow']) ? " class='blocked'" : false ?>><? echo CHtml::dropDownList("Shops[data][{$key}][Shops][categories_id]",$prepare['shop_group'],$data['all_categories']) ?></td>
					<td><? echo CHtml::textField("Shops[data][{$key}][email]",$prepare['shop_email']) ?></td>
					<td<? echo (!$prepare['shop_phone_allow']) ? " class='blocked'" : false ?>><? echo CHtml::textField("Shops[data][{$key}][phone]",$prepare['shop_phone']) ?></td>
					<td><? echo CHtml::textField("Shops[data][{$key}][Shops][homepage]",$prepare['shop_site']) ?></td>
					<td><? echo CHtml::textField("Shops[data][{$key}][discount]",$prepare['shop_discount']) ?></td>
					<td><? echo CHtml::dropDownList("Shops[data][{$key}][Shops][id_type]",$prepare['id_type'],$data['all_types']) ?></td>
				<? } ?>
			</tr>
		<? } ?>
	</tbody>
</table>
<div class="form-actions">
		<?php echo TbHtml::submitButton('Применить импорт', array('color' => TbHtml::BUTTON_COLOR_PRIMARY)); ?>
        <?php echo TbHtml::linkButton('Отмена', array('url'=>'/admin/shops/importshops')); ?>
	</div>
<?php $this->endWidget(); ?>