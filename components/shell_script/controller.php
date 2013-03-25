<?php

    /*
    *  Copyright (c) Codiad & Kent Safranski (codiad.com), distributed
    *  as-is and without warranty under the MIT License. See
    *  [root]/license.txt for more. This information must remain intact.
    *
    *
    *  This controller is used for commit code to git, analyze code,
    *  deploy application
    */

    require_once('../../config.php');
    require_once('class.ctcshell.php');
	
    $Shell = new CTCShell($_SESSION['user'],$_SESSION['project']);

    //////////////////////////////////////////////////////////////////
    // Test call command line from GUI in Codiad
    //////////////////////////////////////////////////////////////////

    if($_GET['action']=='test_command') {
        $Shell->cmd = "dir D:\\";
        $Shell->execCmd();
    }

    //////////////////////////////////////////////////////////////////
    // Test show user and project
    //////////////////////////////////////////////////////////////////

    if($_GET['action']=='deploy_app') {
        echo formatJSEND("success",$_SESSION['user'].' - '.$_SESSION['project']);
    }

    //////////////////////////////////////////////////////////////////
    // Test sendmail
    //////////////////////////////////////////////////////////////////

    if($_GET['action']=='finish_exam') {
        $Shell->cmd = "D:\\Projects\\Training\\Codiad\\codiad\\components\\shell_script\\script\\testmail.bat";
        $Shell->execCmd();
    }

    //////////////////////////////////////////////////////////////////
    // Check if there are files to commit
    //////////////////////////////////////////////////////////////////

    if($_GET['action']=='check_commit') {
        $Shell->checkModifiedFiles();
    }

    //////////////////////////////////////////////////////////////////
    // Commit a file or some files
    //////////////////////////////////////////////////////////////////

    if($_GET['action']=='commit') {
        $Shell->commit($_GET['list'], $_GET['message']);
    }
?>
