<?php

/*
*  Copyright (c) Codiad & Kent Safranski (codiad.com), distributed
*  as-is and without warranty under the MIT License. See
*  [root]/license.txt for more. This information must remain intact.
*/

class Shell {

    //////////////////////////////////////////////////////////////////
    // PROPERTIES
    //////////////////////////////////////////////////////////////////

    public $cmd     = '';
    public $args    = '';

    //////////////////////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////////////////////

    // -----------------------------||----------------------------- //

    //////////////////////////////////////////////////////////////////
    // Construct
    //////////////////////////////////////////////////////////////////

    public function __construct() {
	    //$cmd = 'ls -al';
        $this->cmd = 'dir C:\\';
    }

    //////////////////////////////////////////////////////////////////
    // Authenticate
    //////////////////////////////////////////////////////////////////

    public function ExecCmd() {
	    //$output = "Win command";
        //if (substr(php_uname(), 0, 7) == "Windows"){
        //    pclose(popen($this->cmd, "r"));
        //} else { 
        //    $output = exec($this->cmd);
        //}
		exec($this->cmd,$output);
		echo formatJSEND("success",$output);
    }

}
