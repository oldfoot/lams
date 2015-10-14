CREATE DEFINER=`lams`@`%` PROCEDURE `sp_mq_message_complete`(IN pMQID INT(5))
BEGIN
	UPDATE mq_master SET status = 'completed' WHERE MQID = pMQID;
    END