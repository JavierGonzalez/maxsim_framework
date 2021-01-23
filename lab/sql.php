<?php

// SELECT nick, COUNT(*) FROM users 
// WHERE status = 'ok' AND nick != 'gonzo' AND level > 10 AND email LIKE '%@b.com' AND param IS null 
// GROUP BY nick ORDER BY 2 DESC LIMIT 10;
sql([
    'select' => ['nick', 'COUNT(*)'],
    'from' => 'users',
    'where' => [
        'status' => 'ok',
        'nick !=' => ['gonzo'],
        'level >' => 10,
        '%email' => '@b.com',
        'param' => null,
    ],
    'group' => 'nick',
    'order' => '2 desc',
    'limit' => 10,
]);


// UPDATE users SET level = 9 WHERE status = 'ok' AND nick = 'gonzo' LIMIT 1
sql([
    'update' => 'users',
    'set' => [
        'email' => 'a@b.es',
        'level +' => 1,
        'data' => ['json_array' => true],
    ],
    'where' => [
        'status' => 'ok',
        'nick' => 'gonzo',
    ],
    'limit' => 1,
]);


// INSERT INTO users (nick, status, email, param) VALUES ('gonzo', 'ok', 'a@b.com', NULL);
sql([
    'insert' => 'users',
    'set' => [
        'nick' => 'gonzo',
        'status' => 'ok',
        'email' => 'a@b.com',
        'param' => null,
    ],
]);


// DELETE FROM users WHERE nick = 'gonzo';
sql([
    'delete' => 'users',
    'where' => [
        'nick' => 'gonzo',
    ],
]);









