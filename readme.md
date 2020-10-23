# Pantheon, Terminus, and GitHub Actions

A list of Github Action workflows that can be used to integrate with Pantheon through Terminus.

- [External PHP / Cron Tasks](/external_php_cli)
- [Parallel Terminus Commands](/parallel_terminus_commands)
- [Remote Terminus Execution (Web API)](/remote_terminus_execution)
- [Scheduled Terminus Tasks](/scheduled_terminus_tasks)
- [Automated Upstream Sync](/upstream_core_sync)

## Github Actions Cheat Sheet

Github Actions has some platform-specific methods for doing special tasks in builds. Some are based on specific YML keys like `run` or `env`, others are inline commands that use a colon-based syntax `::set-env name=SIZE::`.

### Dynamically set env variables
When you need to dynamically set a shell variable to be accessible between steps, you need to output it to the `$GITHUB_ENV` variable.
```
echo "VAR_NAME=VAR_VALUE" >> $GITHUB_ENV
```

The colon syntax will be deprecated, so don't use this:
```echo "::set-env name=VAR_NAME::$(echo $VAR_VALUE)"```

### Dynamically add custom paths

Similar to env variables, you can dynamically make a path available to steps by appending it to the `$GITHUB_PATH` variable.

```
echo "$GITHUB_WORKSPACE/path/to/bin" >> $GITHUB_PATH
```

The colon syntax will be deprecated, so don't use this:
`echo "::add-path::path/to/bin"`

## FAQ

Common gotchas when working with Terminus in a build container.

### Permission denied (password,publickey)

When running commands that need to reach the appserver itself, such as remote Drush or WP-CLI, in addition to a Terminus machine token, you will need to install a private SSH key that also has the public key associated with the user account that issued the machine token.

This private key will need to be generated in a PEM format, as the standard OpenSSH format has some issues in the build containers.

```
ssh-keygen -m PEM -f ~/.ssh/id_rsa
```

### Host key verification failed. fatal: Could not read from remote repository.

If you need to connect to the codeserver on Pantheon, you have to manually add the full codeserver/appserver paths for the site. Something like the following:

```
ssh-keyscan -t rsa -p 2222 "appserver.dev.${SITE_ID}.drush.in" >> ~/.ssh/known_hosts
ssh-keyscan -t rsa -p 2222 "codeserver.dev.${SITE_ID}.drush.in" >> ~/.ssh/known_hosts
```

### Terminus Authentication

There are two approaches to Terminus authentication, both work just fine but can be redundant depending on your setup:

1. Use the Terminus Github Action ([kopepasah/setup-pantheon-terminus](https://github.com/marketplace/actions/setup-pantheon-terminus))
2. Include Terminus in the repo dependencies (`composer require pantheon-systems/terminus`)

Use whichever method is most efficient for your specific setup.

# Disclaimer
THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
