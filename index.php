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
include "../../login_check.php";
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>FruityWiFi</title>
<script src="../js/jquery.js"></script>
<script src="../js/jquery-ui.js"></script>
<link rel="stylesheet" href="../css/jquery-ui.css" />
<link rel="stylesheet" href="../css/style.css" />
<link rel="stylesheet" href="../../../style.css" />

<script src="includes/scripts.js"></script>

<script>
$(function() {
    $( "#action" ).tabs();
    $( "#result" ).tabs();
});

</script>

</head>
<body>

<? include "../menu.php"; ?>

<br>

<?
include "../../config/config.php";
include "_info_.php";
include "../../functions.php";


// Checking POST & GET variables...
if ($regex == 1) {
    regex_standard($_POST["newdata"], "msg.php", $regex_extra);
    regex_standard($_GET["logfile"], "msg.php", $regex_extra);
    regex_standard($_GET["action"], "msg.php", $regex_extra);
    regex_standard($_POST["service"], "msg.php", $regex_extra);
    //regex_standard($_GET["tempname"], "msg.php", $regex_extra);
}

$newdata = $_POST['newdata'];
$logfile = $_GET["logfile"];
$action = $_GET["action"];
//$tempname = $_GET["tempname"];
$service = $_POST["service"];


// DELETE LOG
if ($logfile != "" and $action == "delete") {
    $exec = "rm ".$mod_logs_history.$logfile.".log";
    exec_fruitywifi($exec);
}

// SET MODE
if ($_POST["change_mode"] == "1") {
    $ss_mode = $service;
    $exec = "/bin/sed -i 's/ss_mode.*/ss_mode = \\\"".$ss_mode."\\\";/g' includes/options_config.php";
    exec_fruitywifi($exec);
}

?>

<div class="rounded-top" align="left"> &nbsp; <b><?=$mod_alias?></b> </div>
<div class="rounded-bottom">

    &nbsp;&nbsp;version <?=$mod_version?><br>
    <? 
    if (file_exists($bin_sshuttle)) { 
        echo "&nbsp; $mod_alias <font style='color:lime'>installed</font><br>";
    } else {
        echo "&nbsp; $mod_alias <a href='includes/module_action.php?install=install_$mod_name' style='color:red'>install</a><br>";
    } 
    ?>
    
    <?
    $ismoduleup = exec($mod_isup);
    if ($ismoduleup != "") {
        echo "&nbsp; $mod_alias  <font color='lime'><b>enabled</b></font>.&nbsp; | <a href='includes/module_action.php?service=$mod_name&action=stop&page=module'><b>stop</b></a>";
    } else { 
        echo "&nbsp; $mod_alias  <font color='red'><b>disabled</b></font>. | <a href='includes/module_action.php?service=$mod_name&action=start&page=module'><b>start</b></a>"; 
    }
    ?>
    
</div>

<br>

<div id="msg" style="font-size: larger;">
Loading, please wait...
</div>

