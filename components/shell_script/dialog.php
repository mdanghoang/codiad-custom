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
    require_once('class.ctcshell.php');
    
    //////////////////////////////////////////////////////////////////
    // Verify Session or Key
    //////////////////////////////////////////////////////////////////
    
    checkSession();

    switch($_GET['action']){
    
        //////////////////////////////////////////////////////////////
        // Commit active file
        //////////////////////////////////////////////////////////////
        
        case 'load':
        
            // TODO get file list to commit to git
            $modif_files = false;
            if(file_exists(DATA . "/" . $_SESSION['user'] . '_diff.php')){ 
                $modif_files = getJSON($_SESSION['user'] . '_diff.php');
            }
            if ($modif_files == false || !is_array($modif_files) ||
                    (is_array($modif_files) && empty($modif_files))) {
            ?>
            <pre>You have nothing to commit</pre>
            <button onclick="codiad.modal.unload();return false;">Close</button>
            <?php 
            } else {
            ?>
            <script type="text/javascript">
                $(document).ready(function() {
                    /**
                     * Event checkbox check all
                     */
                    $('#table_files_to_commit tr th:first input#chkbox_commit_all').click(function() {
                        var checkedStatus = this.checked;
                        $('#table_files_to_commit tr td:first-child input:checkbox').each(function() {
                            this.checked = checkedStatus;
                        });
                    });
                    
                    /**
                     * Event click commit button
                     */
                    $('#btn_commit_files').click(function() {
                        var selectedFiles = new Array();
                        // loop each row of table_files_to_commit except header
                        $('#table_files_to_commit tr:gt(0)').each(function() {
                            if ($(this).find('td:nth-child(1) input:checkbox').is(':checked')) {
                                var fileObj = {};
                                //file path in 4th column
                                fileObj.path = $(this).find('td:nth-child(4)').html();
                                //file path in 3rd column
                                fileObj.status = $(this).find('td:nth-child(3) input').val();
                                selectedFiles.push(fileObj);
                            }
                        });
                        
                        // validate commit condition: 
                        //     - has at least one file to commit
                        //     - warning if there is no comment
                        if (selectedFiles.length <= 0) {
                            alert("Please select at least one 1 file to finish your commit.");
                            return false;
                        } else {
                            // Good: at least one file selected
                            $('#txt_selected_files').val(JSON.stringify(selectedFiles));
                            if ($.trim($('textarea[name=commit_message]').val()) === "") {
                                // No commit message: confirm ?
                                alert('You have no message for this commit.');
                            }
                        }
                    });
                });
            </script>
            <form>
            <label>Commit message</label>
            <textarea name="commit_message" autofocus="autofocus" cols="80" rows="10"/>

            <input type="hidden" name="selected_files" id="txt_selected_files" value=""/>
            <label>Files to commit</label>
            <div id="div_files_to_commit">
            <table id="table_files_to_commit" width="100%">
                <tr>
                    <th width="5"><input type="checkbox" id="chkbox_commit_all" name="chkbox" value="0"/></th>
                    <th>File</th><!-- 2nd column -->
                    <th>Status</th><!-- 3rd column -->
                    <th>Path</th><!-- 4th column -->
                </tr>

            <?php
                $checkbox_id = 0;
                foreach ($modif_files as $data) {
                    $checkbox_id++;
            ?>
                <tr>
                    <td><input type="checkbox" name="chkbox" value="<?php echo($checkbox_id); ?>"/></td>
                    <td><?php echo $data['name']; ?></td>
                    <td><?php echo \gitStatus($data['status']); ?><input type="hidden" value="<?php echo $data['status']; ?>" /></td>
                    <td><?php echo $data['path']; ?></td>
                </tr>
            <?php 
                } 
            ?>
            </table>
            </div>
            
            <button class="btn-left" id="btn_commit_files">Commit</button>
            <button class="btn-right" onclick="codiad.modal.unload();return false;">Cancel</button>
            </form>
            <?php
            }
            break;
            
    }
    
?>
        
