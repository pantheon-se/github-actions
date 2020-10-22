<?php

// Append the following to your settings.php file

/**
 * Override database credentials for external cron tasks.
 */
if ((php_sapi_name() == "cli") && getenv('CRON_DB_USER')) {
  $cron_settings = __DIR__ . "/settings.cron.php";
  if (file_exists($cron_settings)) {
    include $cron_settings;
  }
}
