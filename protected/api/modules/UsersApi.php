<?php

class UsersApi extends Api
{
    public function rules()
    {
        return array(
            'check_key' => true,
            'allows' => array(
				array('login', '?'),
				array('logout', '?'),
				array('get_user', '?'),
				array('check_token', '?'),
            ),
            'verbs' => array(
                array('login', 'get'),
                array('logout', 'get'),
                array('get_user', 'get'),
                array('check_token', 'get'),
            ),
        );
    }

    function login( $params = array() ) {
		
        /** @var $params['password'] - hash пароля */

		if ( is_array( $params ) && isset( $params[ 'login' ] ) && isset( $params[ 'password' ] ) )
			$user_id = $this->check_user( $params[ 'login' ], $params[ 'password' ] );
		else
			throw new ApiException( 'Неверный логин и пароль', 1 );
			
		if ( $user_id )
			$token = $this->create_token( $user_id );
		else
            throw new ApiException( 'Авторизация не удалась', 1 );
			
        return array( 'token' => $token );
    }

    function logout( $params = array() ) {
		
		if ( is_array( $params ) && isset( $params[ 'token' ] ) )
			$ret = $this->delete_token( $params[ 'token' ] );
		else
			throw new ApiException( 'Не передан токен', 1 );
			
        return array( 'result' => $ret );
		
    }
	
	function get_user( $params = array() ) {
		
		if ( is_array( $params ) && isset( $params[ 'token' ] ) )
			$token = $params[ 'token' ];
		
		$sql="SELECT `u`.* FROM `tokens` AS `t` LEFT JOIN `users` AS `u` ON `t`.`user_id` = `u`.`id` WHERE `t`.`token` = '" . mysql_escape_string( $token ) . "';";
		
		$res=mysql_query($sql);
		
		if (mysql_error())
			throw new ApiException( 'Ошибка работы с базой данных: ' . mysql_error(), 1 );
			
			
		
		if ( $item = mysql_fetch_assoc( $res ) ) {
			
			
			$item[ 'photo' ] = 'http://' . $_SERVER[ 'SERVER_NAME' ] . ( $item[ 'photo' ] ? '/files/images/avatars/' . $item[ 'photo' ] . '_s.jpg' : '/img/avam.png' );
			
			
			$sql = "SELECT `name` FROM `groups` WHERE `id` = " . (int)$item[ 'group_id' ] . ";";
			
			$res = mysql_query( $sql );
			
			$item[ 'group' ] = '-';
			
			if ( $res )
				list( $item[ 'group' ] ) = mysql_fetch_array( $res );
				
			
			
			$sql = "SELECT * FROM `tariffs` WHERE `id` = " . (int)$item[ 'tariff_id' ] . ";";
			
			$res = mysql_query( $sql );
			
			$item[ 'tariff' ] = array();
			
			if ( $res )
				$item[ 'tariff' ] = mysql_fetch_assoc( $res );
			
			
			
			$sql = "SELECT `name` FROM `agencies` WHERE `id` = " . (int)$item[ 'agency_id' ] . ";";
			
			$res = mysql_query( $sql );
			
			$item[ 'agency' ] = '-';
			
			if ( $res )
				list( $item[ 'agency' ] ) = mysql_fetch_array( $res );
				
				
			
			$item[ 'blacklist' ] = array();
			
			$sql = "SELECT `blacklists`.*, `users`.`phone`, `users`.`firstname` AS `name` FROM `blacklists` LEFT JOIN  `users` ON  `banned_id` =  `users`.`id` WHERE `blacklists`.`banned_id`=`users`.`id` AND `user_id`=".(int)$item['id'].";";
		
			if ( $res = mysql_query( $sql ) )
				while ( $line = mysql_fetch_array( $res ) )
					$item[ 'blacklist' ] [] = $line[ 'banned_id' ];
			
			
			
			$item[ 'tariff_left' ] = round( ( strtotime( $item[ 'date_paid' ] )  - time() ) / 86400 );
			$item[ 'tariff_need' ] = ( $item[ 'tariff' ]['price'] > $item[ 'balance' ] ? $item[ 'tariff' ]['price'] - $item[ 'balance' ] : 0 );
			
			
			$numbers = array();
		
			foreach ( explode( "\n", trim( $item[ 'numbers' ] ) ) as $number )
				$numbers[] = str_replace( '+7', '', $number );
			
			$item[ 'numbers' ] = $numbers;
			
			
			
			$sql = "SELECT COUNT( * ) AS `count` FROM `ads` WHERE `user_id` = '".(int)$item['id']."';";
		
			$res = mysql_query($sql);
			
			$line = mysql_fetch_assoc( $res );
			
			$item[ 'ad_count' ] = $line[ 'count' ];
			
			
			
			$item[ 'referal_link' ] = "http://" . $_SERVER[ 'SERVER_NAME' ] . "/add_user.htm?referal=user" . $item['id'] . "_" . md5( $item[ 'login' ] . 'refer' );
			
			
			
			return $item;
		} 
		
		return array();
	}
	
