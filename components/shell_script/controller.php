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
    // Deploy application
    // At this moment, function is used only for deploying php application
    // TODO deploy java application
    //////////////////////////////////////////////////////////////////

    if($_GET['action']=='deploy_app') {
        logCTC("Action DEPLOY App ============ Start");
        $out = $Shell->deployApplication();
        if ($out != false) {
            echo formatJSEND("success","Deployed successfully");
        } else {
            echo formatJSEND("error","Deploy application failed");
        }
        logCTC("Action DEPLOY App ============ End");
    }

    //////////////////////////////////////////////////////////////////
    // Analyze code
    //////////////////////////////////////////////////////////////////

    if($_GET['action']=='analyze_code') {
        logCTC("Action ANALYZE Code ============ Start");
        $out = $Shell->analyzeCode();
        if ($out != false) {
            echo formatJSEND("success","Analyzed code successfully");
        } else {
            echo formatJSEND("error","Analyze code failed");
        }
        logCTC("Action ANALYZE Code ============ End");
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
        $out = $Shell->commit($_GET['list'], $_GET['message']);
        if ($out != false) {
            echo formatJSEND("success","Commited successfully");
        } else {
            echo formatJSEND("error","Commit failed");
        }
        logCTC("Action COMMIT ============ End");
    }
?>
