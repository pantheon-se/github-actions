<?php

// Authenticate with Terminus
passthru('.github/scripts/vendor/bin/terminus auth:login --machine-token=' . getenv('TERMINUS_MACHINE_TOKEN'));
