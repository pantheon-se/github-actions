# Custom Upstream Branch Sync and Multidev Creation

When using a Custom Upstream, sync new upstream branches automatically to a downstream Pantheon repo and create a multidev environment. As-is, this requires a site to be tagged with `canary` that will recieve the update.

## Secrets Required

- `PANTHEON_ORG_UUID`: The UUID of the organization that owns the custom upstream.
- `PANTHEON_UPSTREAM_UUID`: The UUID of the custom upstream connected to the GitHub repository.
- `PANTHEON_MACHINE_TOKEN`: A machine token for running Terminus commands that are also associated with the SSH key below.
- `PANTHEON_SSH_KEY`: The PRIVATE SSH key that is associated with the public key that has been added to the Pantheon user account.
- `SSH_CONFIG`: A basic SSH config when connecting to the `drush.in` addresses for running WP / Drush commands.
- `KNOWN_HOSTS`: You do not need a known_hosts config unless required, so this only needs to be a single space as the secret variable content.

## Additional Notes

### Pull Request Comments
To enable commenting post-back support on Pull Requests, you need to enable permissions for Github Actions.

1. Go to https://github.com/OWNER/REPO/settings/actions
2. Under Workflow Permissions section, give Actions Read and Write permissions

### SSH Config
When adding `SSH_CONFIG`, use the following as a baseline configuration.

```bash
Host *.drush.in
  StrictHostKeyChecking no
```

### SSH Key
When generating an SSH key, it needs to be in PEM format. You either modify an existing keyfile, or generate a new one.

**Create new PEM key**
```bash
ssh-keygen -m PEM -f <path_to_key_file>
```

**Modify existing key**
```bash
ssh-keygen -p -m PEM -f <path_to_key_file>
```

## Workflow Overview

1. Checkout custom upstream repository
1. Configure SSH credentials
1. Install and authenticate Terminus
1. Get canary site ID from the upstream
1. Sync branch to canary site repo
1. Create multidev environment on new branch (if able to)

Largely based on the work of [Tyler Fahey](https://github.com/twfahey1) and his [GitHub Actions](https://medium.com/swlh/pantheon-and-github-actions-automated-deployments-via-github-actions-c245aa954797) documentation.
