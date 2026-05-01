@echo off
cd /d "c:\Users\USER\Desktop\cmd\company-management-system"
start "" "http://127.0.0.1:8000/login"
C:\xampp\php\php.exe -S 0.0.0.0:8000 router.php
