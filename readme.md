ECUST-XX/ssSetup for Docker tutum/centos:centos7
=========================
本脚本适用于 Docker 镜像 tutum/centos:centos7

用于搭建ss与修改root密码

!!!现在不推荐使用本脚本!!!
------------
由于tutum/centos:centos7体积太大导致docker不稳定，所以已经不推荐使用该系统用来搭建SS

这里推荐使用mo2017/shadowsocks的Docker镜像，部署简单，体积小且稳定性较高

由于arukas.io对8388端口屏蔽，导致该docker不能正常使用，所以在mo2017/shadowsocks基础上重新修改，增加PORT环境变量

Docker使用说明
------------

添加ENV:
	PASS = yourpass(601qaz106)
	PORT = yourport(9621)

ECUSTss.sh使用说明
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
安装命令
```shell
   yum install -y wget;wget --no-check-certificate https://raw.githubusercontent.com/ECUST-XX/ssSetup/master/ECUSTss.sh;bash /root/ECUSTss.sh;
 ```

文件说明
------------
**ECUSTss.sh** 为tutum/centos:centos7的ss脚本

**shadowsocks-go.sh** 为[Teddysun](i@teddysun.com)的通用shadowsocks-go安装脚本

**centos_ss.docx** 为ss手动安装步骤说明

**curl.php** 为调用官方API的部分函数，方便批量处理

**do.php** 为API执行例子

**docker** docker文件夹

**Dockerfile** dockerfile 基于alpine linux

**Start** alpine中的ss配置脚本

**repositories** alpine国内镜像源
