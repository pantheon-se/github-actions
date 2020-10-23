# Pantheon Downstream Repo Sync

If you are using a custom upstream and want new upstream branches to automatically get pushed to Pantheon's downstream repo, this workflow will provide that functionality.

## Secrets Required

- Github Source Repo URL (`MY_SOURCE_REPO`)
- Remote Pantheon Repo URL (`REMOTE_PANTHEON_REPO`)
- SSH Private Key (`SSH_PRIVATE_KEY`)

## Workflow Overview

1. Checkout Custom Upstream repo
2. Create a new branch and publish to Github
3. Action automatically pushes that new branch to the repo defined in the REMOTE_PANTHEON_REPO secret
