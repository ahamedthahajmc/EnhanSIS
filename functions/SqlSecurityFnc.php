<?php



function sqlSecurityFilter($variableName = '', $dbAvailable = 'yes')
{
	if ($dbAvailable == 'yes')
		global $connection;
	
	$variable = $variableName;
	$variableType = gettype($variable);

	$injectionParams = array('union ', 'select ', 'concat',  'concat_ws', 'create ', 'update ', 'insert ', 'delete ', 'extract ', 'drop ', 'truncate ', 'where ', 'trim ', 'format ', 'union%20', 'select%20', 'create%20', 'update%20', 'insert%20', 'delete%20', 'extract%20', 'drop%20', 'truncate%20', 'where%20', 'trim%20', 'format%20', ';', '\'', '--', '../', '..%2f', 'skip-grant-tables', 'sleep(', 'sleep (');

	switch ($variableType) {
		case 'string':

			if ($variable != '') {
				$checker = 0;

				$check_1 = strip_tags($variable);
				$check_2 = addslashes($check_1);
				if ($dbAvailable == 'yes')
					$check_3 = mysqli_real_escape_string($connection, $check_2);
				else
					$check_3 = $check_2;

				foreach ($injectionParams as $one_check) {
					if (strpos(strtolower($check_3), $one_check) !== false)
						$checker++;
				}

				if ($checker == 0)
					return htmlentities(htmlspecialchars($check_3));
				else
					return '';
			}
			else {
				return '';
			}

			break;

		case 'array':

			if (!empty($variable)) {
				$checker = 0;
				$filter_data = array();

				foreach ($variable as $onekey => $oneval) {
					$checker_k = 0;
					$checker_v = 0;

					$k_check_1 = strip_tags($onekey);
					$k_check_2 = addslashes($k_check_1);
					if ($dbAvailable == 'yes')
						$k_check_3 = mysqli_real_escape_string($connection, $k_check_2);
					else
						$k_check_3 = $k_check_2;

					if (is_string($oneval)) {
						$v_check_1 = strip_tags($oneval);
						$v_check_2 = addslashes($v_check_1);
						if ($dbAvailable == 'yes')
							$v_check_3 = mysqli_real_escape_string($connection, $v_check_2);
						else
							$v_check_3 = $v_check_2;
					}

					foreach ($injectionParams as $one_check) {
						if (strpos(strtolower($k_check_3), $one_check) !== false)
							$checker_k++;
						
						if (is_string($oneval)) {
							if (strpos(strtolower($v_check_3), $one_check) !== false)
								$checker_v++;
						}
					}

					if (is_array($oneval) || is_object($oneval)) {
						$get_child_ret = sqlSecurityFilter($oneval); // being recursive

						$filter_data[$k_check_3] = $get_child_ret;
					}
					else {
						if($checker_k != 0 || $checker_v != 0) {
							unset($variable[$onekey]);
						}
						else {
							unset($variable[$onekey]);

							$filter_data[$k_check_3] = htmlentities(htmlspecialchars($v_check_3));
						}
					}
				}

				return $filter_data;

				unset($checker);
				unset($checker_k);
				unset($checker_v);
			}
			else {
				return array();
			}

			break;
		
		case 'object':

			if (!empty((array)$variable)) {
				$checker = 0;
				$filter_data = array();

				foreach ($variable as $onekey => $oneval) {
					$checker_k = 0;
					$checker_v = 0;

					$k_check_1 = strip_tags($onekey);
					$k_check_2 = addslashes($k_check_1);
					if ($dbAvailable == 'yes')
						$k_check_3 = mysqli_real_escape_string($connection, $k_check_2);
					else
						$k_check_3 = $k_check_2;

					if (is_string($oneval)) {
						$v_check_1 = strip_tags($oneval);
						$v_check_2 = addslashes($v_check_1);
						if ($dbAvailable == 'yes')
							$v_check_3 = mysqli_real_escape_string($connection, $v_check_2);
						else
							$v_check_3 = $v_check_2;
					}

					foreach ($injectionParams as $one_check) {
						if (strpos(strtolower($k_check_3), $one_check) !== false)
							$checker_k++;
						
						if (is_string($oneval)) {
							if (strpos(strtolower($v_check_3), $one_check) !== false)
								$checker_v++;
						}
					}

					if (is_array($oneval) || is_object($oneval)) {
						$get_child_ret = sqlSecurityFilter($oneval); // being recursive

						$filter_data[$k_check_3] = $get_child_ret;
					}
					else {
						if($checker_k != 0 || $checker_v != 0) {
							unset($variable->$onekey);
						}
						else {
							unset($variable->$onekey);

							$filter_data[$k_check_3] = htmlentities(htmlspecialchars($v_check_3));
						}
					}
				}

				return $filter_data;

				unset($checker);
				unset($checker_k);
				unset($checker_v);
			}
			else {
				return array();
			}

			break;
	}
}
