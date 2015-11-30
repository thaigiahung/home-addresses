<?php
	class HomeAddressesHelper
	{
		public static function ExecuteQuery($sql)
		{
			$mysqli = new mysqli("localhost", "root", "", "home-addresses");			
			$result = $mysqli->query($sql);			
			$mysqli->close();
			
			return $result;
		}
	}
?>