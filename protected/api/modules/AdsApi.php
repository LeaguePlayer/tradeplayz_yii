<?php

class AdsApi extends Api
{
	
	protected $table = 'ads';
	
    public function rules()
    {
        return array(
            'check_key' => true,
            'allows' => array(
                array('get_list', '?'),
                array('get_notices', '?'),
                array('add_item', '?'),
                array('edit_item', '?'),
                array('del_item', '?'),
                array('get_filter', '?'),
            ),
            'verbs' => array(
                array('get_list', 'get'),
                array('get_notices', 'get'),
                array('add_item', 'get'),
                array('edit_item', 'get'),
                array('del_item', 'get'),
                array('get_filter', 'get'),
            ),
        );
    }
	

    function get_list( $params = array() ) {
	
		$limit = 0;
		$offset = 0;
		$item_id = 0;
		$option_id = 0;
		
		
		if ( isset( $params[ 'filter' ] ) )
			$filter = $params[ 'filter' ];
		else
			$filter = array();
			

		if ( isset( $params[ 'user_id' ] ) && (int)$params[ 'user_id' ] )
			$filter[ 'user_id' ] = $params[ 'user_id' ];
		
		if ( isset( $params[ 'type' ] ) && in_array( $params[ 'type' ], array( 'estate', 'client' ) ) )
			$filter[ 'type' ] = $params[ 'type' ];
			
			
		if ( isset( $filter[ 'item_id' ] ) && $item_id = (int)$filter[ 'item_id' ] )
			unset( $filter[ 'item_id' ] );
		
		if ( isset( $filter[ 'option_id' ] ) && $option_id = (int)$filter[ 'option_id' ] )
			unset( $filter[ 'option_id' ] );
			
		
		$where = filter( $filter );
		
		
		if ( $option_id )
			$where .= ( $where ? 'AND' : 'WHERE' ) . " ( " . (int)$option_id . " IN ( SELECT o.value_id FROM ad_options AS o WHERE o.item_id = a.id ) )";
		
		if ( $item_id )
			$where .= ( $where ? 'AND' : 'WHERE' ) . " `a`.`id` = " . (int)$item_id;
			
		
		if ( isset( $params[ 'limit' ] ) )
			$limit = (int)$params[ 'limit' ];
		
		if ( isset( $params[ 'offset' ] ) )
			$offset = (int)$params[ 'offset' ];
			
		
		$sql = "SELECT 
		
				`a`.*,
				
				`users`.`id` AS `user_id`,
				`users`.`phone` AS `phone`,
				`users`.`firstname` AS `user`,
				`users`.`email` AS `email`,
				`users`.`sms` AS `sms`,
				`users`.`agency_id` AS `agency`,
				
				`regions`.`parent_id` AS `region_pid`,
				
				`whos`.`value` AS `who`,
				`whats`.`value` AS `what`,
				`regions`.`value` AS `region`,
				`suites`.`value` AS `suite`,
				`furnitures`.`value` AS `furniture`,
				`communes`.`value` AS `commune`,
				`conditions`.`value` AS `condition`,
				`periods`.`value` AS `period`
			
			FROM `ads` as `a`
			
				LEFT JOIN `users` ON `a`.`user_id`=`users`.`id`
				LEFT JOIN `whos` ON `a`.`who_id`=`whos`.`id`
				LEFT JOIN `whats` ON `a`.`what_id`=`whats`.`id`
				LEFT JOIN `regions` ON `a`.`region_id`=`regions`.`id`
				LEFT JOIN `suites` ON `a`.`suite_id`=`suites`.`id`
				LEFT JOIN `furnitures` ON `a`.`furniture_id`=`furnitures`.`id`
				LEFT JOIN `communes` ON `a`.`commune_id`=`communes`.`id`
				LEFT JOIN `conditions` ON `a`.`condition_id`=`conditions`.`id`
				LEFT JOIN `periods` ON `a`.`period_id`=`periods`.`id`
			
			$where ORDER BY `a`.`modified` DESC;";
			
		$res = mysql_query( $sql );
		
		if (mysql_error())
			throw new ApiException( 'Ошибка работы с базой данных: ' . mysql_error(), 1 );
		
		$ret = array();
		
		if ( $res )
			while( $item = mysql_fetch_assoc( $res ) ) {
				$ret[ $item['id'] ] = $item;
			}
		
		$count = count( $ret );
		
		$ret = array_slice( $ret, $offset, ( $limit ? $limit : NULL ), true );
		
		foreach( $ret as $item ) {
			$ret[ $item['id'] ][ 'whos' ] = $this->get_value( $item['id'], 'whos' );
			$ret[ $item['id'] ][ 'whats' ] = $this->get_value( $item['id'], 'whats' );
			$ret[ $item['id'] ][ 'options' ] = $this->get_value( $item['id'], 'options' );
			$ret[ $item['id'] ][ 'photos' ] = $this->get_photos( $item['id'] );
			$ret[ $item['id'] ][ 'notices' ] = $this->check_notices( $item );
		}
		
		$ret[ 'count' ] = $count;
		
        return $ret;
    }
	
	
	function get_notices( $params ) {
		
		$vars = array(
			'type',
		);
		
		foreach( $vars as $var )
			if ( isset( $params[ $var ] ) )
				$$var = $params[ $var ];
			else
				throw new ApiException( 'Ошибка, не передана переменная: ' . $var, 1 );
		
		$user = $this->get_user_from_token( $params[ 'token' ] );
		
		$user_id = $user['id'];
		$agency_id = $user['agency_id'];
		$blacklist = $user['blacklist'];
		
		$sql="SELECT 
		
				n.id,
				a.id as item_id
			
			FROM ".$this->table." as a
			
			LEFT JOIN ".$this->table." as n
				ON a.id <> n.id
			
			LEFT JOIN users as u ON n.user_id=u.id
			LEFT JOIN regions as ra ON a.region_id=ra.id
			LEFT JOIN regions as rn ON n.region_id=rn.id
			
			WHERE a.user_id = '" . $user_id . "'
			
			AND a.type LIKE '" . $type . "'
			AND n.type NOT LIKE '" . $type . "'
			AND n.user_id <> '" . $user_id . "'
			AND n.user_id NOT IN ('" . implode( ',', $blacklist ) . "')
			
			AND a.expires > '" . date( 'Y-m-d H:i:s' ) . "'
			AND n.expires > '" . date( 'Y-m-d H:i:s' ) . "'
			AND a.finished = 0 AND n.finished = 0 AND ( n.`show` = 'anyone' 
			" . ( $agency_id ? "OR ( n.`show` = 'limited' AND u.agency_id = " . $agency_id . " )" : '' ) . " )
			
			AND  a.price " . ( $type == 'estate' ? '<=' : '>=' ) . " n.price
			AND a.price " . ( $type == 'estate' ? '>=' : '<=' ) . " ( n.price " . ( $type == 'estate' ? '-' : '+' ) . " 5000 )
			AND ( a.region_id = 0 OR n.region_id = 0 OR a.region_id = n.region_id
				OR ra.parent_id = n.region_id OR rn.parent_id = a.region_id )
			AND ( a.period_id = 0 OR n.period_id = 0 OR a.period_id = n.period_id )
			AND ( a.furniture_id = 0 OR n.furniture_id = 0 OR a.furniture_id = n.furniture_id )			
			
			" .( $type == 'client' ? '
				AND ( a.who_id = 0 OR a.who_id IN ( SELECT w1.value_id FROM ad_whos AS w1 WHERE w1.item_id = n.id ) )
				AND ( n.what_id = 0 OR n.what_id IN ( SELECT w2.value_id FROM ad_whats AS w2 WHERE w2.item_id = a.id ) )
			' :
			'
				AND ( a.what_id = 0 OR a.what_id IN ( SELECT w1.value_id FROM ad_whats AS w1 WHERE w1.item_id = n.id ) )
				AND ( n.who_id = 0 OR n.who_id IN ( SELECT w2.value_id FROM ad_whos AS w2 WHERE w2.item_id = a.id ) )
			'  ) .  "
			
			ORDER BY a.modified DESC;";
			
		$res = mysql_query($sql);
		
		$ret=array();
		
		if ($res)
			while($item=mysql_fetch_assoc($res)) {
				$ret[ $item['item_id'] ] [ $item[ 'id' ] ] = $this->get_item( $item[ 'id' ], 'value' );
			}
		
		return $ret;
		
	}
	
