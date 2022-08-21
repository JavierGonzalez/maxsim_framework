<?php # maxsim.tech — MIT License — Copyright (c) 2005 Javier González González <javier.gonzalez@maxsim.tech>

$echo = [];

$target_path[] = '';
if (isset($_GET['target'])) {
    $target_path = array_merge($target_path, array_filter(explode('/', $_GET['target'])));

    if (substr($_GET['target'], -1, 1) != '/')
        array_pop($target_path);
}

$value_path = [];
foreach ($target_path AS $value) {
    if ($value)
        $value_path[] = $value;
    $ls = array_merge((array)$ls, glob(($value?implode('/', (array)$value_path).'/*':'{,.}[!.,!..]*'), GLOB_MARK | GLOB_BRACE));
}


natcasesort($ls);

foreach ($ls AS $item) {
    
    $type = (is_dir($item)?'dir':'file');

    $tree[(fnmatch('*\/*',$item)?'dir':$type)][] = array_filter([
        'name' => $item,
        'type' => $type,
        'logs' => ($type=='file' && file_exists('maxsim/log/app/'.str_replace('/', '|', $item).'.log')?true:null),
    ]);
}

$echo['tree'] = array_merge($tree['dir'], $tree['file']);

exit_json($echo);