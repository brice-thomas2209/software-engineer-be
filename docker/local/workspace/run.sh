#!/usr/bin/env bash

service ssh restart

# Create the default users.txt if it does not exist yet.
if [ ! -f /home/workspace/code/local/workspace/users.txt ]; then
    cp /home/workspace/code/docker/local/workspace/users.txt.example /home/workspace/code/docker/local/workspace/users.txt
fi

# Recreate the workspace user
deluser workspace
newusers /home/workspace/code/docker/local/workspace/users.txt
printf "Recreated workspace user\n"

chown workspace:workspace /home/workspace/code/docker/local/workspace/users.txt

cat /home/workspace/code/docker/local/workspace/aliases > /home/workspace/.bashrc
cat /home/workspace/code/docker/local/workspace/aliases > /home/workspace/.bash_profile
echo 'cd code' >> /home/workspace/.bashrc
echo 'cd code' >> /home/workspace/.bash_profile

chown workspace:workspace /home/workspace
chown workspace:workspace /home/workspace/.bashrc
chown workspace:workspace /home/workspace/.bash_profile

# Attempt to load in the developer's .gitconfig file.
if [ -f /tmp/home/.gitconfig ]; then
    cp /tmp/home/.gitconfig /home/workspace/.gitconfig
    chown workspace:workspace /home/workspace/.gitconfig
fi

# Attempt to load in the developer's private SSH key.
mkdir /home/workspace/.ssh
chown workspace:workspace /home/workspace/.ssh
if [ -f /tmp/home/.ssh/id_rsa ]; then
    cp /tmp/home/.ssh/id_rsa /home/workspace/.ssh/id_rsa
    chown workspace:workspace /home/workspace/.ssh/id_rsa
fi

# Attempt to load in the developer's public SSH key.
if [ -f /tmp/home/.ssh/id_rsa.pub ]; then
    cp /tmp/home/.ssh/id_rsa.pub /home/workspace/.ssh/authorized_keys
    chown workspace:workspace /home/workspace/.ssh/authorized_keys
fi

# Make vim the default editor
update-alternatives --set editor /usr/bin/vim.basic

# Hit it!
/sbin/my_init