	function add_item( $params ) {	
		
		$vars = array(
			'item',
		);
		
		foreach( $vars as $var )
			if ( isset( $params[ $var ] ) )
				$$var = $params[ $var ];
			else
				throw new ApiException( 'Ошибка, не передана переменная: ' . $var, 1 );
				
		$user = $this->get_user_from_token( $params[ 'token' ] );
		
		$item['user_id'] = $user['id'];
		
		if( !isset( $item[ 'type' ] ) || !in_array( $item[ 'type' ], array( 'client', 'estate' ) ) )
			throw new ApiException( 'Переменная type должна быть равна либо estate, либо client', 1 );
		
		if ( !isset( $item[ 'who_id' ] ) )
			$item[ 'who_id' ] = 0;
		
		if ( !isset( $item[ 'region_id' ] ) )
			$item[ 'region_id' ] = 0;
		
		if ( !isset( $item[ 'period_id' ] ) )
			$item[ 'period_id' ] = 0;
			
		if ( !isset( $item[ 'furniture_id' ] ) )
			$item[ 'furniture_id' ] = 0;
		
		$item[ 'created' ] = date( 'Y-m-d H:i:s' );
		
		$item[ 'modified' ] = date( 'Y-m-d H:i:s' );
		
		if ( isset( $item[ 'expires' ] ) )
			$item[ 'expires' ] = date( 'Y-m-d H:i:s', time() + (int)$item['expires'] * 3600 );
	
		$sql = "INSERT INTO `ads` (`" . implode( '`, `', array_keys( $item) ) . "`) VALUES ('" . implode ( "', '", array_map( 'mysql_real_escape_string', $item) ) . "');";
		
		mysql_query($sql);
		
		if (mysql_error())
			throw new ApiException( 'Ошибка работы с базой данных: ' . mysql_error(), 1 );
		
		$new_id = mysql_insert_id();
		
		$values = array(
			'whos',
			'whats',
			'options',
		);
		
		foreach( $values as $value ) {
			
			$error = '';
			
			if ( isset( $item[ $value ] ) && !empty( $item[ $value ] ) )
				$error = $this->add_values( $new_id, $item[ $value ], $value );
			
			if ( $error )
				throw new ApiException( 'Ошибка работы с базой данных: ' . $error, 1 );
				
			unset( $item[ $value ] );
			
		}
		
		$photos = array();
			
		if ( isset( $_FILES[ 'photos' ] ) )
				foreach( $_FILES[ 'photos' ][ 'name' ]  as $id => $name)
					if ( $name && $file = $this->upload_photo( $_FILES[ 'photos' ], $id ) )
						$photos[] = $file;
		
		foreach( $photos as $file)
			$this->insert_photo( $new_id, $file );
		
		return array( 'insert_id' => $new_id );
    }
	
