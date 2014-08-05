# -*- mode: ruby -*-
# vi: set ft=ruby :
github_username = "fideloper"
github_repo     = "Vaprobash"
github_branch   = "1.0.1"
github_url      = "https://raw.githubusercontent.com/#{github_username}/#{github_repo}/#{github_branch}"
hostname        = "nemex.dev"
server_ip       = "192.168.42.03"
server_memory   = "384" # MB
server_swap     = "768" # Options: false | int (MB) - Guideline: Between one or two times the server_memory
server_timezone = "UTC"
php_timezone    = "UTC" # http://php.net/manual/en/timezones.php
hhvm            = "false"
public_folder   = "/vagrant"

Vagrant.configure("2") do |config|
  config.vm.box = "ubuntu/trusty64"
  config.vm.provision "shell",
    inline: "echo setting timezone to #{server_timezone}; ln -sf /usr/share/zoneinfo/#{server_timezone} /etc/localtime"
  config.vm.hostname = hostname
  config.vm.network :private_network, ip: server_ip
  config.vm.synced_folder ".", "/vagrant",
            id: "core",
            :nfs => true,
            :mount_options => ['nolock,vers=3,udp,noatime']
  config.vm.provider :virtualbox do |vb|
    vb.customize ["modifyvm", :id, "--memory", server_memory]
    vb.customize ["guestproperty", "set", :id, "/VirtualBox/GuestAdd/VBoxService/--timesync-set-threshold", 10000]
    vb.customize ["modifyvm", :id, "--natdnshostresolver1", "on"]
    vb.customize ["modifyvm", :id, "--natdnsproxy1", "on"]
  end

  config.vm.provider "vmware_fusion" do |vb, override|
    override.vm.box_url = "http://files.vagrantup.com/precise64_vmware.box"
    vb.vmx["memsize"] = server_memory
  end

  if Vagrant.has_plugin?("vagrant-cachier")
    config.cache.scope = :box
    config.cache.synced_folder_opts = {
        type: :nfs,
        mount_options: ['rw', 'vers=3', 'tcp', 'nolock']
    }
  end

  config.vm.provision "shell", path: "#{github_url}/scripts/base.sh", args: [github_url, server_swap]
  config.vm.provision "shell", path: "#{github_url}/scripts/php.sh", args: [php_timezone, hhvm]
  config.vm.provision "shell", path: "#{github_url}/scripts/nginx.sh", args: [server_ip, public_folder, hostname, github_url]
end
