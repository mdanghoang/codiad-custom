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
        logCTC("Action DEPLOY App ============ Start");
        echo formatJSEND("success",$_SESSION['user'].' - '.$_SESSION['project']);
        logCTC("Action DEPLOY App ============ End");
    }

    //////////////////////////////////////////////////////////////////
    // Test sendmail
    //////////////////////////////////////////////////////////////////

    if($_GET['action']=='finish_exam') {
        logCTC("Action FINISH Exam ============ Start");
        $Shell->cmd = "D:\\Projects\\Training\\Codiad\\codiad\\components\\shell_script\\script\\testmail.bat";
        $Shell->execCmd();
        logCTC("Action FINISH Exam ============ End");
    }

    //////////////////////////////////////////////////////////////////
    // Check if there are files to commit
    //////////////////////////////////////////////////////////////////

    if($_GET['action']=='check_commit') {
        logCTC("Action CHECK files to commit ============ Start");
        $Shell->checkModifiedFiles();
        logCTC("Action CHECK files to commit ============ End");
    }

    //////////////////////////////////////////////////////////////////
    // Commit a file or some files
    //////////////////////////////////////////////////////////////////

    if($_GET['action']=='commit') {
        logCTC("Action COMMIT ============ Start");
        $Shell->commit($_GET['list'], $_GET['message']);
        logCTC("Action COMMIT ============ End");
    }
?>
