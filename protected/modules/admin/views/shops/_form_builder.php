<?php
/* @var $this ImageController */
/* @var $model Image */
/* @var $form CActiveForm */
?>
<?php
	//Fonts
	$fonts = array(
		'Arial'=>'Arial',
		'Verdana'=>'Verdana',
		'Georgia'=>'Georgia',
		'Myriad Pro'=>'Myriad Pro',
		'Courier'=>'Courier',
		'Plaster'=>'Plaster'
	);
	//Align text
	$align = array(
		'left'=>'Left',
		'center'=>'Center',
		'right'=>'Right',
		'justify'=>'Justify'
	);
	//Font-sizes
	$font_sizes = array();
	for ($i=5; $i < 16; $i++) { 
		$font_sizes[$i * 2] = ($i * 2).'px';
	}

	echo CHtml::hiddenField('Shops_page',$Shops_page);
?>
<div class="form">
<div>
	<a class="builder fancybox" href="#builder">Создать новый пакет</a>
</div>

<div>
	<? 
		if(!empty($model->path_package))
			{
				
				if($_GET['changecategory'] == 'yes') $message = "<p><b>Внимание!</b> Вы изменили товарную категорию у магазина, нужно пересоздать пакет!</p>";
				else $message = "<p><b>Внимание!</b> Если вы создадите новый пакет - старый будет удален!</p>";

				echo $message;
				echo TbHtml::image($model->getPackageUrl());
				
				echo "<br>";
			}
		else
			{
				echo "<br>";
				echo "<p><b>Внимание!</b> У вас еще нет загруженного пакета - создайте первый макет магазина прямо сейчас!</p>";
			}
	 ?>
</div>

<div id="builder">
	<div id="canvas-container" style="min-height: 100px;">
		<canvas id="canvas" width="586" height="634"></canvas>
	</div>
	<div id="settings">
		<form name="settings" method="GET" action="">
		
		<?if($templates){?>
		<div class="block">
			<div>
				<?php
					$list =  CHtml::listData($templates, 'id', 'name');
					$delete = CHtml::listData($templates, 'id', 'name');
					$list[0] = 'Нет';
					ksort($list, SORT_NUMERIC);
				?>
				
				<?php echo CHtml::label('Загрузить из шаблона', 'template');?>
				<?php echo CHtml::dropDownList('template-check', 0, $list);?>
				<div style="margin: 5px 0;">
					<?php echo CHtml::dropDownList('template-del', 0, $delete);?>
					<a href="#delete-templte" id="del-template">Удалить шаблон</a>
				</div>
			</div>
		</div>
		<?}?>
		<div class="block hide">
			<div class="row">
				<?php echo CHtml::label('Ширина', 'c_width');?>
				<?php echo CHtml::textField('c_width', 586);?>
			</div>
			<div class="row">
				<?php echo CHtml::label('Высота', 'c_height');?>
				<?php echo CHtml::textField('c_height', 634);?>
			</div>
			<div class="clear"></div>
			<div>
				<span style="font-size:11px;">* при размерах 586 по ширине и 634 по высоте - изображение для устройств до 4S отображается на полный экран.</span>
			</div>
			<div class="clear"></div>			
		</div>
		<div class="block">
			
			<div class="row">
				<?php echo CHtml::label('Цвет текста', 'color');?>
				<div id="color-selector"><div style="background-color: rgb(255, 255, 255);"></div></div>
			</div>
			<div class="row">
				<?php echo CHtml::label('Шрифт', 'font');?>
				<?php echo CHtml::dropDownList('font','', $fonts);?>
			</div>
			<div class="row">
				<?php echo CHtml::label('Выравнивание', 'align');?>
				<?php echo CHtml::dropDownList('align','', $align);?>
			</div>
			<div class="clear"></div>
			<?php echo CHtml::textarea('text', $model->title);?>
			<div class="row">
				<?php echo CHtml::label('Размер шрифта', 'font-size');?>
				<input id="text-font-size" type="range" min="1" step="1" max="130" value="50" />
			</div>
			
			<div class="row">
				<?php echo TbHtml::button('Добавить текст', array('id' => 'add-text', 'color' => TbHtml::BUTTON_COLOR_PRIMARY));?>
				<?php echo TbHtml::button('Подстроить', array('id' => 'refresh-text'));?>
			</div>
			<div class="clear"></div>
		</div>
		<div class="block">
			<?$this->widget('ext.EAjaxUpload.EAjaxUpload',
			array(
		        'id'=>'uploadFile',
		        'config'=>array(
		               'action'=>Yii::app()->createUrl('/admin/shops/getImage'),
		               'allowedExtensions'=>array("jpg","jpeg","gif","png"),//array("jpg","jpeg","gif","exe","mov" and etc...
		               'sizeLimit'=>4*1024*1024,// maximum file size in bytes
		               'onComplete'=>"js:function(id, fileName, responseJSON){
		               		var c = jQuery('#canvas').data('canvas');
		               		fabric.Image.fromURL('/uploads/tmp/' + fileName, function(img) {
								img.set('left', 100).set('top', 100);
								c.add(img);
								jQuery('#set-size').click();
							});
		               		console.log(c,fileName); 
		               	}"
		              )
			)); ?>
		</div>
		<div class="row">
				<?php echo TbHtml::button('Сделать изображение полностью белым', array('id' => 'img-brightness'));?>
				
			</div>
		<div class="block hide">
			<div>
				<?php echo CHtml::checkbox('template', false, array('id' => 'template'));?>
				<?php echo CHtml::label('Сохранить как шаблон', 'template');?>
				<div class="template_name" style="display: none;">
					<?php echo CHtml::textField('template_name', 'Название');?>
				</div>
			</div>
		</div>
		<div class="block">
			<div class="row">
				<?php echo TbHtml::button('Удалить выбранный элемент', array('id' => 'delete', 'color' => TbHtml::BUTTON_COLOR_DANGER));?>
			</div>
			<div class="row">
				<?php echo TbHtml::button('Очистить все', array('id' => 'clear-canvas', 'color' => TbHtml::BUTTON_COLOR_DANGER));?>
			</div>
			<div class="clear"></div>
		</div>
		<div class="save-block">
			<div class="row">
				
				<?php echo TbHtml::button('Сохранить', array('color' => TbHtml::BUTTON_COLOR_SUCCESS, 'id' => 'save-builder', 'size' => TbHtml::BUTTON_SIZE_LARGE));?>
			</div>
			<div class="clear"></div>
		</div>
		
		</form>
	</div>
