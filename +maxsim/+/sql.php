<?php

maxsim_event('sql_start');


/** To sanitize the user input for values to prevent SQL injection */
function e(string $user_input_value): string {
    return SQLite3::escapeString($user_input_value);
}


/** To sanitize the user input for identifiers (like table or column names) to prevent SQL injection  */
function e_identifier(string $user_input_identifier): string {
    return str_replace('"', '""', $user_input_identifier);
}



/** Execute an SQL query. $query must be sanitized to prevent SQL injection */
function sql(string $query, array $params=[]): mixed {
    global $sql_db;
    
    if (!isset($params['wait_result']) OR !is_bool($params['wait_result']))
        $params['wait_result'] = true;

    if (!isset($params['db']) AND isset($sql_db['db']))
        $params['db'] = $sql_db['db'];

    $params['query'] = $query;
    

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://maxsim/api/sql');   
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2); 
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);    
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['json' => json_encode($params)]));
    $output = curl_exec($ch);


    $result = json_decode($output, true);

    if (!is_array($result))
        return 'Invalid response from SQL API';
    
    if (!isset($result['result'])) {
        if (isset($result['error']) AND is_string($result['error']))
            return $result['error'];
        else
            return 'Unknown error from SQL API';
    }
    
    return $result['result'];
}


//** If the insert is successful, it returns the last insert row id */
function sql_insert(string $table, array $rows, array $params=[]): mixed {

	if (!is_array(reset($rows)))
		$rows = [$rows];

	if (count($rows) === 0)
		return false;

    $columns = [];
    $values  = [];
    $values_num = 0;
    foreach ($rows AS $row_id => $row) {
		$values_num++;
        $columns_values = [];

		foreach ($row AS $key => $value) {

			if ($values_num === 1)
				$columns[] = e_identifier($key);

			if ($value === null OR $value === false OR $value === true)
				$columns_values[] = 'NULL';
			else if (is_array($value))
				$columns_values[] = '\''.e(json_encode($value)).'\'';
			else
				$columns_values[] = '\''.e($value).'\'';
		}

        if (count($columns_values) > 0)
		    $values[] = '('.implode(',', $columns_values).')';

		if ($values_num >= 5000) {
			sql('INSERT INTO '.e_identifier($table).' ('.implode(',', $columns).') VALUES '.implode(',', $values), $params);
			$values 	= [];
			$values_num = 0;
        }
	}

    if (count($values) > 0)
        $res = sql('INSERT INTO '.e_identifier($table).' ('.implode(',', $columns).') VALUES '.implode(',', $values), $params);

        
	if (!isset($res) OR $res === false)
		return false;
	else
		return $res;
}


/** `$where` must be sanitized from SQL injection */
function sql_update(string $table, array $columns, string $where = '', bool $or_insert = false): mixed {

	if (!is_array($columns))
		return false;

	if ($or_insert === true) {
        $row_exists = sql('SELECT COUNT(*) AS _sql_update_num FROM '.e_identifier($table).' WHERE '.$where)[0]['_sql_update_num'] ?? 0;
		if ($row_exists > 0)
            return sql_update($table, $columns, $where);
		else
            return sql_insert($table, $columns);
            
	} else {
		$a = [];
		foreach ($columns AS $key => $value) {
			if ($value === '++')
				$a[] = e_identifier($key).' = '.e_identifier($key).' + 1';
			else if ($value === '--')
				$a[] = e_identifier($key).' = '.e_identifier($key).' - 1';
			else if ($value === null OR $value === true OR $value === false)
				$a[] = e_identifier($key).' = NULL';
			else if (is_array($value))
				$a[] = e_identifier($key).' = \''.e(json_encode($value)).'\'';
			else
				$a[] = e_identifier($key).' = \''.e($value).'\'';
		}
		return sql('UPDATE '.e_identifier($table).' SET '.implode(',', $a).' WHERE '.$where);
	}
}