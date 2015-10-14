CREATE DEFINER=`lams`@`%` PROCEDURE `sp_core_module_master_browse`()
BEGIN
	SELECT *
	FROM core_module_master;
    END