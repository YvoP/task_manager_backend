#!/bin/sh
set -e # Stop the script if any command fail

USER_ID=${1}
GROUP_ID=${2}
USER_NAME="appuser"
GROUP_NAME="appgroup"

# Check if the user with the given id already exist
if getent passwd ${USER_ID} >/dev/null; then
    echo "User ID ${USER_ID} already exists. Skipping creation."
else
    # create group
    groupadd -g ${GROUP_ID} ${GROUP_NAME}
    # create user with his own home (very important)
    useradd -u ${USER_ID} -m -g ${GROUP_ID} ${USER_NAME}

    echo "User ID ${USER_ID} does not exist. User has been created."
fi
