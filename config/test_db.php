<?php
$db = require __DIR__ . '/db.php';
$db['dsn'] = 'pgsql:host=localhost;port=5432;dbname=prizes_db_test';

return $db;
