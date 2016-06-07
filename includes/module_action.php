<? 
/*
    Copyright (C) 2013-2016 xtr4nge [_AT_] gmail.com

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/ 
?>
<?

include "../../../config/config.php";
include "../_info_.php";
include "../../../login_check.php";
include "../../../functions.php";

include "options_config.php";

// Checking POST & GET variables...
if ($regex == 1) {
    regex_standard($_GET["service"], "../msg.php", $regex_extra);
    regex_standard($_GET["action"], "../msg.php", $regex_extra);
    regex_standard($_GET["page"], "../msg.php", $regex_extra);
    regex_standard($io_action, "../msg.php", $regex_extra);
    regex_standard($_GET["mac"], "../msg.php", $regex_extra);
    regex_standard($_GET["install"], "../msg.php", $regex_extra);
	regex_standard($_POST["ssh_cert"], "../msg.php", $regex_extra);
}

$service = $_GET['service'];
$action = $_GET['action'];
$page = $_GET['page'];
$mac =  strtoupper($_GET['mac']);
$install = $_GET['install'];

$ssh_cert = $_POST['ssh_cert'];

if($service == "station" and $mac != "") {
	if($action == "allow") {
		$exec = "iptables -t nat -A PREROUTING -p tcp -m mac --mac-source $mac -j MARK --set-mark $mod_captive_mark";
	} else {
		$exec = "iptables -t nat -D PREROUTING -p tcp -m mac --mac-source $mac -j MARK --set-mark $mod_captive_mark";
	}
	exec_fruitywifi($exec);
	
	header("Location: ../index.php?tab=1");
	exit;
}

if($service == "sshuttle") {
    
    if ($action == "start") {
        // START MODULE
        
        // COPY LOG
        if ( 0 < filesize( $mod_logs ) ) {
            $exec = "cp $mod_logs $mod_logs_history/".gmdate("Ymd-H-i-s").".log";
			exec_fruitywifi($exec);
            
            $exec = "echo '' > $mod_logs";
			exec_fruitywifi($exec);
        }
        
		$exec = "$bin_sshuttle -r $mod_sshuttle_user@$mod_sshuttle_server:$mod_sshuttle_port 0/0 -l 0.0.0.0:8888 --dns  -e 'ssh -i ./id_rsa' -x $io_in_ip -D > /dev/null &";
		#$exec = "$bin_sshuttle -r $mod_sshuttle_user@$mod_sshuttle_server:$mod_sshuttle_port 10.0.0.1/24 -l 10.0.0.1:8888 --dns  -e 'ssh -i ./id_rsa' -x $io_in_ip -D > /dev/null &";
		exec_fruitywifi($exec);
        
    } else if($action == "stop") {
        // STOP MODULE
		// iptables -L -t nat -v --line-numbers -n
		$exec = "ps aux|grep -iEe 'sshuttle.+python.+ssh.+-D' | grep -v grep | awk '{print $2}'";
		exec($exec,$output);
		
		$exec = "kill " . $output[0];
		exec_fruitywifi($exec);
		
		$exec = "iptables -t nat -F sshuttle-8888";
		exec_fruitywifi($exec);
		
		$exec = "iptables -t nat -X sshuttle-8888";
		exec_fruitywifi($exec);
		
        // CLEAN USERS FILE
        $exec = "echo '-' > $file_users";
		exec_fruitywifi($exec);
        
        // COPY LOG
        if ( 0 < filesize( $mod_logs ) ) {
            $exec = "cp $mod_logs $mod_logs_history/".gmdate("Ymd-H-i-s").".log";
			exec_fruitywifi($exec);
            
            $exec = "echo '' > $mod_logs";
			exec_fruitywifi($exec);
        }

    }

}

if ($ssh_cert == "gen_certificate") {
    $exec = "$bin_rm id_rsa";
    exec_fruitywifi($exec);
    
    $exec = "$bin_rm id_rsa.pub";
    exec_fruitywifi($exec);
    
    $exec = "$bin_ssh_keygen -t rsa -f id_rsa -C @FruityWifi";
    exec_fruitywifi($exec);
    
    header('Location: ../index.php?tab=2');
    exit;
}

if ($install == "install_sshuttle") {

    $exec = "$bin_chmod 755 install.sh";
    exec_fruitywifi($exec);

    $exec = "$bin_sudo ./install.sh > $log_path/install.txt &";
    exec_fruitywifi($exec);

    header('Location: ../../install.php?module='.$mod_name);
    exit;
}

if ($page == "status") {
    header('Location: ../../../action.php');
} else {
    header('Location: ../../action.php?page='.$mod_name);
}

?>