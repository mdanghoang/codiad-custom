<?php

    /*
    *  Copyright (c) Codiad & Kent Safranski (codiad.com), distributed
    *  as-is and without warranty under the MIT License. See 
    *  [root]/license.txt for more. This information must remain intact.
    * 
    *  This dialog is used for all action commit source code
    * 
    */

    require_once('../../config.php');
    
    //////////////////////////////////////////////////////////////////
    // Verify Session or Key
    //////////////////////////////////////////////////////////////////
    
    checkSession();

    switch($_GET['action']){
    
        //////////////////////////////////////////////////////////////
        // Commit active file
        //////////////////////////////////////////////////////////////
        
        case 'commit':
        
            // TODO get file list to commit to git
            $modif_files = false;
            if(!file_exists(BASE_PATH . "/data/" . $_SESSION['user'] . '_diff.php')){ 
            ?>
            <pre>You have nothing to commit</pre>
            <button onclick="codiad.modal.unload();return false;">Close</button>
            <?php 
            } else {
                $modif_files = getJSON($_SESSION['user'] . '_diff.php');
            ?>
<script type="text/javascript">
        $(document).ready(function() {
            $("#table_files_to_commit tr th:first input#chkbox_commit_all").click(function() {
                var checkedStatus = this.checked;
                $("#table_files_to_commit tr td:first-child input:checkbox").each(function() {
                    this.checked = checkedStatus;
                });
            });
        });
</script>
            <form>
            <label>Commit message</label>
            <textarea name="comment" autofocus="autofocus" cols="80" rows="10"/>

            <label>Files to commit</label>
            <div id="div_files_to_commit">
            <table id="table_files_to_commit" width="100%">
                <tr>
                    <th width="5"><input type="checkbox" id="chkbox_commit_all" name="chkbox" value="0"/></th>
                    <th>File</th>
                    <th>Path</th>
                </tr>

            <?php
                $checkbox_id = 0;
                foreach ($modif_files as $file=>$data) {
                    $checkbox_id++;
            ?>
                <tr>
                    <td><input type="checkbox" id="chkbox_commit_<?php echo($checkbox_id); ?>" name="chkbox" value="<?php echo($checkbox_id); ?>"/></td>
                    <td><?php echo($data['name']); ?></td>
                    <td><?php echo($data['path']); ?></td>
                </tr>
            <?php 
                } 
            ?>
            </table>
            </div>
            
            <button class="btn-left">Commit</button>
            <button class="btn-right" onclick="codiad.modal.unload();return false;">Cancel</button>
            </form>
            <?php
            }
            break;
            
    }
    
?>
        
