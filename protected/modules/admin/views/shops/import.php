<?php
$this->menu=array(
    array('label'=>'Вернуться','url'=>array('list')),
    array('label'=>'Скачать пример xlsx файла','url'=>'/examples/example.xlsx'),
);
?>

<? 
if(empty($data['prepare']))
	$this->renderPartial('/shops/_import_begin', array('data'=>$data));
else
	$this->renderPartial('/shops/_import_prepare', array('data'=>$data));
?>


