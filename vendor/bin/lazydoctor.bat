@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../lazyjsonmapper/lazyjsonmapper/bin/lazydoctor
php "%BIN_TARGET%" %*
