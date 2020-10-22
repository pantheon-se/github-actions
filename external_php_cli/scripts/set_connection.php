<?php

// Ensure the command is running from CLI
if ((php_sapi_name() == "cli")) {

  $site_id = getenv('SITE_ID');
  $mysql_str = shell_exec("terminus connection:info ${site_id}.live --format list --field 'MySQL Command'");

  $mysql_parts = explode(" ", $mysql_str);

  // Print db creds for Github Actions environment variables
  $CRON_DB_USER = $mysql_parts[2];
  $CRON_DB_PASS = substr($mysql_parts[3], 2);
  $CRON_DB_HOST = $mysql_parts[5];
  $CRON_DB_PORT = $mysql_parts[7];
  $CRON_DB_NAME = $mysql_parts[8];

  $output = <<<EOD
  CRON_DB_USER=${CRON_DB_USER}
  CRON_DB_PASS=${CRON_DB_PASS}
  CRON_DB_HOST=${CRON_DB_HOST}
  CRON_DB_PORT=${CRON_DB_PORT}
  CRON_DB_NAME=${CRON_DB_NAME}
  EOD;

  print $output;
}
