@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../dolejska-daniel/riot-api/src/LeagueAPICLI/leagueapicli
php "%BIN_TARGET%" %*
