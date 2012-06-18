<?php

$doworkflow = array(
    'project_employee_request_email_address' => 35,
    'project_employee_request_web_access' => 36,
    'project_employee_request_comm_query' => 37,
    'project_employee_request_merlin_access' => 36
);

$config = array(
    'folders' => array(
        'include' => 'include/',
        'sitecode' => './',
        'public' => 'public/'
    ),
    'errorpages' => array(
        'DB_CONNECT_FAILED' => 'public/closed.dbdown.html',
        'CLOSED_FOR_MAINTENANCE' => 'public/closed.maintenance.html'
    ),
    'CLOSED' => 0, // 0 - open, 1 - closed
    'DEBUG' => false,
    'DEBUG_MAILSCRIPT' => 1,
    'DEBUGLEVEL' => array(
        'CTextProcessor' => 0,
        'dotmod' => 0,
        'looptmod' => 0,
    ),
    'DEFAULTPAGE' => 'main/home',
    'TITLEBASE' => 'Tickets',
    'ENV' => 'TEST',
    'PROFILER' => 1, # 1 forces profiler info to be printed (commented in PROD, on-screen in TEST)
    'PROFILER_THRESHOLD' => 0.5, # time in seconds for allowed duration not to be recorded into profiler table; 0 forces to save everything
    'PROFILER_THRESHOLD_SQL' => 0.05, # time in seconds for allowed query execution duration not to be recorded into profiler table; 0 forces to save everything
    'VERSION' => '1.15',
    'UNAUTHORIZED_INLINE' => '../images/unauthorized.inline.html',
    'DEFAULT_ACTION' => 'main/dashboard',
    'DEFAULT_URL_EXTERNAL' => 'index.pl?p=dashboard/notifications', #make sure it exists to avoid eternal loop
    'DEFAULT_URL_INTERNAL' => 'index.pl?p=main/dashboard', #make sure it exists to avoid eternal loop
    'DEFAULT_CLIENT_ACTION' => 'clients/welcome', #make sure it exists to avoid eternal loop
    'DEFAULT_CLIENT_URL' => 'client.pl?p=clients/welcome', #make sure it exists to avoid eternal loop
    'DIRUPLOAD' => "c:\\apache_data\\intranet_pictures",
    'URLPICTURES' => '/intranet_pictures',
    'ROOT_PATH_SCRIPTS' => 'c:\apache2\test\john\scripts',
    'ROOT_PATH_IMAGES' => 'c:\apache2\test\john\images',
    'ROOT_PATHFIX' => '../../..',
    'SERVERURL_LOCAL' => 'http://172.26.0.7:8080',
    'SERVERURL_EXTERNAL' => 'http://195.76.4.166:8080',
    'BASEURL_SCRIPTS' => '/test/john/scripts',
    'BASEURL_IMAGES' => '/test/john/images',
    'BASEURL_PICTURES' => '/intranet_pictures',
    'INDEX_SCRIPT_URL' => '/index.pl',
    'CENTRAL_SCRIPT_URL' => '/test/cgi-bin/central.pl',
    'CLUBABSOLUTERU_URL' => 'http://www.clubabsolute.ru/v3/index.php',
    'SENDMAIL' => 0,
    'SENDMAIL_FROM' => 'mailrobot@absolutemail.net',
    'SENDMAIL_SMTPSERVER' => 'mail.clubabsolute.com',
    'DEFAULT_DASHBOARD_DEFAULTVIEW' => 1,
    'DEFAULT_DASHBOARD_ARTICLEAGE' => 30,
    'DEFAULT_DASHBOARD_NOTIFICATIONAGE' => 2,
    'DEFAULT_DASHBOARD_OFFICELOCATION' => 1,
    'doworkflow' => $doworkflow,
);
?>
