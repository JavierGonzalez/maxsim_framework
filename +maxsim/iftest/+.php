<?php # maxsim.tech — MIT License — Copyright (c) 2005 Javier González González <gonzo@virtualpol.com>


function iftest_phpt(string $file, bool $print = false): bool {
    global $iftest_phpt_crono;

    $test_operators = ['==', '===', '!=', '!==', '>', '>=', '<', '<=', '<>', '<=>'];

    $file_code = file_get_contents($file);
    if (!$file_code)
        return false;
    
    if (!isset($iftest_phpt_crono))
        $iftest_phpt_crono = 0;
    
    $verdict_final = true;
    foreach (explode("\n", $file_code) AS $test_line) {
        $test_line = trim($test_line);


        if (strlen($test_line) === 0)
            continue;

        if (substr($test_line,0,2) === '<?')
            continue;

        if (substr($test_line,0,2) === '# ') {
            if ($print === true)
                echo iftest_tr(substr($test_line,2));
            continue;
        }

        if (substr($test_line,0,1) === '#')
            continue;
            
        if (substr($test_line,0,2) === '//')
            continue;
        

        // Select first operator
        $test_rank = [];
        foreach ($test_operators AS $op) {
            $elm = explode(' '.$op.' ', $test_line, 2);
            if (isset($elm[1]))
                $test_rank[$op] = strlen($elm[0]);
        }
        asort($test_rank);
        $operator = array_key_first($test_rank);


        // Line parts
        $test_expected = null;
        if ($operator) {
            $test_code = false;
            $elm = explode($operator, $test_line, 2);
            if (isset($elm[1])) {
                $test_expected  = trim($elm[0]);
                $test_code      = trim(explode(' //', $elm[1])[0]);
            }
            if ($test_code === false)
                continue;
        } else {
            $test_code = $test_line;
        }
        

        // #pass_fail (Inverted test verdict)
        if (strpos($test_code, '#pass_fail') !== false) {
            $test_code = trim(str_replace('#pass_fail', '', $test_code));
            $pass_fail = true;
        } else {
            $pass_fail = false;
        }


        // #limit_ms=1 (Is FAIL if execution time is more than 1 milisecond)
        if (strpos($test_code, '#limit_ms') !== false) {
            $ms = explode('#limit_ms=', $test_code)[1];
            $limit_ms = trim(explode(' ', trim($ms))[0]);
            $test_code = trim(str_replace('#limit_ms='.$limit_ms, '', $test_code));
        } else {
            $limit_ms = false;
        }


        ///////////////////////////////////////
        list($test_result, $test_crono, $verdict) = @eval('
            
            $iftest_crono_start = hrtime(true); 
            $iftest_result = '.$test_code.';
            $iftest_crono_final = hrtime(true);

            $iftest_crono = ($iftest_crono_final - $iftest_crono_start) / 1e9;
            return [$iftest_result, $iftest_crono, ('.($operator?$test_expected.' '.$operator.' ':'').'$iftest_result)?true:false];');
        ///////////////////////////////////////


        $iftest_phpt_crono += $iftest_crono;

        if ($print === true) {
            echo iftest_print_html($test_expected, $test_line, $limit_ms, $pass_fail, $verdict, $test_result, $test_crono);
            ob_flush();
        }

        if ($verdict === false AND $pass_fail === false) {
            if ($print === false)
                return false;

            $verdict_final = false;
        }
    }

    return $verdict_final;
}


function iftest_print_html(mixed $expected, mixed $code, int | bool $limit_ms = false, bool $pass_fail = false, 
        bool $verdict = false, mixed $result = false, float | bool $crono = false): string {

    global $unit_test;


    $limit_ms_fail = false;
	if (is_numeric($limit_ms) AND $limit_ms >= 1 AND ($crono * 1000) > $limit_ms) {
		$verdict = false;
        $limit_ms_fail = true;
    }


	if ($pass_fail === true) {
		if ($verdict === true)
			$verdict = false;
		else if ($verdict === false)
			$verdict = true;
	}

	if ($verdict === true)
		$unit_test['tests_pass'] = ($unit_test['tests_pass'] ?? 0) + 1;


	$unit_test['last_test_result'] = $result;

    if ($verdict === true AND $pass_fail === true)
        $verdict_text = '<b style="color:blue;">PASS FAIL</b>';
    else if ($verdict === true)
        $verdict_text = '<b style="color:blue;">PASS</b>';
    else
        $verdict_text = '<b style="color:red;">FAIL</b>'; 


    $print = 'table';
    $output = '';
	if ($print == 'text')
        $output .= num($crono * 1000, 2).' ms '.$verdict_text.' <em><b>'.print_r($result, false).'</b></em> = '.$code.'<br />';


	if ($print == 'table') {
		$unit_test['tests_total'] = ($unit_test['tests_total'] ?? 0) + 1;
        $output .= '<tr style="font-family:monospace;">
				<td align="right"><b>'.$unit_test['tests_total'].'.</b></td>
				<td nowrap align="right"'.(is_numeric($limit_ms) ? ' title="Limit: '.$limit_ms.' ms"' : '').' 
                    style="'.(is_numeric($limit_ms) ? 'font-weight:bold;'.($limit_ms_fail === true? 'color:red;' : 'color:blue;') : 'color:#555;').'">
                    '.num($crono * 1000, 2).' ms</td>
				<td nowrap>'.$verdict_text.'</td>';

        $output .= '<td align="right" nowrap class="iftest-result">'.iftest_print_var($result).'</td>';

        $output .= '<td width="100%" class="iftest-code">'.str_replace('\\n', '<br />', str_replace('">&lt;?php', '">', highlight_string('<?php '.$code, true))).'</td></tr>'."\n";
    }

	$unit_test['tests_crono'] = ($unit_test['tests_crono'] ?? 0) + $crono;

	return $output;
}


function iftest_print_var(mixed $var): string {

	if ($var === true)
		return '<b>true</b>';

	if ($var === false)
		return '<b>false</b>';

	if ($var === null)
		return '<b>null</b>';

	if (is_string($var) AND preg_match("/^(<|>|>=|<=)[0-9\.]{1,}$/", $var))
		return '<b>'.$var.'</b>';

	if (is_int($var) or is_float($var))
		return '<b>'.$var.'</b>';

	if (is_array($var) or is_object($var))
		return '<xmp class="iftest-array">'.print_r($var, true).'</xmp>';

	if (substr($var, 0, 1) == '/' AND substr($var, -1, 1) == '/')
		return '<span style="color:#999;">'.$var.'</span>';

	if (is_string($var))
		return '<b>\'</b><xmp style="display:inline;">'.$var.'</xmp><b>\'</b>';

	return $var;
}


function iftest_tr(string $text = '', int $h = 4): string {
	return '<tr><td colspan="5"><br /><span class="iftest-tr">'.$text.'</span></td></tr>';
}


function iftest_url(string $url, string $return = 'html'): mixed {

    if (strpos($url, '://') === false) {
        if (substr($url, 0, 1) !== '/')
            return null;

        $url = 'http://localhost'.$url;
    }

    $options = [
        'http' => [
            'timeout' => 1,
        ],
        'ssl' => [
            'verify_peer'      => false,
            'verify_peer_name' => false,
        ],
    ];
    $html = file_get_contents($url, false, stream_context_create($options));
    
    if ($return === 'status')
        return (int) trim(explode(' ', $http_response_header[0])[1]);
    else
        return $html;
}