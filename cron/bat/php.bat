@echo off

set cron=E:\PHPworkspace
set php=D:\server\php-5.3.3
set fileName=%1

IF "%fileName%" NEQ "" goto call else goto input

:input
echo ┌──────────────┐
echo │Function：执行本地PHP脚本BAT│
echo │Author：火子                │
echo │Date：2014-06-05            │
echo └──────────────┘
echo.
set /p file=请选择要执行文件：
echo.
IF "%file%"=="help" goto help
IF "%file%"=="" goto notInput
set fileName = %cron%\%file%
IF NOT EXIST %fileName% goto notExist else goto php

:php
echo ---------------------
echo cron:begining
%php%\php %fileName%
echo.
echo cron:end.
echo ---------------------
goto input

:choice
echo.
choice /C YN /M 是否继续执行：
echo.
if errorlevel 2 goto exit
if errorlevel 1 goto input

:help
echo 当前PHP安装路径：%php%
echo 当前脚本读取路径：%cron%
goto choice

:notExist
echo file:%cron%\%file% does not exist.
goto choice

:call
%php%\php %fileName%
echo.
goto end

:notInput
echo pleace input fileName
goto choice

:exit
echo See You..

:end
pause