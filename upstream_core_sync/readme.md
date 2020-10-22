# Automated Core Upstream Sync

If using a Custom Upstream that has been cloned from one of Pantheon's managed upstreams, this workflow will attempt to regularly merge in Core updates from Pantheon's upstreams.

## Secrets Required

- None

## Workflow Overview

1. Checkout Custom Upstream repo
2. Add the managed upstream as a remote
3. Add required config settings for Git
4. Pull, merge, and commit the latest code back to Custom Upstream
