CREATE TABLE `leave_workflow` (
  `workspace_id` int(11) NOT NULL,
  `perform_action` varchar(30) NOT NULL,
  `workflow_order` int(5) NOT NULL,
  `reject_goto_order` int(5) DEFAULT NULL,
  PRIMARY KEY (`workspace_id`,`perform_action`,`workflow_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8