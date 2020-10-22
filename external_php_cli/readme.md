# External PHP CLI Execution

When running Drush, WP, or other PHP CLI commands, you may need to bypass any appserver limitations. When running Drush or WP-CLI through Terminus remotely, there is no `max_execution_time` being invoked through the CLI, but you are still restricted to the `memory_limit` of the appserver.

This action will make a copy of the Pantheon site repository, fetch the public database credentials, then use the build container PHP configuration to bypass any appserver limitations and run a Drush command.

**Note**
This process does require a configuration adjustment within the application in order to swap out the connection credentials to remotely connect to the live database.

## Secrets Required

- Terminus Machine Token (`TERMINUS_MACHINE_TOKEN`)
- Pantheon Site ID (`PANTHEON_SITE_ID`)
- Private SSH key in PEM format (`PRIVATE_SSH_KEY`)

## Workflow Overview

1. Install PHP
   - This setup script sets `memory_limit` to -1
2. Install private SSH key
   - Will need to manually update known_hosts with specific host paths for Pantheon appservers - these will not autoresolve.
3. Clone the Pantheon site repo into the build container and check out the live tag.
   - If you're using Github in addition to Pantheon, you could technically use the Github repo code, but this step will ensure the code matches exactly what is sitting on live.
4. Install Composer dependencies
5. Create bin aliases for Terminus and Drush (provided through Composer dependencies)
6. Authenticate Terminus
7. Fetch and replace connection credentials to live environment
   - The script to do this is written PHP because it is much simpler to parse output rather than through Bash.
   - We use a specific Github Actions method to assign the script outputs to shell variables for the next step.
8. Run CLI command
   - This could be Drush, WP-CLI, cron, etc.
   - Drush will bootstrap Drupal, but will swap out the database credentials in the presence of a `CRON_DB_USER` shell variable, which was set in the previous step.