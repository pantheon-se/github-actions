# Pantheon, Terminus, and GitHub Actions

A list of Github Action workflows that can be used to integrate with Pantheon through Terminus.

- [External PHP / Cron Tasks](/external_php_cli)
- [Parallel Terminus Commands](/parallel_terminus_commands)
- [Remote Terminus Execution (Web API)](/remote_terminus_execution)
- [Scheduled Terminus Tasks](/scheduled_terminus_tasks)
- [Automated Upstream Sync](/upstream_core_sync)
- [Custom Upstream and Multidev Creation](/custom_upstream_branch_sync)

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

Known issues and solutions when working with Terminus in a build container.

### Permission denied (password,publickey)

When running commands that need to reach the appserver itself, such as remote Drush or WP-CLI, in addition to a Terminus machine token, you will need to install a private SSH key that also has the public key associated with the user account that issued the machine token. The easiest way to do this is to use [shimataro/ssh-key-action](https://github.com/marketplace/actions/install-ssh-key) to setup the SSH configuration. Using the example snippet below:

```yaml     
  - uses: shimataro/ssh-key-action@v2
    with:
      key: ${{ secrets.PANTHEON_SSH_KEY }}
      config: ${{ secrets.SSH_CONFIG }}
      known_hosts: ${{ secrets.KNOWN_HOSTS }}
```

- `PANTHEON_SSH_KEY`: The _PRIVATE_ SSH key that is associated with the public key that has been added to the Pantheon user account.
- `SSH_CONFIG`: A basic SSH config when connecting to the `drush.in` addresses for running WP / Drush commands.
- `KNOWN_HOSTS`: You do not need a known_hosts config unless required, so this only needs to be a single space as the secret variable content.

#### PANTHEON_SSH_KEY
This private key will need to be generated in a PEM format, as the standard OpenSSH format has some issues in the build containers.
```
ssh-keygen -m PEM -f ~/.ssh/id_rsa
```

#### SSH_CONFIG

The following is required for the SSH configuration.
```
Host *.drush.in
  StrictHostKeyChecking no
```

### Host key verification failed. fatal: Could not read from remote repository.

If you need to connect to the codeserver on Pantheon, you have to manually add the full codeserver/appserver paths for the site. Something like the following:

```
ssh-keyscan -t rsa -p 2222 "appserver.dev.${SITE_ID}.drush.in" >> ~/.ssh/known_hosts
ssh-keyscan -t rsa -p 2222 "codeserver.dev.${SITE_ID}.drush.in" >> ~/.ssh/known_hosts
```

### Terminus Authentication

We recommend using the official Pantheon Github Action for setting up Terminus:

- [pantheon-systems/terminus-github-actions](https://github.com/pantheon-systems/terminus-github-actions)

```yaml
  - name: Install Terminus
    uses: pantheon-systems/terminus-github-actions@main
    with:
      pantheon-machine-token: ${{ secrets.PANTHEON_MACHINE_TOKEN }}
      terminus-version: 2.6.5 # Optional
```

# Disclaimer
THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
