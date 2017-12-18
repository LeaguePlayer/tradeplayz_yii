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
                // array('label'=>'Товары', 'url'=>"#",'items'=>[
                //         array('label'=>'Товарные категории', 'url'=>array('/admin/categories')),
                //          array('label'=>'ТРЦ', 'url'=>array('/admin/malls')),
                //         array('label'=>'Магазины', 'url'=>array('/admin/shops')),
                        
                //     ]),

                
                
               
                
                array('label'=>'Турниры', 'url'=>array('/admin/tournaments')),
                array('label'=>'FAQ', 'url'=>array('/admin/faq')),
                array('label'=>'Чат', 'url'=>array('/admin/chat')),

                
               // array('label'=>'Галереи', 'url'=>array('/admin/gallery/manage')),
            );
        ?>
         <?php
            $userlogin = Yii::app()->user->name ? Yii::app()->user->name : Yii::app()->user->email;

            $langs_avail = array();
            $select_lang = null;
            foreach($this->ALLOWED_COUNTRIES as $lang)
                {
                    if($lang == Yii::app()->language) $select_lang = Controller::languageTranslate($lang);
                    $langs_avail[] = array('label'=>Controller::languageTranslate($lang), 'url'=>array('/admin/config/changeLang', 'lang'=>$lang));
                }

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
                            array('label'=>'Выбран '.$select_lang, 'url'=>array('/admin/orders'), 'items'=>$langs_avail),
                            array('label'=>'Выйти ('.$userlogin.')', 'url'=>'/user/logout'),
                        ),
                    ),
                ),
            ));
        ?>

        <?php echo $content;?>

	</body>
</html>
