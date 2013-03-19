/*
 *  Init shell script module
 */

(function(global, $){

    var codiad = global.codiad;

    codiad.shellScript = {
        controller: 'components/shell_script/controller.php',
		
        //////////////////////////////////////////////////////////////////
        // Analyze source code
        //////////////////////////////////////////////////////////////////

        analyzeCode: function() {

            // Run controller to analyze source code of client
            $.get(this.controller + '?action=analyze_code', function(data) {
            });

        },
		
        //////////////////////////////////////////////////////////////////
        // Deploy application
        //////////////////////////////////////////////////////////////////

        deployApp: function() {
            alert('Deploy application in progress. You will receivce deployment result by mail.');
            // Run controller to deploy application
            $.get(this.controller + '?action=deploy_app', function(data) {
                var testResponse = codiad.jsend.parse(data);
                if (testResponse != 'error') {
				    alert(testResponse.toString());
                    codiad.message.success(testResponse.toString());
                } else {
				    codiad.message.error('ERROR');
				}
            });
        },
		
        //////////////////////////////////////////////////////////////////
        // Finish exam
        //////////////////////////////////////////////////////////////////

        finishExam: function() {
            alert('Finish exam.');
            // Run controller to finish exam
            $.get(this.controller + '?action=finish_exam', function(data) {
                var testResponse = codiad.jsend.parse(data);
				testResponse = testResponse.join('\n');
                if (testResponse != 'error') {
				    alert(testResponse.toString());
                    codiad.message.success('Exam finished');
                } else {
				    codiad.message.error('ERROR');
				}
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
                if (testResponse != 'error') {
				    alert(testResponse.toString());
                    codiad.message.success('Run command with success');
                } else {
				    codiad.message.error('ERROR');
				}
            });
        }
    }

})(this, jQuery);
