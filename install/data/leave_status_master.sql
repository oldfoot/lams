insert  into `leave_status_master`(`status_id`,`status_name`,`is_new`,`is_new_default`,`is_approved`,`is_approved_default`,`is_rejected`,`is_rejected_default`,`is_deleted`,`is_deleted_default`,`workspace_id`,`teamspace_id`,`allow_edit`) values (1,'New','y','y','n','n','n','n','n','n',1,NULL,'y'),(2,'Rejected','n','n','n','n','y','n','n','n',1,NULL,'n'),(3,'Approved','n','n','y','n','n','n','n','n',1,NULL,'n'),(4,'Deleted','n','n','n','n','n','n','y','n',1,NULL,'n'),(12,'New','y','y','n','n','n','n','n','n',3,NULL,'n'),(13,'Completed','n','n','n','n','y','n','n','n',3,NULL,'n'),(14,'Rejected','n','n','n','n','n','n','y','n',3,NULL,'n'),(15,'Deleted','n','n','n','n','n','n','n','n',3,NULL,'n'),(16,'Approved','n','n','y','n','n','n','n','n',3,NULL,'n'),(17,'New','y','y','n','n','n','n','n','n',17,NULL,'n'),(18,'Pending','n','n','n','n','n','n','n','n',17,NULL,'n'),(19,'Approved','n','n','y','n','y','n','n','n',17,NULL,'n'),(20,'New','y','y','n','n','n','n','n','n',24,0,'n'),(21,'Rejected','n','n','n','n','y','y','n','n',24,0,'n');