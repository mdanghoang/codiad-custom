<?php

/*
 * Author: mdanghoang
 * 
 * This class is used for action in Coding TestingCenter (CTC)
 */

require_once('class.shell.php');

define("SCRIPT_PATH", BASE_PATH . "/components/shell_script/script");

class CTCShell extends Shell {
    
    public $user = '';
    public $project = '';

    //////////////////////////////////////////////////////////////////
    // Construct
    //////////////////////////////////////////////////////////////////

    public function __construct($username,$projectname) {
        $this->user = $username;
        $this->project = $projectname;
    }
    
    //////////////////////////////////////////////////////////////////
    // Check modified files to commit to git repository
    // Return: 
    //     false if there is no modified files to commit
    //     array of files with relative path to be able to commit
    //////////////////////////////////////////////////////////////////

    public function checkModifiedFiles() {
        $output = false;
        $modified_files = false;
        
        $diff_file_name = DATA . "/" . $this->user . '_diff.php';
        // Remove old data if any
        if (file_exists($diff_file_name)) {
            unlink($diff_file_name);
        }
        
        $status_arr = array(GIT_STATUS_DELETED,GIT_STATUS_MODIFIED,GIT_STATUS_UNTRACKED);
        foreach ($status_arr as $stt) {
            // Get files with corresponding status in order: deleted, modified and other
            $output = false;
            $this->cmd = "cd " . WORKSPACE . "/" . $this->project . " ; git ls-files --" . $stt . " --exclude-standard";
            $this->execCmdWithOutput($output);
            if ($output != false) {
                foreach ($output as $line) {
                    // Check if this file path has been existed in file list
                    if (!isInArray("path", $line, $modified_files)) {
                        $modified_files[] = array("name"=>basename($line),"path"=>$line,"status"=>$stt);
                    }
                    
                }
            }
        }
        
        if ($modified_files != false) {
            saveJSON(basename($diff_file_name), $modified_files);
        }
        
        return $modified_files;
    }
    
    //////////////////////////////////////////////////////////////////
    // Commit files
    //////////////////////////////////////////////////////////////////

    public function commit($files_json,$message) {
        $this->cmd = "cd " . WORKSPACE . "/" . $this->project;
        $modif_files = json_decode($files_json);
        //$files_to_add = "";
        $files_to_delete = "";
        $file_to_commit = "";
        foreach ($modif_files as $data) {
            $data = (array)$data;
            if ($data['status'] == GIT_STATUS_DELETED) {
                $files_to_delete = $files_to_delete . $data['path'] . " ";
            } else {
                $files_to_add = $files_to_add . $data['path'] . " ";
            }
            
            $file_to_commit = $file_to_commit . $data['path'] . " ";
        }
        
        // Add new files
        if (!empty($files_to_add)) {
            $this->cmd = $this->cmd . " ; git add " . $files_to_add;
        }
        
        // Update deleted files
        if (!empty($files_to_delete)) {
            $this->cmd = $this->cmd . " ; git add -u " . $files_to_delete;
        }
        
        // Commit files to local repository
        $msg = "";
        if (!is_null($message) && !empty($message)) {
            $msg = "-m \"" . $message . "\"";
        }
        $this->cmd = $this->cmd . " ; git commit " . $msg . " -- " . $file_to_commit;
        
        // Push files to remote repository
        $output = false;
        $this->cmd = $this->cmd . " ; git push 2>&1";
        $this->execCmdWithOutput($output);
        
        return $output;
    }
        
    //////////////////////////////////////////////////////////////////
    // Deploy application
    //////////////////////////////////////////////////////////////////

    public function deployApplication() {
        $this->cmd = "sh " . SCRIPT_PATH . "/deploy_app_php.sh " . $this->user. " " . $this->project;
        $output = false;
        $this->execCmdWithOutput($output);
        
        return $output;
    }
        
    //////////////////////////////////////////////////////////////////
    // Analyze code
    //////////////////////////////////////////////////////////////////

    public function analyzeCode() {
        $output = false;
        // Add analysis to process queue
        if ($this->addAnalysisQueue()) {
            // Call script to launch analysis
            $this->cmd = "sh " . SCRIPT_PATH . "/analyze_code_php.sh";
            $this->execCmdInBackground();
            $output = true;
        }
        
        return $output;
    }
    
    //////////////////////////////////////////////////////////////////
    // Add data to analysis queue file
    // Return: 
    //     true = success to add project to queue or project has been in queue
    //     false = failed to save project to queue
    //////////////////////////////////////////////////////////////////

    private function addAnalysisQueue() {
        $queue_file_name = DATA . "/acphp_queue.php";
        $analyzing_process = false;
        if (file_exists($queue_file_name)) {
            $analyzing_process = getJSON(basename($queue_file_name));
            logCTC("Got process queue from file");
        }
        
        $existed = false;
        // Check if there is an existing analysis for this user and this project
        foreach ($analyzing_process as $data) {
            if ($data["user"] == $this->user && $data["project"] == $this->project) {
                $existed = true;
                break;
            }
        }
        
        if (!$existed) {
            $analyzing_process[] = array("user"=> $this->user,"project"=> $this->project);
            logCTC("User: " . $this->user . " - project: " . $this->project ." added to queue");
        }
        
        $saved = saveJSONWithLock(basename($queue_file_name), $analyzing_process);
        if (!$saved)
            logCTC ("Cannot save data to file");
        
        return ($saved || $existed);
    }
        
}
?>
