# Custom Upstream Branch Sync and Multidev Creation

When using a Custom Upstream, sync new upstream branches automatically to a downstream Pantheon repo and create a multidev environment. As-is, this requires a site to be tagged with `canary` that will recieve the update.

## Secrets Required

- `PANTHEON_ORG_UUID`: The UUID of the organization that owns the custom upstream.
- `PANTHEON_UPSTREAM_UUID`: The UUID of the custom upstream connected to the GitHub repository.
- `PANTHEON_MACHINE_TOKEN`: A machine token for running Terminus commands that are also associated with the SSH key below.
- `PANTHEON_SSH_KEY`: The PRIVATE SSH key that is associated with the public key that has been added to the Pantheon user account.
- `SSH_CONFIG`: A basic SSH config when connecting to the `drush.in` addresses for running WP / Drush commands.
- `KNOWN_HOSTS`: You do not need a known_hosts config unless required, so this only needs to be a single space as the secret variable content.

## Workflow Overview

1. Checkout custom upstream repository
1. Configure SSH credentials
1. Install and authenticate Terminus
1. Get canary site ID from the upstream
1. Sync branch to canary site repo
1. Create multidev environment on new branch (if able to)

Largely based on the work of [Tyler Fahey](https://github.com/twfahey1) and his [GitHub Actions]([url](https://medium.com/swlh/pantheon-and-github-actions-automated-deployments-via-github-actions-c245aa954797)) documentation.