	private function upload_photo($photo, $n) {
		
		if($photo['name'][$n]) {
		
			if($photo['type'][$n] == "image/jpeg") {
			
				if($photo['size'][$n] != 0 && $photo['size'][$n]<=10485760) {
				
					if(is_uploaded_file($photo['tmp_name'][$n])) {				
						
						$dir = "files/photos";
						
						if ( !file_exists( $dir )) {
							mkdir( $dir, 0750, true );
						}
					
						$name=md5(sprintf('%x%x%x%x%x',rand(0,9),rand(0,9),rand(0,9),rand(0,9),time())).time();
						if (!copy($photo["tmp_name"][$n], $dir.'/'.$name.'.jpg')) 
							throw new Exception('Произошла ошибка при сохранении файла на сервере');					
						return $name;
						
					} else {
						throw new Exception('Произошла ошибка при загрузке файла на сервер');
					}
				} else {
					throw new Exception('Размер файла не должен превышать 10Мб. Размер вашего файла: '.$photo['size'][$n].' байт.');
				}
			} else {
				throw new Exception('Принимаются только файлы типа *.jpg');
			}		
		}
	
		return NULL;
	}
	
	
	function edit_item( $params ) {	
		
		$vars = array(
			'id',
			'item',
		);
		
		foreach( $vars as $var )
			if ( isset( $params[ $var ] ) )
				$$var = $params[ $var ];
			else
				throw new ApiException( 'Ошибка, не передана переменная: ' . $var, 1 );
		
		foreach ( $item as $field => $description ) {
			$sql="UPDATE ".$this->table." SET ".mysql_escape_string($field)."='".mysql_escape_string($description)."' WHERE (id=".(int)$id.");";		
			
			mysql_query($sql);
			
			if (mysql_error())
				throw new ApiException( 'Ошибка работы с базой данных: ' . mysql_error(), 1 );
		}
		
		return array( 'affected_rows' => mysql_affected_rows() );
    }
	
	
	function del_item( $params ) {
		
		$vars = array(
			'id',
		);
		
		foreach( $vars as $var )
			if ( isset( $params[ $var ] ) )
				$$var = $params[ $var ];
			else
				throw new ApiException( 'Ошибка, не передана переменная: ' . $var, 1 );
		
        $sql="DELETE FROM ".$this->table." WHERE id=".(int)$id.";";
		
        mysql_query($sql);
		
		$ret = mysql_affected_rows();
		
		if (mysql_error())
			throw new ApiException( 'Ошибка работы с базой данных: ' . mysql_error(), 1 );
		
		$this->delete_photos( $id );
		
		$this->delete_value( $id, 'whos' );
		$this->delete_value( $id, 'whats' );
		$this->delete_value( $id, 'options' );
		$this->delete_value( $id, 'photos' );
		
		return array( 'affected_rows' => $ret );
		
    }
	
