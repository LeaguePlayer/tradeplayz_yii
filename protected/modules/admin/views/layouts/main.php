<!DOCTYPE html>
<html lang="en">
	<head>
	  <meta charset="utf-8">
	  <title><?php echo CHtml::encode(Yii::app()->config->get('app.name')).' | Admin';?></title>
	  <meta name="viewport" content="width=device-width, initial-scale=1.0">
	</head>
	<body>

        <?php
            $menuItems = array(
               // array('label'=>'Разделы сайта', 'url'=>array('/admin/structure')),
              //  array('label'=>'Меню сайта', 'url'=>array('/admin/menu')),
                array('label'=>'Товары', 'url'=>"#",'items'=>[
                        array('label'=>'Товарные категории', 'url'=>array('/admin/categories')),
                         array('label'=>'ТРЦ', 'url'=>array('/admin/malls')),
                        array('label'=>'Магазины', 'url'=>array('/admin/shops')),
                        
                    ]),

                
                
               
                
                array('label'=>'Акции/События', 'url'=>array('/admin/eventsstock')),
                array('label'=>'Луки', 'url'=>array('/admin/looks')),
                array('label'=>($this->actualUnholdersCard > 0) ? "Карты Malloko ({$this->actualUnholdersCard})" : "Карты Malloko", 'url'=>array('/admin/usersholderscard')),

                 array('label'=>'Настройки', 'url'=>"#",'items'=>[
                       array('label'=>'PUSH-уведомления', 'url'=>array('/admin/notification')),
                       array('label'=>'Текстовый экраны', 'url'=>array('/admin/apppages')),
                       array('label'=>'Слайдер на главной', 'url'=>array('/admin/slider')),
                       array('label'=>'Хэштег дня', 'url'=>array('/admin/hashtagday')),
                       array('label'=>'Настройки приложения', 'url'=>array('/admin/config')),
                    ]),
                
               // array('label'=>'Галереи', 'url'=>array('/admin/gallery/manage')),
            );
        ?>
        <?php
            $userlogin = Yii::app()->user->name ? Yii::app()->user->name : Yii::app()->user->email;
            $this->widget('bootstrap.widgets.TbNavbar', array(
                'color'=>'inverse', // null or 'inverse'
                'brandLabel'=> CHtml::encode(Yii::app()->name),
                'brandUrl'=>'/',
                'collapse'=>true, // requires bootstrap-responsive.css
                'items'=>array(
                    array(
                        'class'=>'bootstrap.widgets.TbNav',
                        'items'=>$menuItems,
                    ),
                    array(
                        'class'=>'bootstrap.widgets.TbNav',
                        'htmlOptions'=>array('class'=>'pull-right'),
                        'items'=>array(
                            array('label'=>'Выйти ('.$userlogin.')', 'url'=>'/user/logout'),
                        ),
                    ),
                ),
            ));
        ?>

        <?php echo $content;?>

	</body>
</html>
