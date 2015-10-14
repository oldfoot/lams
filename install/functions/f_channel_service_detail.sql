CREATE DEFINER=`root`@`localhost` FUNCTION `f_channel_service_detail`(serviceid int(5), detailid int(5)) RETURNS varchar(50) CHARSET latin1
    DETERMINISTIC
BEGIN
	IF serviceid = 2 THEN
		SELECT video_name INTO @str FROM video_master WHERE video_id = detailid;
	ELSEIF serviceid = 3 THEN
		SELECT graphic_name  INTO @str FROM graphic_master WHERE graphic_id = detailid;
	ELSEIF serviceid = 7 THEN
		SELECT description INTO @str FROM rss_master WHERE rss_id = detailid;
	ELSEIF serviceid = 8 THEN
		SELECT description INTO @str FROM rss_master WHERE rss_id = detailid;
	ELSEIF serviceid = 12 THEN
		SELECT description INTO @str FROM twitter_master WHERE tweet_id = detailid;
	ELSEIF serviceid = 14 THEN
		SELECT category_name INTO @str FROM announcement_categories WHERE category_id = detailid;
	ELSEIF serviceid = 18 THEN
		SELECT sms_msg INTO @str FROM sms_messages WHERE id = detailid;
	ELSEIF serviceid = 19 THEN
		SELECT message INTO @str FROM announcements WHERE id = detailid;
	END IF;
	return @str;
    END