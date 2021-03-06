<?php
    /*
    *  Copyright (c) Codiad & Kent Safranski (codiad.com), distributed
    *  as-is and without warranty under the MIT License. See
    *  [root]/license.txt for more. This information must remain intact.
    */
    
    /* The stack of debug messages. */
    $debugMessageStack = array();
    
    //////////////////////////////////////////////////////////////////
    // Log debug message
    // Messages will be displayed in the console when the response is 
    // made with the formatJSEND function.
    //////////////////////////////////////////////////////////////////
    
    function debug($message) {
        global $debugMessageStack;
        $debugMessageStack[] = $message;
    }
    
    //////////////////////////////////////////////////////////////////
    // Localization
    //////////////////////////////////////////////////////////////////
    
    if (isset($_SESSION['lang'])) {
        include BASE_PATH."/languages/{$_SESSION['lang']}.php";
    } else {
        include BASE_PATH."/languages/en.php";
    }
    
    function i18n($key) {
        echo get_i18n($key);
    }
    
    function get_i18n($key) {
        global $lang;
        $key = ucwords(strtolower($key)); //Test, test TeSt and tESt are exacly the same
        return isset($lang[$key]) ? $lang[$key] : $key;
    }
    
    //////////////////////////////////////////////////////////////////
    // Check Session / Key
    //////////////////////////////////////////////////////////////////

    function checkSession(){
        // Set any API keys
        $api_keys = array();
        // Check API Key or Session Authentication
        $key = "";
        if(isset($_GET['key'])){ $key = $_GET['key']; }
        if(!isset($_SESSION['user']) && !in_array($key,$api_keys)){
            exit('{"status":"error","message":"Authentication Error"}');
        }
    }

    //////////////////////////////////////////////////////////////////
    // Get JSON
    //////////////////////////////////////////////////////////////////

    function getJSON($file,$namespace=""){
        $path = BASE_PATH . "/data/";
        if($namespace != ""){
            $path = $path . $namespace . "/";
            $path = preg_replace('#/+#','/',$path);
        }
        
        $json = file_get_contents($path . $file);
        $json = str_replace("|*/?>","",str_replace("<?php/*|","",$json));
        $json = json_decode($json,true);
        return $json;
    }

    //////////////////////////////////////////////////////////////////
    // Save JSON
    //////////////////////////////////////////////////////////////////

    function saveJSON($file,$data,$namespace=""){
        $path = BASE_PATH . "/data/";
        if($namespace != ""){
            $path = $path . $namespace . "/";
            $path = preg_replace('#/+#','/',$path);
            if(!is_dir($path)) mkdir($path);
        }
        
        $data = "<?php/*|" . json_encode($data) . "|*/?>";
        $write = fopen($path . $file, 'w') or die("can't open file");
        fwrite($write, $data);
        fclose($write);
    }

    //////////////////////////////////////////////////////////////////
    // Save JSON with lock file
    // If file is locked, wait for max 1 second
    //////////////////////////////////////////////////////////////////
    
    define("MAX_OPEN_FILE_RETRY_NUMBER", 10);
    // period (value in second) between each open file retry
    define("PERIOD_BETWEEN_OPEN_FILE", 1);

    function saveJSONWithLock($file,$data,$namespace=""){
        $path = BASE_PATH . "/data/";
        if($namespace != ""){
            $path = $path . $namespace . "/";
            $path = preg_replace('#/+#','/',$path);
            if(!is_dir($path)) mkdir($path);
        }
        
        $save_success = false;
        $retry_number = 0;
        $write = fopen($path . $file, 'w');
        while (!$save_success && $retry_number < MAX_OPEN_FILE_RETRY_NUMBER) {
            if (flock($write, LOCK_EX)) {
                // lock file with success => write data
                $data = "<?php/*|" . json_encode($data) . "|*/?>";
                fwrite($write, $data);
                flock($write, LOCK_UN);
                $save_success = true;
            } else {
                // lock file failed => another is writing file => wait 1s and retry
                sleep(PERIOD_BETWEEN_OPEN_FILE);
            }
            $retry_number ++;
        }
        fclose($write);
        
        return $save_success;
    }

    //////////////////////////////////////////////////////////////////
    // Format JSEND Response
    //////////////////////////////////////////////////////////////////

    function formatJSEND($status,$data=false){

        /// Debug /////////////////////////////////////////////////
        global $debugMessageStack;
        $debug = "";
        if(count($debugMessageStack) > 0) {
            $debug .= ',"debug":';
            $debug .= json_encode($debugMessageStack);
        }

        // Success ///////////////////////////////////////////////
        if($status=="success"){
            if($data){
                $jsend = '{"status":"success","data":'.json_encode($data).$debug.'}';
            }else{
                $jsend = '{"status":"success","data":null'.$debug.'}';
            }

        // Error /////////////////////////////////////////////////
        }else{
            $jsend = '{"status":"error","message":"'.$data.'"'.$debug.'}';
        }

        // Return ////////////////////////////////////////////////
        return $jsend;

    }
    
    //////////////////////////////////////////////////////////////////
    // Check Function Availability
    //////////////////////////////////////////////////////////////////

    function isAvailable($func) {
        if (ini_get('safe_mode')) return false;
        $disabled = ini_get('disable_functions');
        if ($disabled) {
            $disabled = explode(',', $disabled);
            $disabled = array_map('trim', $disabled);
            return !in_array($func, $disabled);
        }
        return true;
    }

    define("GIT_STATUS_MODIFIED","modified");
    define("GIT_STATUS_UNTRACKED","other");
    define("GIT_STATUS_DELETED","deleted");

    function gitStatus($status) {
        $ret = "Unknown";
        switch ($status) {
            case GIT_STATUS_MODIFIED:
                $ret = "Modified";
                break;

            case GIT_STATUS_UNTRACKED:
                $ret = "Untracked";
                break;

            case GIT_STATUS_DELETED:
                $ret = "Deleted";
                break;

            default:
                break;
        }
        return $ret;
    }
    
    define("GIT_FOLDER",".git");
    function isGitFolder($path) {
        return (basename($path) == GIT_FOLDER);
    }
    
    //////////////////////////////////////////////////////////////////
    // Check if a key/value exist in a 2 dimensions array
    // Return: 
    //     false if not exist
    //     true if exist
    //////////////////////////////////////////////////////////////////
    function isInArray($key,$val,$array) {
        $ret = false;
        foreach ($array as $item) {
            if (isset($item[$key]) && $item[$key] == $val) {
                $ret = true;
                break;
            }
        }
        return $ret;
    }

    //////////////////////////////////////////////////////////////////
    // Log message with following format
    //   [Y-m-d H:i:s {session id} {client ip}] ===> message content
    //////////////////////////////////////////////////////////////////
    function logCTC($message) {
        $path = DATA . "/";
        $file = "ctc.log";
        $write = fopen($path . $file, 'a') or die("can't open file");
        $preline = "[" . \date("Y-m-d H:i:s") . " " . \session_id() . " " . $_SERVER['REMOTE_ADDR'] . "] ===> ";
        fwrite($write, $preline . $message . PHP_EOL);
        fclose($write);
    }

?>
