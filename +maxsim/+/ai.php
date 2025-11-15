<?php


/** Make raw AI request */
function maxsim_ai_request(array $input): array {

    /* Example:
    $input = [
        'prompt_system' => '',    
        'prompt_user'   => '',
    ];
    */

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://maxsim/maxsim/ai/request');   
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2); 
    curl_setopt($ch, CURLOPT_TIMEOUT, 500);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($input));
    
    $output = curl_exec($ch);
    
    return json_decode($output, true);
}
/* Example output:
Array
(
    [model] => gpt-oss
    [id_model] => 40
    [inference_provider] => Cerebras
    [seconds] => 0.596
    [tps] => 86
    [reasoning_effort] => medium
    [input_tokens] => 69
    [output_tokens] => 51
    [result] => Hello!
)
*/