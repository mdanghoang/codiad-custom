/*
 *  Init shell script module
 */

(function(global, $) {

    var codiad = global.codiad;

    codiad.shellScript = {
        controller: 'components/shell_script/controller.php',
        dialog: 'components/shell_script/dialog.php',
        
        //////////////////////////////////////////////////////////////////
        // Analyze source code
        //////////////////////////////////////////////////////////////////

        analyzeCode: function() {
            // Run controller to analyze source code of client
            $.get(this.controller + '?action=analyze_code', function(data) {
                var analyzeReponse = codiad.jsend.parse(data);
                if (analyzeReponse !== 'error') {
                    $.msgBox({
                        title:i18n("Information"),
                        content:i18n("Your request to analyze code is in processing. You will receive the analysis result by mail."),
                        type:"info"
                    });
                    codiad.message.success(i18n(analyzeReponse));
                } else {
                    $.msgBox({
                        title:i18n("Too many requests in progress"),
                        content:i18n("Too many requests of analyzing code is in processing. Please retry in few minutes later."),
                        type:"error"
                    });
                }
            });

        },
        //////////////////////////////////////////////////////////////////
        // Deploy application
        //////////////////////////////////////////////////////////////////

        deployApp: function() {
            $.msgBox({
                title:i18n("Information"),
                content:i18n("Your request to deploy application is in processing. You will receive deployment result by mail."),
                type:"info"
            });
            // Run controller to deploy application
            $.get(this.controller + '?action=deploy_app', function(data) {
                var deployReponse = codiad.jsend.parse(data);
                if (deployReponse !== 'error') {
                    codiad.message.success(i18n(deployReponse));
                }
            });
        },

        //////////////////////////////////////////////////////////////////
        // Finish exam
        //////////////////////////////////////////////////////////////////

        finishExam: function() {
            $.msgBox({
                title:i18n("Information"),
                content:i18n("This function is not available in this version"),
                type:"info"
            });
            // Run controller to finish exam
            //$.get(this.controller + '?action=finish_exam', function(data) {
            //    var testResponse = codiad.jsend.parse(data);
            //    testResponse = testResponse.join('\n');
            //    if (testResponse !== 'error') {
            //        alert(testResponse.toString());
            //        codiad.message.success('Exam finished');
            //    } else {
            //        codiad.message.error('ERROR');
            //    }
            //});
        },

        //////////////////////////////////////////////////////////////////
        // Commit active file
        //////////////////////////////////////////////////////////////////

        commit: function() {
            var _this = this;
            $.get(this.controller+'?action=check_commit');
            codiad.modal.load(600, this.dialog + '?action=load');
            $('#modal-content form').live('submit', function(e) {
                e.preventDefault();
                var selectedFiles = $('#txt_selected_files').val();
                var commitMessage = $.trim($('textarea[name=commit_message]').val());
                $.get(_this.controller + '?action=commit&list=' + selectedFiles + '&message=' + commitMessage, function(data) {
                    var commitResponse = codiad.jsend.parse(data);
                    if (commitResponse !== 'error') {
                        codiad.message.success(i18n(commitResponse));
                    }
                });
                codiad.modal.unload();
            });
        },

        //////////////////////////////////////////////////////////////////
        // Test command line from GUI
        //////////////////////////////////////////////////////////////////

        test: function() {
            // Run controller to test command line
            $.get(this.controller + '?action=test_command', function(data) {
                var testResponse = codiad.jsend.parse(data);
                testResponse = testResponse.join('\n');
                if (testResponse !== 'error') {
                    $.msgBox({
                        title:"Information",
                        content:testResponse.toString(),
                        type:"info"
                    });
                    
                    codiad.message.success('Run command with success');
                } else {
                    codiad.message.error('ERROR');
                }
            });
        }
    };

})(this, jQuery);
