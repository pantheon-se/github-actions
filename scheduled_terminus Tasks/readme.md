# Generate Site Inventory with Terminus

Some organizations need to keep track of their site inventory in an external system, like ServiceNow, and this script will utilize the Github Actions cron scheduler to regularly fetch site inventory through Terminus for an organization, combine those into a JSON file, and email it to an end user or system.

## Secrets Required

- Terminus Machine Token
- Pantheon Organization ID
- SendGrid API Key
- Mail Recipient (who is recieving)
- Mail User (who is sending)

## Workflow Overview

1. Checkout git repo
2. Install PHP
3. Install Composer dependencies
4. Authenticate with Terminus
5. Run Site Inventory Script
  1. Get the list of sites in the organization
  2. Get the list of users in the organization
  3. Loop through the sites, and replace the user UUID with their email
  4. Encode data in a JSON file
  5. Email JSON file to defined recipient
