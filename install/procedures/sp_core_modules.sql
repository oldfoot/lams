CREATE DEFINER=`lams`@`%` PROCEDURE `sp_core_modules`()
BEGIN
SELECT ModuleID, ModuleName,Description
FROM core_module_master;
    END