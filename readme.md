ECUST-XX/ssSetup for Docker tutum/centos:centos7
=========================
本脚本适用于 Docker 镜像 tutum/centos:centos7

用于搭建ss与修改root密码

使用说明
------------

本脚本只在tutum/centos:centos7做过测试并正常通过
其他系统未测试(如果是其它版本linux可使用另一个通用脚本)

脚本配置
------------
* 默认ss端口号：9621
* 默认ss密码：601qaz106
* 默认root密码：qazokmwsxijn

如果要修改ss可通过添加参数设置
 ```shell
    ECUSTss.sh 1234 your_secret
 ```
如果修改root密码则需要修改脚本中的change_secret()函数
```shell
    function change_secret(){
    	echo "-----Change root secret-----"
    	echo "qazokmwsxijn"| passwd --stdin root
    }
 ```
 
文件说明
------------
**ECUSTss.sh** 为tutum/centos:centos7的ss脚本
**shadowsocks-go.sh** 为[Teddysun](i@teddysun.com)的通用shadowsocks-go安装脚本
**centos_ss.docx** 为ss手动安装步骤