	function get_filter( $params = array() ) {
	
		$allowed = array(
			'whos',
			'whats',
			'regions',
			'furnitures',
			'periods',
			'options',
		);
		
		if( isset( $params['type'] ) && in_array( $params['type'], $allowed ) )
			$filter = $params['type'];
		else
			throw new ApiException( 'Ошибка указания фильтра', 3 );
	
		$sql = "SELECT * FROM `$filter`;";
		$res = mysql_query( $sql );

		$ret = array();
  
		if ( $res )
			while( $item = mysql_fetch_assoc( $res ) )
				$ret[ $item[ 'id' ] ] = $item['value'];
		
		if (mysql_error())
			throw new ApiException( 'Ошибка работы с базой данных: ' . mysql_error(), 3 );
			
		return $ret;
	}
	
	
	public function get_item( $id, $get = 'description' ) {
		
		$sql="SELECT 
		
				a.*,
				
				users.id AS user_id,
				users.phone AS phone,
				users.firstname AS user,
				users.email AS email,
				users.agency_id AS agency,
				
				regions.parent_id AS region_pid,
				
				whos.".$get." AS who,
				whats.".$get." AS what,
				regions.value AS region,
				suites.".$get." AS suite,
				furnitures.".$get." AS furniture,
				communes.".$get." AS commune,
				conditions.".$get." AS `condition`,
				periods.".$get." AS period
			
				
			FROM ".$this->table." AS a
			
				LEFT JOIN users ON a.user_id=users.id 
				LEFT JOIN whos ON a.who_id=whos.id 
				LEFT JOIN whats ON a.what_id=whats.id 
				LEFT JOIN regions ON a.region_id=regions.id 
				LEFT JOIN suites ON a.suite_id=suites.id 
				LEFT JOIN furnitures ON a.furniture_id=furnitures.id 
				LEFT JOIN communes ON a.commune_id=communes.id 
				LEFT JOIN conditions ON a.condition_id=conditions.id 
				LEFT JOIN periods ON a.period_id=periods.id 
			 
			WHERE a.id = ".(int)$id.";";
			
		$res = mysql_query($sql);
		
		$item = false;
		
		if( $item = mysql_fetch_assoc( $res ) ) {
		
			$item[ 'whos' ] = $this->get_value( $item['id'], 'whos' );
			$item[ 'whats' ] = $this->get_value( $item['id'], 'whats' );
			$item[ 'options' ] = $this->get_value( $item['id'], 'options' );
			$item[ 'photos' ] = $this->get_photos( $item['id'] );
			
		}
		
		return $item;
	}
	
