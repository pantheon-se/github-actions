<?php
// Increase memory to 1GB
ini_set('memory_limit', '1028M');

// Override database
$databases['default']['default'] = [
  'database' => getenv('CRON_DB_NAME'),
  'username' => getenv('CRON_DB_USER'),
  'password' => getenv('CRON_DB_PASS'),
  'host' => getenv('CRON_DB_HOST'),
  'port' => getenv('CRON_DB_PORT'),
  'driver' => 'mysql',
];
