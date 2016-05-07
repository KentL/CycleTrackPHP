<?php

require_once( 'Util.php' );

$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

$server = $url["host"];
$username = $url["user"];
$password = $url["user"];
$db = substr($url["path"], 1);

abstract class DatabaseConnection extends mysqli
{
	
	public function __construct( $host, $user, $password, $database )
	{
		parent::__construct( $host, $user, $password, $database );

		if ( mysqli_connect_error() )
			throw new DatabaseConnectionException();
	}

	public function query( $query )
	{
		if ( !($result = parent::query( $query ) ) )
			Util::log( __METHOD__ . "() ERROR {$this->errno}: {$this->error}: \"{$query}\"" );
		
		return $result;
	}
}

class LocalDatabaseConnection extends DatabaseConnection 
{
	

	public function __construct()
	{
		$url=parse_url(getenv("CLEARDB_DATABASE_URL"));
		$host    = url["host"];
		$user     = url["user"];
		$password = url["pass"];
		$database  =  substr(url["path"], 1);

		parent::__construct( $host, $user, $password ,$database );
	}
}

class DatabaseConnectionFactory 
{
	static protected $connection = null;

	public static function getConnection()
	{
		if ( self::$connection )
			return self::$connection;
		else
			return self::$connection = new LocalDatabaseConnection();
	}
}

class DatabaseException extends Exception
{
	public function __construct( $message, $code )
	{
		parent::__construct( $message, $code );
	}
}

class DatabaseConnectionException extends DatabaseException
{
	public function __construct( $message=null, $code=null )
	{
		if ( !$message )
			mysqli_connect_error();

		if ( !$code )
			mysqli_connect_errno();

		parent::__construct( mysqli_connect_error(), mysqli_connect_errno() );
	}
}

