@echo off

set cron=E:\PHPworkspace
set php=D:\server\php-5.3.3
set fileName=%1

IF "%fileName%" NEQ "" goto call else goto input

:input
echo ��������������������������������
echo ��Function��ִ�б���PHP�ű�BAT��
echo ��Author������                ��
echo ��Date��2014-06-05            ��
echo ��������������������������������
echo.
set /p file=��ѡ��Ҫִ���ļ���
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
choice /C YN /M �Ƿ����ִ�У�
echo.
if errorlevel 2 goto exit
if errorlevel 1 goto input

:help
echo ��ǰPHP��װ·����%php%
echo ��ǰ�ű���ȡ·����%cron%
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