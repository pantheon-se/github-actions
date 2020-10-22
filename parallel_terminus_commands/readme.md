# Parallel Terminus Execution

When running commands against a large number of sites, we can run these tasks asynchronously because each site is independent. There are two ways of running asynchronous tasks:

1. Asynchronous background shell commands
2. Parallel shell commands

The general approach for this requires wrapping multiple Terminus commands into a single shell script that accepts a Site ID, which can then be used in a asynchronous loop or parallel method.

## Secrets Required
- Terminus Machine Toke (`TERMINUS_MACHINE_TOKEN`)
- SSH Key in PEM format (`PRIVATE_SSH_KEY`)
- Pantheon Upstream UUID (`UPSTREAM_UUID`)
- Pantheon Organization UUID (`ORG_UUID`)
- Slack Webhook URL (`SLACK_WEBHOOK`)

## Workflow Overview

1. Prepare the build environment
2. Authenticate Terminus
3. Generate list of site UUIDs
4. Asynchronously process sites
   - **Async Background Processes**
     Utilize `&` to send shell process to the background, add delay (`sleep`) between processes, wait for all background processes to finish (use `wait`).
   - **Parallel Processing**
     Pipe site IDs to GNU Parallel, set max jobs capacity, pass site ID to script, wait for job queue to complete.  