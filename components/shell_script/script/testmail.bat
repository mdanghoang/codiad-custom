@echo off
@rem ***************************************************************
@rem ** Author: DANG Hoang Minh 
@rem **
@rem ** testmail.bat
@rem **
@rem ** This script is used to report daily meeting everyday:
@rem ** sendmail with report attached.
@rem **
@rem ** Usage: dailyreport
@rem **
@rem ***************************************************************

@echo off
set mail_from=mdanghoang@pentalog.fr
set mail_to=testcloudvf@gmail.com
set mail_cc=testcloudvf+cc@gmail.com
set mail_subject=[Test mail] Candidate finished the exam %date:~10,4%%date:~4,2%%date:~7,2%

@rem ** Here is the message body
setlocal EnableDelayedExpansion
set mail_msg=Hello,^

^

I have finished my exam, please check.^

^

This is a test mail.^

^

Thanks,^

Minh

set mail_server=webmail.pentalog.fr
set auth_user=testforcloud@pentalog.fr
set auth_pwd=Pentalog123

set report_path="C:\Users\mdanghoang\Downloads\jenkins_result.jpg"

D:\Programs\sendEmail\sendEmail -f %mail_from% -t %mail_to% -cc %mail_cc% -u %mail_subject% -m !mail_msg! -s %mail_server% -xu %auth_user% -xp %auth_pwd% -a %report_path%

@rem ** End of script