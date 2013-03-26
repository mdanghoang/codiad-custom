<?php

/*
 * Author: mdanghoang
 * 
 * This class is used for action in Coding TestingCenter (CTC)
 */

require_once('class.shell.php');

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
        
        // Get modified files
        $this->cmd = "cd " . WORKSPACE . "/" . $this->project . " ; git ls-files --modified --exclude-standard";
        $this->execCmdWithOutput($output);
        if ($output != false) {
            foreach ($output as $line) {
                $modified_files[] = array("name"=>basename($line),"path"=>$line,"status"=>GIT_STATUS_MODIFIED);
            }
        }
        
        // Get untracked files
        $output = false;
        $this->cmd = "cd " . WORKSPACE . "/" . $this->project . " ; git ls-files --other --exclude-standard";
        $this->execCmdWithOutput($output);
        if ($output != false) {
            foreach ($output as $line) {
                $modified_files[] = array("name"=>basename($line),"path"=>$line,"status"=>GIT_STATUS_UNTRACKED);
            }
        }
        
        // Get deleted files
        $output = false;
        $this->cmd = "cd " . WORKSPACE . "/" . $this->project . " ; git ls-files --deleted --exclude-standard";
        $this->execCmdWithOutput($output);
        if ($output != false) {
            foreach ($output as $line) {
                $modified_files[] = array("name"=>basename($line),"path"=>$line,"status"=>GIT_STATUS_DELETED);
            }
        }
        
        if ($modified_files != false) {
            saveJSON(basename($diff_file_name), $modified_files);
            echo formatJSEND("success", $modified_files);
        } else {
            echo formatJSEND("error", $this->cmd);
        }
    }
    
    //////////////////////////////////////////////////////////////////
    // Commit files
    //////////////////////////////////////////////////////////////////

    public function commit($files_json,$message) {
        $this->cmd = "cd " . WORKSPACE . "/" . $this->project;
        $modif_files = json_decode($files_json);
        $files_to_add = "";
        $files_to_delete = "";
        $file_to_commit = "";
        foreach ($modif_files as $data) {
            $data = (array)$data;
            if ($data['status'] == GIT_STATUS_UNTRACKED) {
                $files_to_add = $files_to_add . $data['path'] . " ";
            }
            if ($data['status'] == GIT_STATUS_DELETED) {
                $files_to_delete = $files_to_delete . $data['path'] . " ";
            }
            $file_to_commit = $file_to_commit . $data['path'] . " ";
        }
        
        // Add new files
        if (!empty($files_to_add)) {
            $this->cmd = $this->cmd . " ; git add " . $files_to_add;
        }
        
        // Update deleted files
        if (!empty($files_to_delete)) {
            $this->cmd = $this->cmd . " ; git add -u " . $files_to_add;
        }
        
        // Commit files to local repository
        $msg = "";
        if (!is_null($message) && !empty($message)) {
            $msg = "-m \"" . $message . "\"";
        }
        $this->cmd = $this->cmd . " ; git commit " . $msg . " -- " . $file_to_commit;
        
        // Push files to remote repository
        $this->cmd = $this->cmd . " ; git push";
        $output = false;
        $this->execCmdWithOutput($output);
        debug(json_encode($output));
        
        echo formatJSEND("success", $this->cmd);
    }
        
    //////////////////////////////////////////////////////////////////
    // Deploy application
    //////////////////////////////////////////////////////////////////

    public function deployApplication() {
        echo formatJSEND("success", get_class($this) . " - deployApplication");
    }
        
    //////////////////////////////////////////////////////////////////
    // Analyze code
    //////////////////////////////////////////////////////////////////

    public function analyzeCode() {
        echo formatJSEND("success", get_class($this) . " - analyzeCode");
    }
        
}
?>
