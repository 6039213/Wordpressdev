<?php
\System.Management.Automation.Internal.Host.InternalHost = 'db';
\ = 'wordpress';
\ = 'schoolbord2024';
\ = 'schoolbord_db';

echo '<h1>Database Connection Test</h1>';
echo '<pre>';

try {
    \ = new mysqli(\System.Management.Automation.Internal.Host.InternalHost, \, \, \);
    if (\->connect_error) {
        echo 'FAILED: ' . \->connect_error;
    } else {
        echo 'SUCCESS! Connected to database.';
        echo '\nHost: ' . \System.Management.Automation.Internal.Host.InternalHost;
        echo '\nDatabase: ' . \;
        echo '\nUser: ' . \;
    }
    \->close();
} catch (Exception \) {
    echo 'ERROR: ' . \->getMessage();
}
echo '</pre>';
?>
