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
        logCTC("DEPLOY App ============ Start");
        $out = $Shell->deployApplication();
        if ($out != false) {
            echo formatJSEND("success", "Deployed successfully");
            logCTC("Deployed successfully");
        } else {
            echo formatJSEND("error", "Deploy application failed");
            logCTC("Deployed FAILED");
        }
        logCTC("DEPLOY App ============ End");
    }

    //////////////////////////////////////////////////////////////////
    // Analyze code
    //////////////////////////////////////////////////////////////////

    if($_GET['action']=='analyze_code') {
        logCTC("ANALYZE Code ============ Start");
        $out = $Shell->analyzeCode();
        if ($out != false) {
            echo formatJSEND("success","Processed successfully - Analysis is waiting in queue");
            logCTC("Saved to queue successfully");
        } else {
            echo formatJSEND("error","Process to analyze code failed");
            logCTC("Saved to queue FAILED");
        }
        logCTC("ANALYZE Code ============ End");
    }

    //////////////////////////////////////////////////////////////////
    // Test sendmail
    //////////////////////////////////////////////////////////////////

    if($_GET['action']=='finish_exam') {
        logCTC("FINISH Exam ============ Start");
        $Shell->cmd = "D:\\Projects\\Training\\Codiad\\codiad\\components\\shell_script\\script\\testmail.bat";
        $Shell->execCmd();
        logCTC("FINISH Exam ============ End");
    }

    //////////////////////////////////////////////////////////////////
    // Check if there are files to commit
    //////////////////////////////////////////////////////////////////

    if($_GET['action']=='check_commit') {
        logCTC("CHECK files to commit ============ Start");
        $Shell->checkModifiedFiles();
        logCTC("CHECK files to commit ============ End");
    }

    //////////////////////////////////////////////////////////////////
    // Commit a file or some files
    //////////////////////////////////////////////////////////////////

    if($_GET['action']=='commit') {
        logCTC("COMMIT ============ Start");
        $out = $Shell->commit($_GET['list'], $_GET['message']);
        if ($out != false) {
            echo formatJSEND("success","Commited successfully");
            logCTC("Commited successfully");
        } else {
            echo formatJSEND("error","Commit failed");
            logCTC("Commited FAILED");
        }
        logCTC("COMMIT ============ End");
    }
?>
