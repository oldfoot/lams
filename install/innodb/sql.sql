alter table `genus`.`leave_application_attachments` add foreign key `FK_leave_application_attachments`(`application_id`) references `leave_applications` (`application_id`) on delete cascade  on update cascade
alter table `genus`.`leave_application_approval` add foreign key `FK_leave_application_approval`(`application_id`) references `leave_applications` (`application_id`) on delete cascade  on update cascade
alter table `genus`.`leave_user_balances` add foreign key `FK_leave_user_balances`(`period_id`) references `leave_period_master` (`period_id`) on delete cascade  on update cascade
alter table `genus`.`leave_user_settings` add foreign key `FK_leave_user_settings`(`period_id`) references `leave_period_master` (`period_id`) on delete cascade  on update cascade
alter table `genus`.`leave_category_balance` add foreign key `FK_leave_category_balance`(`period_id`) references `leave_period_master` (`period_id`) on delete cascade  on update cascade
alter table `genus`.`leave_applications` add foreign key `FK_leave_applications`(`period_id`) references `leave_period_master` (`period_id`) on delete cascade  on update cascade
alter table `genus`.`leave_category_balance` add foreign key `FK_leave_category_balance`(`category_id`) references `leave_category_master` (`category_id`) on delete cascade  on update cascade
alter table `genus`.`leave_applications` add foreign key `FK_leave_applications`(`category_id`) references `leave_category_master` (`category_id`) on delete cascade  on update cascade
