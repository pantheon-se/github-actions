<?php

require __DIR__ . '/vendor/autoload.php';

use SendGrid\Mail\Mail;
use SendGrid\Mail\Attachment;

// Set up vars
$org_id = getenv('PANTHEON_ORGANIZATION_ID');
$time = date("Y-m-d H:i:s");

// Get sites
$sites = json_decode(shell_exec(".github/scripts/vendor/bin/terminus org:site:list --format=json " . $org_id), 1);

// Get users
$users = json_decode(shell_exec(".github/scripts/vendor/bin/terminus org:people:list --format=json " . $org_id), 1);

// Update site owner with email.
echo "Parsing sites for owners... \n";
foreach ($sites as &$site) {
  // If the site owner exists in the org users, map the email.
  if (!empty($users[$site['owner']])) {
    $site['owner'] = $users[$site['owner']]['email'];
  } else {
    // If not, manually get the site owner
    $team = json_decode(shell_exec(".github/scripts/vendor/bin/terminus site:team:list --format=json " . $site['id']), 1);

    // Merge with existing list so we don't have to do this again.
    $users = array_merge_recursive($users, $team);

    // Re-assign owner.
    $site['owner'] = $users[$site['owner']]['email'];
  }
}

// Update sites.json
echo "Encoding site data... \n";
$file_encoded = base64_encode(json_encode($sites, JSON_PRETTY_PRINT));

// Prepare email
echo "Preparing email... \n";
$email = new Mail();
$email->setFrom(getenv('MAIL_USERNAME'));
$email->setSubject("[{$time}] Terminus Site Update - {$org_id}");
$email->addTo(getenv('MAIL_RECIPIENT'));
$email->addContent("text/plain", "Sending latest sites.");

// Add attachment
$attachment = new Attachment();
$attachment->setContent($file_encoded);
$attachment->setType("application/json");
$attachment->setFilename("sites.json");
$attachment->setDisposition("attachment");
$email->addAttachment($attachment);

// Send email
$sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
try {
  $response = $sendgrid->send($email);
  print $response->statusCode() . "\n";
  print_r($response->headers());
  print $response->body() . "\n";
} catch (Exception $e) {
  echo 'Caught exception: ' .  $e->getMessage() . "\n";
}
