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
        if (substr(php_uname(), 0, 7) == "Windows"){
            $this->cmd = 'dir C:\\';
        } else { 
            $this->cmd = 'ls -al';
        }
        
    }

    //////////////////////////////////////////////////////////////////
    // Execute command
    //////////////////////////////////////////////////////////////////

    public function ExecCmd() {
		exec($this->cmd,$output);
		echo formatJSEND("success",$output);
    }

}
