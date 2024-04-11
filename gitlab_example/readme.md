# GitLab Deployment Example

In this example for a custom upstream, we generate a `gitlab_jobs.yml` file on the fly as it is the only way to pass in a dynamic list of site UUIDs for deploying code to. The key steps in the `generate-config` are below:

```
# Stash list of all Pantheon sites in the org
PANTHEON_SITES="$(terminus org:site:list ${ORG_ID} --format list --upstream ${UPSTREAM_ID} --field name)"

# Remove new lines and insert commas inbetween
SITES_ARRAY="${PANTHEON_SITES//$'\n'/, }"
```

The `SITES_ARRAY` is then used to populate a matrix property in the `gitlab_jobs.yml` file. The job consists of an initial step the creates a Terminus session, then that session is passed to the downstream job so that we don't run into auth0 rate limiting. This session is not encrypted, but our official [Terminus Github Action](https://github.com/pantheon-systems/terminus-github-actions/blob/v1.2.1/action.yml#L107) does encrypt the session, so that code could be lifted and re-used.

The `deploy` script is then used for each individual site ID to run through deployment steps. This script can / should be updated for each customer specific use case.