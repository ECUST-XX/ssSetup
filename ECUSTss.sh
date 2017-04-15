#!/usr/bin/env bash
PATH=/bin:/sbin:/usr/bin:/usr/sbin:/usr/local/bin:/usr/local/sbin:~/bin
export PATH
#=================================================================#
#   System Required:  CentOS				                      #
#   Description:  Install Shadowsocks server					  #
#   Author: ECUST-XX <13673460778@163.com>                        #
#   Thanks: @cyfdecyf <https://twitter.com/cyfdecyf>              #
#   Intro:       https://github.com/ECUST-XX/ssSetup              #
#==================================================================

clear
echo
echo "#############################################################"
echo "#			Install Shadowsocks server						  #"
echo "# Intro: https://github.com/ECUST-XX/ssSetup                #"
echo "# Author: ECUST-XX <13673460778@163.com>                    #"
echo "# Github: https://github.com/ECUST-XX/ssSetup				  #"
echo "#############################################################"
echo



#Install necessary dependencies
function install_dependencies(){
	yum install -y sudo passwd epel-release python-setuptools m2crypto supervisor
	echo "-----Install necessary dependencies list-----"
	echo "passwd epel-release python-setuptools m2crypto supervisor"

	if Check_software passwd && Check_software epel-release && Check_software python-setuptools && Check_software m2crypto && Check_software supervisor;then
		echo "Error: Install necessary dependencies failed."
		exit 1
	fi
}

#Check install
function check_software(){
	local checkType=$1
	temp=`yum list installed | grep "${checkType}"`
	if [ -n "$temp" ];then
		echo "${checkType} installed"
		return 1
	elif [ -z "$temp" ];then
		echo "${checkType} installed failed"
		return 0
	fi
}



#Change root secret
function change_secret(){
	echo "-----Change root secret-----"
	echo "qazokmwsxijn"| passwd --stdin root
}


#Install pip
function install_pip(){
	easy_install pip
	echo "-----Install pip-----"
}

#Install shadowsocks
function install_shadowsocks(){
	pip install shadowsocks
	echo "-----Install shadowsocks-----"
}


#Config and start shadowsocks
function config_shadowsocks(){
    echo "-----Config shadowsocks-----"
    cat >> /etc/supervisord.conf<<-EOF
[program:shadowsocks]
command=ssserver -c /etc/shadowsocks.json
autostart=true
autorestart=true
user=root
log_stderr=true
logfile=/var/log/shadowsocks.log

EOF
	echo "service supervisord start" >> /etc/rc.local
}


#Config my ss.log
function config_my_ss(){
    echo "-----Config my ss.log-----"
    
    cat > /root/ss.log<<-EOF
{
    "server":"0.0.0.0",
    "server_port":${shadowsocksport},
    "local_port":1080,
    "password":"${shadowsockspwd}",
    "method":"aes-256-cfb",
    "timeout":600
}
EOF
}


#Read and start ssserver
function start_ssserver(){
	ssserver -c /root/ss.log -d start
}


#Set shadowsockspwd shadowsocksport
function set_shadowsockspwd_shadowsocksport(){
	shadowsocksport = $1
	shadowsockspwd = $2

	[ -z "${shadowsockspwd}" ] && shadowsockspwd="601qaz106"
	[ -z "${shadowsocksport}" ] && shadowsocksport="9621"

	echo "Set shadowsockspwd:${shadowsockspwd} shadowsocksport:${shadowsocksport}"
}

#Initialization step


set_shadowsockspwd_shadowsocksport $1 $2
install_dependencies
install_pip
install_shadowsocks
config_shadowsocks
config_my_ss
start_ssserver
change_secret