<div id="body" style="display:none;">

    <div id="result" class="module">
        <ul>
            <li><a href="#tab-output">Output</a></li>
            <li><a href="#tab-options">Options</a></li>
            <li><a href="#tab-ssh">SSHkey</a></li>
            <li><a href="#tab-history">History</a></li>
            <li><a href="#tab-about">About</a></li>
        </ul>
        <div id="tab-output">
            <form id="formLogs-Refresh" name="formLogs-Refresh" method="POST" autocomplete="off" action="index.php">
            <input type="submit" value="refresh">
            <br><br>
            <?
                if ($logfile != "" and $action == "view") {
                    $filename = $mod_logs_history.$logfile.".log";
                } else {
                    $filename = $mod_logs;
                }
            
                $data = open_file($filename);
                
                // REVERSE
                //$data_array = explode("\n", $data);
                //$data = implode("\n",array_reverse($data_array));
                
            ?>
            <textarea id="output" class="module-content" style="font-family: courier;"><?=htmlspecialchars($data)?></textarea>
            <input type="hidden" name="type" value="logs">
            </form>
            
        </div>
        <!-- END OUTPUT -->
        
        <!-- OPTIONS -->
        <div id="tab-options" class="history">
            
            <h5>
                <div style="width: 50px; display: inline-block">User</div> <input id="sshuttle_user" class="form-control input-sm" placeholder="User" value="<?=$mod_sshuttle_user?>" style="width: 145px; display: inline-block; " type="text" />
                <br>
                <div style="width: 50px; display: inline-block">Server</div> <input id="sshuttle_server" class="form-control input-sm" placeholder="Server" value="<?=$mod_sshuttle_server?>" style="width: 145px; display: inline-block; " type="text" />
                <br>
                <div style="width: 50px; display: inline-block">Port</div> <input id="sshuttle_port" class="form-control input-sm" placeholder="Port" value="<?=$mod_sshuttle_port?>" style="width: 145px; display: inline-block; " type="text" />
                <input class="btn btn-default btn-sm" type="button" value="save" onclick="setOption('sshuttle_user', 'mod_sshuttle_user'); setOption('sshuttle_server', 'mod_sshuttle_server'); setOption('sshuttle_port', 'mod_sshuttle_port')">
                
                <br><br>                
                
                <input id="sshuttle_listen" type="checkbox" name="my-checkbox" <? if ($mod_sshuttle_listen == "1") echo "checked"; ?> onclick="setCheckbox(this, 'mod_sshuttle_listen')" > Listen
                <br>
                <input id="sshuttle_listen_value" class="form-control input-sm" placeholder="LISTEN" value="<?=$mod_sshuttle_listen_value?>" style="width: 200px; display: inline-block; " type="text" />
                <input class="btn btn-default btn-sm" type="submit" value="save" onclick="setOption('sshuttle_listen_value', 'mod_sshuttle_listen_value')">
                
                <br><br>
                
                <input id="sshuttle_dns" type="checkbox" name="my-checkbox" <? if ($mod_sshuttle_dns == "1") echo "checked"; ?> onclick="setCheckbox(this, 'mod_sshuttle_dns')" > DNS
                
            </h5>
            
        </div>
        <!-- END OPTIONS -->
        
        <!-- SSH KEY -->
        
        <div id="tab-ssh" class="history">
            <form id="formLogs-Refresh" name="formLogs-Refresh" method="POST" autocomplete="off" action="includes/module_action.php">
                <div class="general" style="display:inline;">Public Key </div><input type="submit" value="generate">
                <br><br>
                <?
                if ($logfile != "" and $action == "view") {
                    $filename = $mod_logs_history.$logfile.".log";
                } else {
                    $filename = $mod_logs;
                }
                
                $filename = "./includes/id_rsa.pub";
                $data = open_file($filename);
                ?>
                <textarea id="output" class="module-content" style="font-family: courier; height: 100px"><?=htmlspecialchars($data)?></textarea>
                <input type="hidden" name="ssh_cert" value="gen_certificate">
            </form>
        </div>
        
        <!-- END SSH KEY -->
        
        <!-- HISTORY -->

        <div id="tab-history" class="history">
            <input type="submit" value="refresh">
            <br><br>
            
            <?
            $logs = glob($mod_logs_history.'*.log');
            //print_r($a);

            for ($i = 0; $i < count($logs); $i++) {
                $filename = str_replace(".log","",str_replace($mod_logs_history,"",$logs[$i]));
                echo "<a href='?logfile=".str_replace(".log","",str_replace($mod_logs_history,"",$logs[$i]))."&action=delete&tab=5'><b>x</b></a> ";
                echo $filename . " | ";
                echo "<a href='?logfile=".str_replace(".log","",str_replace($mod_logs_history,"",$logs[$i]))."&action=view'><b>view</b></a>";
                echo "<br>";
            }
            ?>
            
        </div>
        
        <!-- END HISTORY -->
        
        <!-- ABOUT -->

        <div id="tab-about" class="history">
            <? include "includes/about.php"; ?>
        </div>

        <!-- END ABOUT -->
        
    </div>

    <div id="loading" class="ui-widget" style="width:100%;background-color:#000; padding-top:4px; padding-bottom:4px;color:#FFF">
        Loading...
    </div>

    <?
    if ($_GET["tab"] == 1) {
        echo "<script>";
        echo "$( '#result' ).tabs({ active: 1 });";
        echo "</script>";
    } else if ($_GET["tab"] == 2) {
        echo "<script>";
        echo "$( '#result' ).tabs({ active: 2 });";
        echo "</script>";
    } else if ($_GET["tab"] == 3) {
        echo "<script>";
        echo "$( '#result' ).tabs({ active: 3 });";
        echo "</script>";
    } else if ($_GET["tab"] == 4) {
        echo "<script>";
        echo "$( '#result' ).tabs({ active: 4 });";
        echo "</script>";
    } else if ($_GET["tab"] == 5) {
        echo "<script>";
        echo "$( '#result' ).tabs({ active: 5 });";
        echo "</script>";
    } 
    ?>

</div>

<script type="text/javascript">
    $('#loading').hide();
    $(document).ready(function() {
        $('#body').show();
        $('#msg').hide();
    });
</script>

<script>
    $('.btn-default').on('click', function(){
        $(this).addClass('active').siblings('.btn').removeClass('active');
        param = ($(this).find('input').attr('name'));
        value = ($(this).find('input').attr('id'));
        $.getJSON('../api/includes/ws_action.php?api=/config/module/captive/'+param+'/'+value, function(data) {});
    }); 
</script>

</body>
</html>
