@echo off
title Rechner nach OpenAudit Classic inventarisieren - bitte Zielhost in audit.config eintragen
@cscript audit.vbs .
@ping -n 6 localhost> nul
rem pause