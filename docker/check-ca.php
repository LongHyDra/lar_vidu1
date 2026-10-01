<?php
$caPath = getenv('MYSQL_ATTR_SSL_CA');
if (!empty($caPath)) {
    if (!file_exists($caPath) || !is_readable($caPath)) {
        fwrite(STDERR, "SSL CA path is invalid or unreadable: {$caPath}\n");
        exit(1);
    }
}
exit(0);