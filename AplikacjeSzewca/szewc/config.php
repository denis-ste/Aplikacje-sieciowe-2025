<?php
$conf->debug = true;

# ---- Webapp location
$conf->server_name = 'localhost';
$conf->protocol = 'http';
$conf->app_root = '/KATALOG_ZADANIA/szewc/public';
$conf->clean_urls = true;

# ---- Database config - values required by Medoo
$conf->db_type = 'mysql';
$conf->db_server = 'localhost';
$conf->db_name = 'szewc';
$conf->db_user = 'root';
$conf->db_pass = '';
$conf->db_charset = 'utf8mb4';

# ---- Database config - optional
$conf->db_port = '3307';
$conf->db_option = [
    PDO::ATTR_CASE => PDO::CASE_NATURAL,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
];