	private function check_user( $login, $md5password ) {
	
		$sql = "SELECT * FROM `users` 
			WHERE MD5( `login` ) = '" . mysql_escape_string( $login ) . "' 
			AND `md5password` = '" . mysql_escape_string( $md5password ) . "';";
		
		$res = mysql_query($sql);
		
		if (mysql_error())
			throw new ApiException( 'Ошибка работы с базой данных: ' . mysql_error(), 2 );
			
		if( $res ) {
		
			$item = mysql_fetch_assoc( $res );
			
			return $item[ 'id' ];
			
		}
		
		return NULL;
	}
	
	private function create_token( $user_id ) {
		
		$token = md5( time() . (int)$user_id );
		
		$sql = "INSERT INTO `tokens` 
				( `user_id`, `token`, `created` ) 
			VALUES
				( '" . (int)$user_id . "', '" . $token . "', '" . date( 'Y-m-d H:i:S' ) . "' );";
		
		mysql_query($sql);
		
		if (mysql_error())
			throw new ApiException( 'Ошибка работы с базой данных: ' . mysql_error(), 1 );
		
		return $token;
	}
	
	private function delete_token( $token ) {
	
		$sql = "DELETE FROM `tokens` WHERE `token` = '" . mysql_escape_string( $token ) . "';";
		
		$res = mysql_query($sql);
		
		if (mysql_error())
			throw new ApiException( 'Ошибка работы с базой данных: ' . mysql_error(), 3 );
		
		return mysql_affected_rows();
	}
	
	function check_token( $params ) {
		
		if( !isset( $params[ 'token' ] ) )
			throw new ApiException( 'Не передана переменная token', 3 );
		
		$sql = "SELECT * FROM `tokens` WHERE ( `token` ) = '" . mysql_escape_string( $params[ 'token' ] ) . "';";
		
		$res = mysql_query($sql);
		
		if (mysql_error())
			throw new ApiException( 'Ошибка работы с базой данных: ' . mysql_error(), 3 );
		
		if( $res )
			$ret = mysql_fetch_assoc($res);
		
		if( isset( $ret['user_id' ] ) && $ret['user_id' ] )
			return array( 
				'user_id' => $ret['user_id' ], 
				'validate' => true,
			);
		else
			return array(
				'user_id' => 0,
				'validate' => false,
			);

	}
	
	static function get_user_from_token( $token ) {
		
		$sql = "SELECT * FROM `tokens` WHERE ( `token` ) = '" . mysql_escape_string( $token ) . "';";
		
		$res = mysql_query($sql);
		
		if (mysql_error())
			throw new ApiException( 'Ошибка работы с базой данных: ' . mysql_error(), 3 );
		
		if( $res )
			$ret = mysql_fetch_assoc($res);
		
		if( isset( $ret['user_id' ] ) && $ret['user_id' ] )
			return $ret['user_id' ];
		else
			return false;

	}
	
	// function check_token( $params = array() ) 
	// {
	
		// $sql = "SELECT * FROM `tokens` WHERE ( `token` ) = '" . mysql_escape_string( $_GET['token'] ) . "';";
		
		// $res = mysql_query($sql);
		// $ret = mysql_fetch_assoc($res);
		// $ret2 = $ret['user_id'];
		
		// if (mysql_error())
		// {
			// throw new ApiException( 'Ошибка работы с базой данных: ' . mysql_error(), 3 );
			// return array( 'validate ' => 'false' );
		// }
		
		// $response['validate'] = 'true'; 
		// $response['user_id'] = $ret2;
		
		// if ($ret['user_id'] != NULL)
		// {
		// return array( 'validate' => 'true', $ret['user_id']);
		// }
		// else
		// {
		// return array( 'validate' => 'false' );
		// }

	// }
	
}