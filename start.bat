@echo off
cd /d "C:\xampp\htdocs\company-management-system"
start "" "http://localhost:8000/login"
C:\xampp\php\php.exe -S localhost:8000 index.php
