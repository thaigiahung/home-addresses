<?php
	class HomeAddressesHelper
	{
		public static function ExecuteQuery($sql)
		{
			$mysqli = new mysqli("localhost", "root", "", "home-addresses");
			$mysqli->set_charset("utf8");
			$result = $mysqli->query($sql);			
			$mysqli->close();
			
			return $result;
		}
	}
?>