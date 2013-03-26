<?php

/*
*  Copyright (c) Codiad & Kent Safranski (codiad.com), distributed
*  as-is and without warranty under the MIT License. See
*  [root]/license.txt for more. This information must remain intact.
*
*
*  This class is used for executing command line
*/

class Shell {

    //////////////////////////////////////////////////////////////////
    // PROPERTIES
    //////////////////////////////////////////////////////////////////

    public $cmd  = '';
    public $args = '';

    //////////////////////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////////////////////

    // -----------------------------||----------------------------- //

    //////////////////////////////////////////////////////////////////
    // Construct
    //////////////////////////////////////////////////////////////////

    public function __construct() {
        if (substr(php_uname(), 0, 7) == "Windows") {
            $this->cmd = 'dir C:\\';
        } else {
            $this->cmd = 'ls -al';
        }
        
    }

    //////////////////////////////////////////////////////////////////
    // Execute command with output
    //////////////////////////////////////////////////////////////////

    public function execCmdWithOutput(&$output) {
        logCTC("Running command: " . $this->cmd);
        //exec
        if(function_exists('exec')){
            exec($this->cmd, $output);
        }
        // system
        else if(function_exists('system')){
            ob_start();
            system($this->cmd);
            ob_end_clean();
        }
        //passthru
        else if(function_exists('passthru')){
            ob_start();
            passthru($this->cmd);
            ob_end_clean();
        }
        //shell_exec
        else if(function_exists('shell_exec')){
            shell_exec($this->cmd);
        }
        
        if (!is_null($output) && !empty($output)) {
            logCTC(\implode(PHP_EOL,$output));
        }
    }

    //////////////////////////////////////////////////////////////////
    // Execute command
    //////////////////////////////////////////////////////////////////

    public function execCmd() {
        $output = false;
        $this->execCmdWithOutput($output);
        return $output;
    }
}
