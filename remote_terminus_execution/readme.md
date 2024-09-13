# Remote Terminus Command Execution

A poor man's way to execute Terminus commands via a Web API, using Github Repository Dispatches and Github Actions. 

**This does not require any of the code from the repository the Github Action workflow is associated with.*

## Secrets Required
- Terminus Machine Token (`TERMINUS_MACHINE_TOKEN`)
- SSH Key in PEM format (`PRIVATE_SSH_KEY`)

You will also need a personal **Github Token** to trigger the repository dispatch.

## Installation
Use the following steps to configure your workflow environment.

### 1. Generate SSH key

Generate a new SSH key that will be used to authenticate the Github Action container with Pantheon.

```
ssh-keygen -m PEM -f ~/.ssh/github_action
```

You need to copy the private key into your repository secrets, and the public key into your Pantheon account. If on a Mac, you can use the `pbcopy` command to copy to your clipboard.

```
pbcopy < ~/.ssh/github_action
```

- In your Github repo, name the variable `SSH_KEY` and paste in the value.
- In your [Pantheon account dashboard](https://dashboard.pantheon.io/users#account/ssh-keys), paste your public key (`github_action.pub`) into the text field. Consider adjusting the string label at the end of the key to something more descriptive, like **Github Action**.

### 2. Generate Pantheon Machine Token

1. In your [Pantheon account dashboard](https://dashboard.pantheon.io/users#account/tokens/list), generate a new Machine Token.
2. Copy the value from the browser.
3. Open your repository secrets, and create a new variable called `TERMINUS_MACHINE_TOKEN`.
4. Paste in the machine token and click save.

### 3. Generate Github Access Token

1. Go to your [Github tokens](https://github.com/settings/tokens)
2. Click "Generate New Token"
3. Give the token a name, and check `workflow` as the only permission (`@todo` confirm this)
4. Click "Generate Token"
5. Save the token to use in the next section.

## Create Repository Dispatch Request

Generate a new request using the following information, but a few important things to note:

- The API path requires the repo owner and name as part of the path (`:repo_owner/:repo_name`)
- The authorization is a bearer token and is your Github access token
- If using the provided workflow template, the `event_type` is specific to this workflow. **DO NOT CHANGE**.

```bash
curl --location --request POST 'https://api.github.com/repos/:repo_owner/:repo_name/dispatches' \
--header 'Accept: application/vnd.github.everest-preview+json' \
--header 'Authorization: Bearer <ACCESS TOKEN>' \
--header 'Content-Type: application/json' \
--data-raw '{
    "event_type": "remote-terminus",
    "client_payload": {
        "command": "drush dunder-mifflin-sales.dev -- cr"
    }
}'
```

## Workflow Overview

1. Trigger on repository dispatch using the `remote-terminus` event_type key.
1. Install private SSH key from secrets to authenticate with Terminus user
1. Install Terminus and authenticate with machine token from secrets
1. Run Terminus command from API payload.