	function get_photos( $item_id ) {
		
		$ret = array();
		
		$sql = "SELECT * FROM ad_photos WHERE item_id=" . (int)$item_id . " ORDER BY time ASC;";
		
		$res = mysql_query($sql);
		
		if (mysql_error())
			throw new ApiException( 'Ошибка работы с базой данных: ' . mysql_error(), 1 );
		
		$dir = "files/photos";
		
		if ( $res )
			while( $item = mysql_fetch_assoc( $res ) )
				$ret[] = "http://" . $_SERVER[ 'SERVER_NAME' ] . '/' . $dir . '/' . $item[ 'file' ] . '.jpg';
		
		return $ret;
	}
	
	public function delete_photos( $item_id ) {
		
		$sql = "SELECT * FROM ad_photos WHERE item_id=" . (int)$item_id . " ORDER BY time ASC;";
		
		$res = mysql_query($sql);
		
		if ( $res ) {
			while( $item = mysql_fetch_assoc( $res ) ) {
				
				$dir = "files/photos/" . date( 'y_m', $item[ 'time' ] );
				
				unlink( $dir . '/' . $item[ 'file' ] . '.jpg' );
			}
		}
		
		return mysql_affected_rows();
	}
	
	function add_values( $item_id, $array, $table ) {
		
		$error = '';
		
		foreach( $array as $value_id ) {
			
			$sql = "INSERT INTO ad_" . $table . " ( item_id, value_id ) VALUES ( '" . (int)$item_id . "', '" . (int)$value_id . "' );";
			
			mysql_query($sql);
			
			if (mysql_error()) $error .= mysql_error();
		}
		
		return $error;
    }
	
	private function get_value( $item_id, $table ) {
		
		$sql="SELECT t.id,value FROM ".$table." AS t LEFT JOIN ad_".$table." ON t.id = value_id WHERE item_id = " . $item_id . ";";
		
		$ret=array();
		
		$res=mysql_query($sql);
		
		if (mysql_error())
			throw new ApiException( 'Ошибка работы с базой данных: ' . mysql_error(), 1 );
		
		if ($res)
			while($item=mysql_fetch_assoc($res)) {
				$ret[ $item['id'] ]=$item['value'];
			}
		
		return $ret;
		
	}
	
	private function delete_value( $item_id, $table ) {
		
		$sql="DELETE FROM ad_".$table." WHERE item_id = " . $item_id . ";";
		
		$res=mysql_query($sql);
		
		if (mysql_error())
			throw new ApiException( 'Ошибка работы с базой данных: ' . mysql_error(), 1 );
		
		return mysql_affected_rows();
		
	}
	