</div>



</div><!-- form -->

<script type="text/javascript">
	
	$( "#canvas-container" ).resizable({
		handles: "s",
		resize: function(event, ui){
			var c = $(this).find('canvas').data('canvas');
			c.setHeight($(this).height());
			$('#c_height').val($(this).height());
			c.renderAll();
		}
	});

	jQuery('.fancybox').fancybox({
		width: 1200,
		type: 'inline',
		afterShow: function(){
			jQuery('#c_width, #c_height').keyup();
			var fi = jQuery('.fancybox-inner');
			var s = fi.find('#settings');
			//hot fix -- !
			if($(window).height() >= 680){
				fi.on('scroll', function(){
					s.animate({top: fi.scrollTop()}, {duration: 500, queue:false});
				});
			}
			$(window).resize(function(){
				console.log
				if($(window).height() >= 680){
					fi.on('scroll', function(){
						s.animate({top: fi.scrollTop()}, {duration: 500, queue:false});
					});
				}else{
					fi.off('scroll');
				}
			});
			
			
		}
	});

	



	jQuery('#template').on('click', function(){
		if($(this).is(':checked'))
			$(this).parent().find('.template_name').fadeIn();
		else
			$(this).parent().find('.template_name').fadeOut();
	});

	jQuery('#del-template').on('click', function(){
		var template_id = $('#template-del').find('option:selected').val();
		var name = $('#template-del').find('option:selected').text();
		if(confirm("Вы действительно хотите удалить шаблон - "+name+"?")){
			$.post('<?=Yii::app()->createUrl('image/deleteTemplate')?>',{id: template_id}, function(data){
				if(data){
					if(data == 'no'){
						$('#template-del').parents('.block').hide();
						return false;
					}
					var select = "";
					jQuery.each(data, function(index, value){
						select += '<option value="'+index+'">'+value+'</option>';
					});
					console.log(select);
					$('#template-del').html(select);
					select = '<option value="0">Нет</option>' + select;
					$('#template-check').html(select);
					//var objects = jQuery.parseJSON('{"name":"John"}');
				}
			});
		}
	});

	jQuery('#template-check').change(function(){
		var template_id = $(this).find('option:selected').val();
		var c = $('#canvas').data('canvas');
		//console.log(template_id);
		if(template_id === 0){
			c.clear();
			c.renderAll();
			return false;
		}
		if(confirm("Все данные на холсте будут потеряны. Продолжить?")){
			$.post('<?=Yii::app()->createUrl('image/getTemplate')?>',{id: template_id}, function(data){
				if(data){
					c.clear();
					c.loadFromDatalessJSON(data);
					c.renderAll();
				}

			});
		}
	});

	jQuery('#save-builder').on('click', function(){
		//Save as template
		var c = $('#canvas').data('canvas');
		var parent = $(this).closest('#builder');
		

		c.deactivateAll().renderAll();
		var image = c.toDataURL();
		

		c.setBackgroundImage('<? echo $model->category->imgBehaviorPreview->getImageUrl(); ?>', function(){
			c.renderAll.bind(c);
			var image2 = c.toDataURL();
			

			var id_shop = "<?php echo $model->id; ?>";
			var shops_page = $('#Shops_page').val();
			
			$.post('<?=Yii::app()->createUrl('/admin/shops/builder')?>',{Image:{id_shop: id_shop, path_package: image2, path_package_discount: image}}, function(data){
				

				if(data == 'ok'){
					

					

					document.location = (!shops_page) ? "/admin/shops/" : "/admin/shops/list/Shops_page/"+shops_page;
				}
				else if(data == 'new')
				{
					document.location = (!shops_page) ? "/admin/shops/bindfoursquare/id_shop/"+id_shop : "/admin/shops/bindfoursquare/id_shop/"+id_shop+"/Shops_page/"+shops_page;
				}
				//document.location.reload(true);
			});
		});
		
		
	});


$(document).ready(function(){

	
		var c = $('#canvas').data('canvas');
		//c.setBackgroundImage('/uploads/templates/123.png');
		c.setBackgroundImage('<? echo $model->category->imgBehaviorDiscountPreview->getImageUrl(); ?>');
		c.renderAll();
		

	});
</script>