CREATE TABLE `leave_workflow_detail` (
  `perform_action` varchar(100) NOT NULL,
  `do_next_step` char(1) DEFAULT 'n',
  `is_final_step` char(1) DEFAULT 'n',
  PRIMARY KEY (`perform_action`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8