<?
$mod_name="sshuttle";
$mod_version="1.0";
$mod_path="/usr/share/fruitywifi/www/modules/$mod_name";
$mod_logs="$log_path/$mod_name.log"; 
$mod_logs_history="$mod_path/includes/logs/";
$mod_panel="show";
$mod_type="service";
$mod_isup="ps aux | grep -iEe 'sshuttle.+python.+ssh.+-D' |grep -v grep";
$mod_alias="SSHuttle";

# OPTIONS
$mod_sshuttle_user="";
$mod_sshuttle_server="";
$mod_sshuttle_port="22";
$mod_sshuttle_listen="1";
$mod_sshuttle_listen_value="0.0.0.0:0";
$mod_sshuttle_dns="1";

//sshuttle -r {user}@{server}:{port} 0/0 -l 0.0.0.0:0 --dns

# EXEC
$bin_sudo = "/usr/bin/sudo";
$bin_sshuttle = "/usr/sbin/sshuttle";
$bin_iptables = "/sbin/iptables";
$bin_awk = "/usr/bin/awk";
$bin_grep = "/bin/grep";
$bin_sed = "/bin/sed";
//$bin_conntrack = "/usr/sbin/conntrack";
$bin_cat = "/bin/cat";
$bin_echo = "/bin/echo";
$bin_ln = "/bin/ln";
$bin_arp = "/usr/sbin/arp";
$bin_rm = "/bin/rm";
$bin_ssh_keygen = "/usr/bin/ssh-keygen";
?>