	function check_notices( $ad, $blacklist = array(), $agency_id = 0 ) {
		
		$sql="SELECT 
		
				a.*,
				u.phone AS phone,
				u.email AS email,
				u.sms AS sms,
				u.firstname AS user,
				u.agency_id AS agency,
				
				whos.value AS who,
				whats.value AS what,
				regions.value AS region,
				suites.value AS suite,
				furnitures.value AS furniture,
				communes.value AS commune,
				conditions.value AS `condition`,
				periods.value AS period
			
			FROM `ads` as a
			
			LEFT JOIN users as u ON a.user_id=u.id
			LEFT JOIN whos ON a.who_id=whos.id 
			LEFT JOIN whats ON a.what_id=whats.id 
			LEFT JOIN regions ON a.region_id=regions.id 
			LEFT JOIN suites ON a.suite_id=suites.id 
			LEFT JOIN furnitures ON a.furniture_id=furnitures.id 
			LEFT JOIN communes ON a.commune_id=communes.id 
			LEFT JOIN conditions ON a.condition_id=conditions.id 
			LEFT JOIN periods ON a.period_id=periods.id 
			 
			WHERE a.user_id <> '" . (int)$ad[ 'user_id' ] . "'
			
			AND a.type NOT LIKE '" . $ad[ 'type' ] . "'
			AND a.user_id NOT IN ('" . implode( ',', $blacklist ) . "')
			
			AND a.finished = 0 
			AND a.expires > '" . date( 'Y-m-d H:i:s' ) . "'
			AND ( a.`show` = 'anyone' " . ( $agency_id ? "OR ( a.`show` = 'limited' AND u.agency_id = " . (int)$agency_id . " )" : '' ) . " )
			
			AND a.price " . ( $ad[ 'type' ] == 'estate' ? '>=' : '<=' ) . " '" . (int)$ad[ 'price' ] . "'
			AND a.price " . ( $ad[ 'type' ] == 'estate' ? '<=' : '>=' ) . " ( '" . (int)$ad[ 'price' ] . "' " . ( $ad[ 'type' ] == 'estate' ? '+' : '-' ) . " 5000 )
			
			" . ( $ad[ 'type' ] == 'client' && (int)$ad[ 'who_id' ] ? "
				AND ( '" . (int)$ad[ 'who_id' ] . "' IN ( SELECT w.value_id FROM ad_whos AS w WHERE w.item_id = a.id ) )
			" : "" ) . "
			
			" . ( $ad[ 'type' ] == 'estate' && (int)$ad[ 'what_id' ] ? "
				AND ( '" . (int)$ad[ 'what_id' ] . "' IN ( SELECT w.value_id FROM ad_whats AS w WHERE w.item_id = a.id ) )
			" : "" ) . "
			
			" . ( (int)$ad[ 'region_id' ] ? "AND ( a.region_id = 0 OR a.region_id = '" . (int)$ad[ 'region_id' ] . "' 
				OR regions.parent_id = '" . (int)$ad[ 'region_id' ] . "' OR a.region_id = '" . (int)$ad[ 'region_pid' ] . "'  ) " : '' ) . "
			" . ( (int)$ad[ 'period_id' ] ? "AND ( a.period_id = 0 OR a.period_id = '" . (int)$ad[ 'period_id' ] . "' ) " : '' ) . "
			" . ( (int)$ad[ 'furniture_id' ] ? "AND ( a.furniture_id = 0 OR a.furniture_id = '" . (int)$ad[ 'furniture_id' ] . "' ) " : '' ) . "
			
			ORDER BY a.modified DESC;";
			
		$res = mysql_query($sql);
		
		if (mysql_error())
			throw new ApiException( 'Ошибка работы с базой данных: ' . mysql_error(), 3 );
		
		$ret=array();
		
		if ($res)
			while($item=mysql_fetch_assoc($res)) {
				$ret[ $item['id'] ] = $item;
				
				$ret[ $item['id'] ][ 'whos' ] = $this->get_value( $item['id'], 'whos' );
				$ret[ $item['id'] ][ 'whats' ] = $this->get_value( $item['id'], 'whats' );
				$ret[ $item['id'] ][ 'options' ] = $this->get_value( $item['id'], 'options' );
				$ret[ $item['id'] ][ 'photos' ] = $this->get_photos( $item['id'] );
			}
		
		return $ret;
		
	}
	
	private function get_user_from_token( $token ) {
		
		$sql="SELECT `u`.* FROM `tokens` AS `t` LEFT JOIN `users` AS `u` ON `t`.`user_id` = `u`.`id` WHERE `t`.`token` = '" . mysql_escape_string( $token ) . "';";
		
		$res=mysql_query($sql);
		
		if (mysql_error())
			throw new ApiException( 'Ошибка работы с базой данных: ' . mysql_error(), 1 );
		
		$ret = array();
		
		if ( $item = mysql_fetch_assoc( $res ) ) {
			
			$item[ 'blacklist' ] = array();
			
			$sql = "SELECT `blacklists`.*, `users`.`phone`, `users`.`firstname` AS `name` FROM `blacklists` LEFT JOIN  `users` ON  `banned_id` =  `users`.`id` WHERE `blacklists`.`banned_id`=`users`.`id` AND `user_id`=".(int)$item['id'].";";
		
			if ( $res = mysql_query( $sql ) )
				while ( $line = mysql_fetch_array( $res ) )
					$item[ 'blacklist' ] [] = $line[ 'banned_id' ];
					
			return $item;
		} 
		
		return array();
	}
	
}