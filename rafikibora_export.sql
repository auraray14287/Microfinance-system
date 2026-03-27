SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `activity_log`;
CREATE TABLE `activity_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint(20) unsigned DEFAULT NULL,
  `log_name` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `subject_type` varchar(255) DEFAULT NULL,
  `event` varchar(255) DEFAULT NULL,
  `subject_id` bigint(20) unsigned DEFAULT NULL,
  `causer_type` varchar(255) DEFAULT NULL,
  `causer_id` bigint(20) unsigned DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`properties`)),
  `batch_uuid` char(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject` (`subject_type`,`subject_id`),
  KEY `causer` (`causer_type`,`causer_id`),
  KEY `activity_log_log_name_index` (`log_name`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `activity_log` VALUES ('1',NULL,'default','created','App\\Models\\User','created','1',NULL,NULL,'[]',NULL,'2026-02-22 00:09:02','2026-02-22 00:09:02',NULL);
INSERT INTO `activity_log` VALUES ('2',NULL,'default','updated','App\\Models\\User','updated','1',NULL,NULL,'[]',NULL,'2026-02-22 00:09:02','2026-02-22 00:09:02',NULL);
INSERT INTO `activity_log` VALUES ('3',NULL,'default','created','App\\Models\\Payments','created','1',NULL,NULL,'{\"attributes\":{\"id\":1,\"organization_id\":1550434,\"payer_id\":1,\"payment_amount\":\"0\",\"description\":null,\"payment_made_at\":\"2026-02-22 00:09:02\",\"payment_expires_at\":\"2026-02-28T22:09:02.000000Z\",\"transaction_reference\":\"499625\",\"gateway\":\"7 DAYS FREE TRIAL\",\"created_at\":\"2026-02-21T22:09:02.000000Z\",\"updated_at\":\"2026-02-21T22:09:02.000000Z\",\"branch_id\":null}}',NULL,'2026-02-22 00:09:02','2026-02-22 00:09:02',NULL);
INSERT INTO `activity_log` VALUES ('4','1550434','default','updated','App\\Models\\User','updated','1','App\\Models\\User','1','[]',NULL,'2026-02-22 00:13:50','2026-02-22 00:13:50',NULL);
INSERT INTO `activity_log` VALUES ('5','1550434','default','updated','App\\Models\\User','updated','1','App\\Models\\User','1','[]',NULL,'2026-02-22 08:23:23','2026-02-22 08:23:23',NULL);
INSERT INTO `activity_log` VALUES ('6','1550434','default','updated','App\\Models\\User','updated','1','App\\Models\\User','1','[]',NULL,'2026-02-22 12:59:32','2026-02-22 12:59:32',NULL);
INSERT INTO `activity_log` VALUES ('7','1550434','default','created','App\\Models\\LoanType','created','1','App\\Models\\User','1','{\"attributes\":{\"id\":1,\"organization_id\":null,\"loan_name\":\"Assset loan\",\"interest_rate\":\"5.00\",\"interest_cycle\":\"week(s)\",\"created_at\":\"2026-02-23T04:43:44.000000Z\",\"updated_at\":\"2026-02-23T04:43:44.000000Z\",\"service_fee\":\"0\",\"service_fee_type\":\"service_fee_percentage\",\"service_fee_percentage\":\"3.00\",\"service_fee_custom_amount\":\"0.00\",\"penalty_fee_type\":\"penalty_fee_percentage\",\"penalty_fee_percentage\":\"1.50\",\"penalty_fee_custom_amount\":\"0.00\",\"branch_id\":null,\"early_repayment_percent\":\"2.00\"}}',NULL,'2026-02-23 06:43:44','2026-02-23 06:43:44',NULL);
INSERT INTO `activity_log` VALUES ('8','1550434','default','updated','App\\Models\\LoanType','updated','1','App\\Models\\User','1','{\"attributes\":{\"id\":1,\"organization_id\":1550434,\"loan_name\":\"Assset loan\",\"interest_rate\":\"5.00\",\"interest_cycle\":\"week(s)\",\"created_at\":\"2026-02-23T04:43:44.000000Z\",\"updated_at\":\"2026-02-23T04:43:44.000000Z\",\"service_fee\":\"0\",\"service_fee_type\":\"service_fee_percentage\",\"service_fee_percentage\":\"3.00\",\"service_fee_custom_amount\":\"0.00\",\"penalty_fee_type\":\"penalty_fee_percentage\",\"penalty_fee_percentage\":\"1.50\",\"penalty_fee_custom_amount\":\"0.00\",\"branch_id\":null,\"early_repayment_percent\":\"2.00\"},\"old\":{\"id\":null,\"organization_id\":null,\"loan_name\":null,\"interest_rate\":null,\"interest_cycle\":null,\"created_at\":null,\"updated_at\":null,\"service_fee\":null,\"service_fee_type\":null,\"service_fee_percentage\":null,\"service_fee_custom_amount\":null,\"penalty_fee_type\":null,\"penalty_fee_percentage\":null,\"penalty_fee_custom_amount\":null,\"branch_id\":null,\"early_repayment_percent\":null}}',NULL,'2026-02-23 06:43:44','2026-02-23 06:43:44',NULL);
INSERT INTO `activity_log` VALUES ('9','1550434','default','created','App\\Models\\LoanType','created','2','App\\Models\\User','1','{\"attributes\":{\"id\":2,\"organization_id\":null,\"loan_name\":\"Normal loan\",\"interest_rate\":\"3.00\",\"interest_cycle\":\"week(s)\",\"created_at\":\"2026-02-23T04:44:33.000000Z\",\"updated_at\":\"2026-02-23T04:44:33.000000Z\",\"service_fee\":\"0\",\"service_fee_type\":\"service_fee_percentage\",\"service_fee_percentage\":\"3.00\",\"service_fee_custom_amount\":\"0.00\",\"penalty_fee_type\":\"penalty_fee_percentage\",\"penalty_fee_percentage\":\"3.00\",\"penalty_fee_custom_amount\":\"0.00\",\"branch_id\":null,\"early_repayment_percent\":\"2.00\"}}',NULL,'2026-02-23 06:44:33','2026-02-23 06:44:33',NULL);
INSERT INTO `activity_log` VALUES ('10','1550434','default','updated','App\\Models\\LoanType','updated','2','App\\Models\\User','1','{\"attributes\":{\"id\":2,\"organization_id\":1550434,\"loan_name\":\"Normal loan\",\"interest_rate\":\"3.00\",\"interest_cycle\":\"week(s)\",\"created_at\":\"2026-02-23T04:44:33.000000Z\",\"updated_at\":\"2026-02-23T04:44:33.000000Z\",\"service_fee\":\"0\",\"service_fee_type\":\"service_fee_percentage\",\"service_fee_percentage\":\"3.00\",\"service_fee_custom_amount\":\"0.00\",\"penalty_fee_type\":\"penalty_fee_percentage\",\"penalty_fee_percentage\":\"3.00\",\"penalty_fee_custom_amount\":\"0.00\",\"branch_id\":null,\"early_repayment_percent\":\"2.00\"},\"old\":{\"id\":null,\"organization_id\":null,\"loan_name\":null,\"interest_rate\":null,\"interest_cycle\":null,\"created_at\":null,\"updated_at\":null,\"service_fee\":null,\"service_fee_type\":null,\"service_fee_percentage\":null,\"service_fee_custom_amount\":null,\"penalty_fee_type\":null,\"penalty_fee_percentage\":null,\"penalty_fee_custom_amount\":null,\"branch_id\":null,\"early_repayment_percent\":null}}',NULL,'2026-02-23 06:44:33','2026-02-23 06:44:33',NULL);
INSERT INTO `activity_log` VALUES ('11','1550434','default','created','App\\Models\\User','created','2','App\\Models\\User','1','[]',NULL,'2026-02-26 06:58:02','2026-02-26 06:58:02',NULL);
INSERT INTO `activity_log` VALUES ('12','1550434','default','created','App\\Models\\Payments','created','2','App\\Models\\User','1','{\"attributes\":{\"id\":2,\"organization_id\":2689064,\"payer_id\":2,\"payment_amount\":\"0\",\"description\":null,\"payment_made_at\":\"2026-02-26 06:58:02\",\"payment_expires_at\":\"2026-03-05T04:58:02.000000Z\",\"transaction_reference\":\"459532\",\"gateway\":\"7 DAYS FREE TRIAL\",\"created_at\":\"2026-02-26T04:58:02.000000Z\",\"updated_at\":\"2026-02-26T04:58:02.000000Z\",\"branch_id\":null}}',NULL,'2026-02-26 06:58:02','2026-02-26 06:58:02',NULL);
INSERT INTO `activity_log` VALUES ('13','1550434','default','updated','App\\Models\\User','updated','1','App\\Models\\User','1','[]',NULL,'2026-02-26 06:58:23','2026-02-26 06:58:23',NULL);
INSERT INTO `activity_log` VALUES ('14','1550434','default','updated','App\\Models\\User','updated','1','App\\Models\\User','1','[]',NULL,'2026-02-26 07:00:48','2026-02-26 07:00:48',NULL);
INSERT INTO `activity_log` VALUES ('15','1550434','default','updated','App\\Models\\User','updated','1','App\\Models\\User','1','[]',NULL,'2026-02-26 09:49:15','2026-02-26 09:49:15',NULL);
INSERT INTO `activity_log` VALUES ('16','1550434','default','updated','App\\Models\\User','updated','1','App\\Models\\User','1','[]',NULL,'2026-02-26 10:02:04','2026-02-26 10:02:04',NULL);
INSERT INTO `activity_log` VALUES ('17','1550434','default','updated','App\\Models\\User','updated','1','App\\Models\\User','1','[]',NULL,'2026-02-26 13:03:44','2026-02-26 13:03:44',NULL);
INSERT INTO `activity_log` VALUES ('18','1550434','default','updated','App\\Models\\User','updated','1','App\\Models\\User','1','[]',NULL,'2026-02-26 14:38:14','2026-02-26 14:38:14',NULL);
INSERT INTO `activity_log` VALUES ('19','1550434','default','updated','App\\Models\\User','updated','1','App\\Models\\User','1','[]',NULL,'2026-02-26 17:07:57','2026-02-26 17:07:57',NULL);
INSERT INTO `activity_log` VALUES ('20','1550434','default','created','App\\Models\\Loan','created','2','App\\Models\\User','1','{\"attributes\":{\"id\":2,\"organization_id\":1550434,\"borrower_id\":null,\"loan_type_id\":2,\"loan_status\":\"approved\",\"ai_credit_score\":null,\"default_probability\":null,\"risk_factors\":null,\"ai_recommendation\":null,\"ai_decision_reason\":null,\"ai_scored_at\":null,\"borrower_monthly_income\":null,\"borrower_employment_months\":null,\"borrower_existing_debts\":null,\"borrower_credit_history_months\":null,\"borrower_previous_defaults\":0,\"basic_pay\":null,\"recurring_allowances\":null,\"total_recurring_allowances\":null,\"other_allowances\":null,\"paye\":null,\"pension_napsa\":null,\"health_insurance\":null,\"other_recurring_deductions\":null,\"calculated_net_pay\":null,\"actual_net_pay_payslip\":null,\"qualification_status\":null,\"qualification_notes\":null,\"monthly_pay\":null,\"maximum_allowable_emi\":null,\"existing_loans_emi\":\"0.00\",\"eligible_emi\":null,\"loan_amount_eligibility\":null,\"eligibility_interest_rate\":null,\"loan_period\":null,\"principal_amount\":\"10000.00\",\"loan_amount\":\"10300.00\",\"loan_release_date\":\"2026-02-27\",\"loan_duration\":\"6\",\"duration_period\":\"week(s)\",\"transaction_reference\":null,\"created_at\":\"2026-02-27T03:41:57.000000Z\",\"updated_at\":\"2026-02-27T03:41:57.000000Z\",\"repayment_amount\":\"10715.70\",\"loan_due_date\":\"\",\"interest_amount\":\"415.70\",\"activate_loan_agreement_form\":false,\"loan_agreement_file_path\":null,\"interest_rate\":\"3\",\"loan_number\":\"RB-69A1128537356\",\"from_this_account\":null,\"balance\":\"10716\",\"loan_settlement_file_path\":null,\"disbursed_amount\":\"9700\",\"service_fee\":\"300\",\"branch_id\":null,\"is_early_settlement\":0,\"member_id\":2,\"next_payment_date\":\"2026-03-06\",\"amount_per_installment\":\"1716.67\",\"clearance_date\":\"2026-04-10\"}}',NULL,'2026-02-27 05:41:57','2026-02-27 05:41:57',NULL);
INSERT INTO `activity_log` VALUES ('21','1550434','default','updated','App\\Models\\Loan','updated','2','App\\Models\\User','1','{\"attributes\":{\"id\":2,\"organization_id\":1550434,\"borrower_id\":null,\"loan_type_id\":2,\"loan_status\":\"approved\",\"ai_credit_score\":null,\"default_probability\":null,\"risk_factors\":null,\"ai_recommendation\":null,\"ai_decision_reason\":null,\"ai_scored_at\":null,\"borrower_monthly_income\":null,\"borrower_employment_months\":null,\"borrower_existing_debts\":null,\"borrower_credit_history_months\":null,\"borrower_previous_defaults\":0,\"basic_pay\":null,\"recurring_allowances\":null,\"total_recurring_allowances\":null,\"other_allowances\":null,\"paye\":null,\"pension_napsa\":null,\"health_insurance\":null,\"other_recurring_deductions\":null,\"calculated_net_pay\":null,\"actual_net_pay_payslip\":null,\"qualification_status\":null,\"qualification_notes\":null,\"monthly_pay\":null,\"maximum_allowable_emi\":null,\"existing_loans_emi\":\"0.00\",\"eligible_emi\":null,\"loan_amount_eligibility\":null,\"eligibility_interest_rate\":null,\"loan_period\":null,\"principal_amount\":\"10000.00\",\"loan_amount\":\"10300.00\",\"loan_release_date\":\"2026-02-27\",\"loan_duration\":\"6\",\"duration_period\":\"week(s)\",\"transaction_reference\":null,\"created_at\":\"2026-02-27T03:41:57.000000Z\",\"updated_at\":\"2026-02-27T03:41:57.000000Z\",\"repayment_amount\":\"10715.70\",\"loan_due_date\":\"\",\"interest_amount\":\"415.70\",\"activate_loan_agreement_form\":false,\"loan_agreement_file_path\":null,\"interest_rate\":\"3\",\"loan_number\":\"RB-69A1128537356\",\"from_this_account\":null,\"balance\":\"10716\",\"loan_settlement_file_path\":null,\"disbursed_amount\":\"9700\",\"service_fee\":\"300\",\"branch_id\":null,\"is_early_settlement\":0,\"member_id\":2,\"next_payment_date\":\"2026-03-06\",\"amount_per_installment\":\"1716.67\",\"clearance_date\":\"2026-04-10\"},\"old\":{\"id\":null,\"organization_id\":null,\"borrower_id\":null,\"loan_type_id\":null,\"loan_status\":null,\"ai_credit_score\":null,\"default_probability\":null,\"risk_factors\":null,\"ai_recommendation\":null,\"ai_decision_reason\":null,\"ai_scored_at\":null,\"borrower_monthly_income\":null,\"borrower_employment_months\":null,\"borrower_existing_debts\":null,\"borrower_credit_history_months\":null,\"borrower_previous_defaults\":null,\"basic_pay\":null,\"recurring_allowances\":null,\"total_recurring_allowances\":null,\"other_allowances\":null,\"paye\":null,\"pension_napsa\":null,\"health_insurance\":null,\"other_recurring_deductions\":null,\"calculated_net_pay\":null,\"actual_net_pay_payslip\":null,\"qualification_status\":null,\"qualification_notes\":null,\"monthly_pay\":null,\"maximum_allowable_emi\":null,\"existing_loans_emi\":null,\"eligible_emi\":null,\"loan_amount_eligibility\":null,\"eligibility_interest_rate\":null,\"loan_period\":null,\"principal_amount\":null,\"loan_amount\":null,\"loan_release_date\":null,\"loan_duration\":null,\"duration_period\":null,\"transaction_reference\":null,\"created_at\":null,\"updated_at\":null,\"repayment_amount\":null,\"loan_due_date\":null,\"interest_amount\":null,\"activate_loan_agreement_form\":null,\"loan_agreement_file_path\":null,\"interest_rate\":null,\"loan_number\":null,\"from_this_account\":null,\"balance\":null,\"loan_settlement_file_path\":null,\"disbursed_amount\":null,\"service_fee\":null,\"branch_id\":null,\"is_early_settlement\":null,\"member_id\":null,\"next_payment_date\":null,\"amount_per_installment\":null,\"clearance_date\":null}}',NULL,'2026-02-27 05:41:57','2026-02-27 05:41:57',NULL);
INSERT INTO `activity_log` VALUES ('22','1550434','default','updated','App\\Models\\User','updated','1','App\\Models\\User','1','[]',NULL,'2026-02-27 07:35:34','2026-02-27 07:35:34',NULL);
INSERT INTO `activity_log` VALUES ('23','1550434','default','created','App\\Models\\Repayments','created','1','App\\Models\\User','1','{\"attributes\":{\"id\":1,\"organization_id\":null,\"loan_id\":2,\"balance\":\"9716\",\"payments\":\"1000\",\"principal\":\"10000\",\"payments_method\":\"cash\",\"created_at\":\"2026-02-27T05:47:35.000000Z\",\"updated_at\":\"2026-02-27T05:47:35.000000Z\",\"reference_number\":\"No reference was entered by Raymond - raywalcott14@gmail.com\",\"loan_number\":\"RB-69A1128537356\",\"branch_id\":null}}',NULL,'2026-02-27 07:47:35','2026-02-27 07:47:35',NULL);
INSERT INTO `activity_log` VALUES ('24','1550434','default','updated','App\\Models\\Repayments','updated','1','App\\Models\\User','1','{\"attributes\":{\"id\":1,\"organization_id\":1550434,\"loan_id\":2,\"balance\":\"9716\",\"payments\":\"1000\",\"principal\":\"10000\",\"payments_method\":\"cash\",\"created_at\":\"2026-02-27T05:47:35.000000Z\",\"updated_at\":\"2026-02-27T05:47:35.000000Z\",\"reference_number\":\"No reference was entered by Raymond - raywalcott14@gmail.com\",\"loan_number\":\"RB-69A1128537356\",\"branch_id\":null},\"old\":{\"id\":null,\"organization_id\":null,\"loan_id\":null,\"balance\":null,\"payments\":null,\"principal\":null,\"payments_method\":null,\"created_at\":null,\"updated_at\":null,\"reference_number\":null,\"loan_number\":null,\"branch_id\":null}}',NULL,'2026-02-27 07:47:35','2026-02-27 07:47:35',NULL);
INSERT INTO `activity_log` VALUES ('25','1550434','default','updated','App\\Models\\Loan','updated','2','App\\Models\\User','1','{\"attributes\":{\"id\":2,\"organization_id\":1550434,\"borrower_id\":null,\"loan_type_id\":2,\"loan_status\":\"partially_paid\",\"ai_credit_score\":null,\"default_probability\":null,\"risk_factors\":null,\"ai_recommendation\":null,\"ai_decision_reason\":null,\"ai_scored_at\":null,\"borrower_monthly_income\":null,\"borrower_employment_months\":null,\"borrower_existing_debts\":null,\"borrower_credit_history_months\":null,\"borrower_previous_defaults\":0,\"basic_pay\":null,\"recurring_allowances\":null,\"total_recurring_allowances\":null,\"other_allowances\":null,\"paye\":null,\"pension_napsa\":null,\"health_insurance\":null,\"other_recurring_deductions\":null,\"calculated_net_pay\":null,\"actual_net_pay_payslip\":null,\"qualification_status\":null,\"qualification_notes\":null,\"monthly_pay\":null,\"maximum_allowable_emi\":null,\"existing_loans_emi\":\"0.00\",\"eligible_emi\":null,\"loan_amount_eligibility\":null,\"eligibility_interest_rate\":null,\"loan_period\":null,\"principal_amount\":\"10000.00\",\"loan_amount\":\"10300.00\",\"loan_release_date\":\"2026-02-27\",\"loan_duration\":\"6\",\"duration_period\":\"week(s)\",\"transaction_reference\":null,\"created_at\":\"2026-02-27T03:41:57.000000Z\",\"updated_at\":\"2026-02-27T05:47:35.000000Z\",\"repayment_amount\":\"10715.70\",\"loan_due_date\":\"\",\"interest_amount\":\"415.70\",\"activate_loan_agreement_form\":false,\"loan_agreement_file_path\":null,\"interest_rate\":\"3\",\"loan_number\":\"RB-69A1128537356\",\"from_this_account\":null,\"balance\":\"9716\",\"carried_forward_balance\":\"0.00\",\"loan_settlement_file_path\":null,\"disbursed_amount\":\"9700\",\"service_fee\":\"300\",\"branch_id\":null,\"is_early_settlement\":0,\"member_id\":2,\"next_payment_date\":\"2026-03-06\",\"amount_per_installment\":\"1716.67\",\"clearance_date\":\"2026-04-10\"},\"old\":{\"id\":2,\"organization_id\":1550434,\"borrower_id\":null,\"loan_type_id\":2,\"loan_status\":\"approved\",\"ai_credit_score\":null,\"default_probability\":null,\"risk_factors\":null,\"ai_recommendation\":null,\"ai_decision_reason\":null,\"ai_scored_at\":null,\"borrower_monthly_income\":null,\"borrower_employment_months\":null,\"borrower_existing_debts\":null,\"borrower_credit_history_months\":null,\"borrower_previous_defaults\":0,\"basic_pay\":null,\"recurring_allowances\":null,\"total_recurring_allowances\":null,\"other_allowances\":null,\"paye\":null,\"pension_napsa\":null,\"health_insurance\":null,\"other_recurring_deductions\":null,\"calculated_net_pay\":null,\"actual_net_pay_payslip\":null,\"qualification_status\":null,\"qualification_notes\":null,\"monthly_pay\":null,\"maximum_allowable_emi\":null,\"existing_loans_emi\":\"0.00\",\"eligible_emi\":null,\"loan_amount_eligibility\":null,\"eligibility_interest_rate\":null,\"loan_period\":null,\"principal_amount\":\"10000.00\",\"loan_amount\":\"10300.00\",\"loan_release_date\":\"2026-02-27\",\"loan_duration\":\"6\",\"duration_period\":\"week(s)\",\"transaction_reference\":null,\"created_at\":\"2026-02-27T03:41:57.000000Z\",\"updated_at\":\"2026-02-27T03:41:57.000000Z\",\"repayment_amount\":\"10715.70\",\"loan_due_date\":\"\",\"interest_amount\":\"415.70\",\"activate_loan_agreement_form\":false,\"loan_agreement_file_path\":null,\"interest_rate\":\"3\",\"loan_number\":\"RB-69A1128537356\",\"from_this_account\":null,\"balance\":\"10716\",\"carried_forward_balance\":\"0.00\",\"loan_settlement_file_path\":null,\"disbursed_amount\":\"9700\",\"service_fee\":\"300\",\"branch_id\":null,\"is_early_settlement\":0,\"member_id\":2,\"next_payment_date\":\"2026-03-06\",\"amount_per_installment\":\"1716.67\",\"clearance_date\":\"2026-04-10\"}}',NULL,'2026-02-27 07:47:35','2026-02-27 07:47:35',NULL);
INSERT INTO `activity_log` VALUES ('26','1550434','default','updated','App\\Models\\Loan','updated','2','App\\Models\\User','1','{\"attributes\":{\"id\":2,\"organization_id\":1550434,\"borrower_id\":null,\"loan_type_id\":2,\"loan_status\":\"partially_paid\",\"ai_credit_score\":null,\"default_probability\":null,\"risk_factors\":null,\"ai_recommendation\":null,\"ai_decision_reason\":null,\"ai_scored_at\":null,\"borrower_monthly_income\":null,\"borrower_employment_months\":null,\"borrower_existing_debts\":null,\"borrower_credit_history_months\":null,\"borrower_previous_defaults\":0,\"basic_pay\":null,\"recurring_allowances\":null,\"total_recurring_allowances\":null,\"other_allowances\":null,\"paye\":null,\"pension_napsa\":null,\"health_insurance\":null,\"other_recurring_deductions\":null,\"calculated_net_pay\":null,\"actual_net_pay_payslip\":null,\"qualification_status\":null,\"qualification_notes\":null,\"monthly_pay\":null,\"maximum_allowable_emi\":null,\"existing_loans_emi\":\"0.00\",\"eligible_emi\":null,\"loan_amount_eligibility\":null,\"eligibility_interest_rate\":null,\"loan_period\":null,\"principal_amount\":\"10000.00\",\"loan_amount\":\"10300.00\",\"loan_release_date\":\"2026-02-27\",\"loan_duration\":\"6\",\"duration_period\":\"week(s)\",\"transaction_reference\":null,\"created_at\":\"2026-02-27T03:41:57.000000Z\",\"updated_at\":\"2026-02-27T05:47:35.000000Z\",\"repayment_amount\":\"10715.70\",\"loan_due_date\":\"\",\"interest_amount\":\"415.70\",\"activate_loan_agreement_form\":false,\"loan_agreement_file_path\":null,\"interest_rate\":\"3\",\"loan_number\":\"RB-69A1128537356\",\"from_this_account\":null,\"balance\":\"9716\",\"carried_forward_balance\":\"0.00\",\"loan_settlement_file_path\":null,\"disbursed_amount\":\"9700\",\"service_fee\":\"300\",\"branch_id\":null,\"is_early_settlement\":0,\"member_id\":2,\"next_payment_date\":\"2026-03-13\",\"amount_per_installment\":\"1716.67\",\"clearance_date\":\"2026-04-10\"},\"old\":{\"id\":2,\"organization_id\":1550434,\"borrower_id\":null,\"loan_type_id\":2,\"loan_status\":\"partially_paid\",\"ai_credit_score\":null,\"default_probability\":null,\"risk_factors\":null,\"ai_recommendation\":null,\"ai_decision_reason\":null,\"ai_scored_at\":null,\"borrower_monthly_income\":null,\"borrower_employment_months\":null,\"borrower_existing_debts\":null,\"borrower_credit_history_months\":null,\"borrower_previous_defaults\":0,\"basic_pay\":null,\"recurring_allowances\":null,\"total_recurring_allowances\":null,\"other_allowances\":null,\"paye\":null,\"pension_napsa\":null,\"health_insurance\":null,\"other_recurring_deductions\":null,\"calculated_net_pay\":null,\"actual_net_pay_payslip\":null,\"qualification_status\":null,\"qualification_notes\":null,\"monthly_pay\":null,\"maximum_allowable_emi\":null,\"existing_loans_emi\":\"0.00\",\"eligible_emi\":null,\"loan_amount_eligibility\":null,\"eligibility_interest_rate\":null,\"loan_period\":null,\"principal_amount\":\"10000.00\",\"loan_amount\":\"10300.00\",\"loan_release_date\":\"2026-02-27\",\"loan_duration\":\"6\",\"duration_period\":\"week(s)\",\"transaction_reference\":null,\"created_at\":\"2026-02-27T03:41:57.000000Z\",\"updated_at\":\"2026-02-27T05:47:35.000000Z\",\"repayment_amount\":\"10715.70\",\"loan_due_date\":\"\",\"interest_amount\":\"415.70\",\"activate_loan_agreement_form\":false,\"loan_agreement_file_path\":null,\"interest_rate\":\"3\",\"loan_number\":\"RB-69A1128537356\",\"from_this_account\":null,\"balance\":9716,\"carried_forward_balance\":\"0.00\",\"loan_settlement_file_path\":null,\"disbursed_amount\":\"9700\",\"service_fee\":\"300\",\"branch_id\":null,\"is_early_settlement\":0,\"member_id\":2,\"next_payment_date\":\"2026-03-06\",\"amount_per_installment\":\"1716.67\",\"clearance_date\":\"2026-04-10\"}}',NULL,'2026-02-27 07:47:35','2026-02-27 07:47:35',NULL);
INSERT INTO `activity_log` VALUES ('27','1550434','default','updated','App\\Models\\User','updated','1','App\\Models\\User','1','[]',NULL,'2026-02-27 15:41:53','2026-02-27 15:41:53',NULL);
INSERT INTO `activity_log` VALUES ('28','1550434','default','created','App\\Models\\User','created','3','App\\Models\\User','1','[]',NULL,'2026-02-27 17:29:56','2026-02-27 17:29:56',NULL);
INSERT INTO `activity_log` VALUES ('29','1550434','default','created','App\\Models\\Payments','created','3','App\\Models\\User','1','{\"attributes\":{\"id\":3,\"organization_id\":3629395,\"payer_id\":3,\"payment_amount\":\"0\",\"description\":null,\"payment_made_at\":\"2026-02-27 17:29:56\",\"payment_expires_at\":\"2026-03-06T15:29:56.000000Z\",\"transaction_reference\":\"106242\",\"gateway\":\"7 DAYS FREE TRIAL\",\"created_at\":\"2026-02-27T15:29:56.000000Z\",\"updated_at\":\"2026-02-27T15:29:56.000000Z\",\"branch_id\":null}}',NULL,'2026-02-27 17:29:56','2026-02-27 17:29:56',NULL);
INSERT INTO `activity_log` VALUES ('30','1550434','default','updated','App\\Models\\User','updated','1','App\\Models\\User','1','[]',NULL,'2026-02-27 17:30:17','2026-02-27 17:30:17',NULL);
INSERT INTO `activity_log` VALUES ('31','1550434','default','updated','App\\Models\\User','updated','1','App\\Models\\User','1','[]',NULL,'2026-02-27 17:38:02','2026-02-27 17:38:02',NULL);
INSERT INTO `activity_log` VALUES ('32','1550434','default','updated','App\\Models\\User','updated','1','App\\Models\\User','1','[]',NULL,'2026-02-27 19:24:03','2026-02-27 19:24:03',NULL);
INSERT INTO `activity_log` VALUES ('33','1550434','default','updated','App\\Models\\User','updated','1','App\\Models\\User','1','[]',NULL,'2026-02-27 19:27:49','2026-02-27 19:27:49',NULL);
INSERT INTO `activity_log` VALUES ('34','1550434','default','updated','App\\Models\\User','updated','1','App\\Models\\User','1','[]',NULL,'2026-02-27 20:56:22','2026-02-27 20:56:22',NULL);
INSERT INTO `activity_log` VALUES ('35','1550434','default','updated','App\\Models\\User','updated','1','App\\Models\\User','1','[]',NULL,'2026-02-27 21:44:05','2026-02-27 21:44:05',NULL);
INSERT INTO `activity_log` VALUES ('36','1550434','default','updated','App\\Models\\User','updated','1','App\\Models\\User','1','[]',NULL,'2026-02-27 21:56:17','2026-02-27 21:56:17',NULL);
INSERT INTO `activity_log` VALUES ('37','1550434','default','updated','App\\Models\\LoanType','updated','2','App\\Models\\User','1','{\"attributes\":{\"id\":2,\"organization_id\":1550434,\"loan_name\":\"Normal loan\",\"interest_rate\":\"3.00\",\"interest_cycle\":\"month(s)\",\"created_at\":\"2026-02-23T04:44:33.000000Z\",\"updated_at\":\"2026-02-28T06:54:22.000000Z\",\"service_fee\":\"0\",\"service_fee_type\":\"service_fee_percentage\",\"service_fee_percentage\":\"3.00\",\"service_fee_custom_amount\":\"0.00\",\"penalty_fee_type\":\"penalty_fee_percentage\",\"penalty_fee_percentage\":\"3.00\",\"penalty_fee_custom_amount\":\"0.00\",\"branch_id\":null,\"early_repayment_percent\":\"2.00\"},\"old\":{\"id\":2,\"organization_id\":1550434,\"loan_name\":\"Normal loan\",\"interest_rate\":\"3.00\",\"interest_cycle\":\"week(s)\",\"created_at\":\"2026-02-23T04:44:33.000000Z\",\"updated_at\":\"2026-02-23T04:44:33.000000Z\",\"service_fee\":\"0\",\"service_fee_type\":\"service_fee_percentage\",\"service_fee_percentage\":\"3.00\",\"service_fee_custom_amount\":\"0.00\",\"penalty_fee_type\":\"penalty_fee_percentage\",\"penalty_fee_percentage\":\"3.00\",\"penalty_fee_custom_amount\":\"0.00\",\"branch_id\":null,\"early_repayment_percent\":\"2.00\"}}',NULL,'2026-02-28 08:54:22','2026-02-28 08:54:22',NULL);
INSERT INTO `activity_log` VALUES ('38','1550434','default','updated','App\\Models\\User','updated','1','App\\Models\\User','1','[]',NULL,'2026-03-03 08:25:54','2026-03-03 08:25:54',NULL);
INSERT INTO `activity_log` VALUES ('39','1550434','default','created','App\\Models\\Repayments','created','2','App\\Models\\User','1','{\"attributes\":{\"id\":2,\"organization_id\":null,\"loan_id\":2,\"balance\":\"8716\",\"payments\":\"1000\",\"principal\":\"10000\",\"payments_method\":\"group_payment\",\"created_at\":\"2026-03-05T05:33:08.000000Z\",\"updated_at\":\"2026-03-05T05:33:08.000000Z\",\"reference_number\":\"GPS-1-20260305073308\",\"loan_number\":\"RB-69A1128537356\",\"branch_id\":null}}',NULL,'2026-03-05 07:33:08','2026-03-05 07:33:08',NULL);
INSERT INTO `activity_log` VALUES ('40','1550434','default','updated','App\\Models\\Repayments','updated','2','App\\Models\\User','1','{\"attributes\":{\"id\":2,\"organization_id\":1550434,\"loan_id\":2,\"balance\":\"8716\",\"payments\":\"1000\",\"principal\":\"10000\",\"payments_method\":\"group_payment\",\"created_at\":\"2026-03-05T05:33:08.000000Z\",\"updated_at\":\"2026-03-05T05:33:08.000000Z\",\"reference_number\":\"GPS-1-20260305073308\",\"loan_number\":\"RB-69A1128537356\",\"branch_id\":null},\"old\":{\"id\":null,\"organization_id\":null,\"loan_id\":null,\"balance\":null,\"payments\":null,\"principal\":null,\"payments_method\":null,\"created_at\":null,\"updated_at\":null,\"reference_number\":null,\"loan_number\":null,\"branch_id\":null}}',NULL,'2026-03-05 07:33:08','2026-03-05 07:33:08',NULL);
INSERT INTO `activity_log` VALUES ('41','1550434','default','updated','App\\Models\\Loan','updated','2','App\\Models\\User','1','{\"attributes\":{\"id\":2,\"organization_id\":1550434,\"borrower_id\":null,\"loan_type_id\":2,\"loan_status\":\"partially_paid\",\"ai_credit_score\":null,\"default_probability\":null,\"risk_factors\":null,\"ai_recommendation\":null,\"ai_decision_reason\":null,\"ai_scored_at\":null,\"borrower_monthly_income\":null,\"borrower_employment_months\":null,\"borrower_existing_debts\":null,\"borrower_credit_history_months\":null,\"borrower_previous_defaults\":0,\"basic_pay\":null,\"recurring_allowances\":null,\"total_recurring_allowances\":null,\"other_allowances\":null,\"paye\":null,\"pension_napsa\":null,\"health_insurance\":null,\"other_recurring_deductions\":null,\"calculated_net_pay\":null,\"actual_net_pay_payslip\":null,\"qualification_status\":null,\"qualification_notes\":null,\"monthly_pay\":null,\"maximum_allowable_emi\":null,\"existing_loans_emi\":\"0.00\",\"eligible_emi\":null,\"loan_amount_eligibility\":null,\"eligibility_interest_rate\":null,\"loan_period\":null,\"principal_amount\":\"10000.00\",\"loan_amount\":\"10300.00\",\"loan_release_date\":\"2026-02-27\",\"loan_duration\":\"6\",\"duration_period\":\"week(s)\",\"transaction_reference\":null,\"created_at\":\"2026-02-27T03:41:57.000000Z\",\"updated_at\":\"2026-03-05T05:33:08.000000Z\",\"repayment_amount\":\"10715.70\",\"loan_due_date\":\"\",\"interest_amount\":\"415.70\",\"activate_loan_agreement_form\":false,\"loan_agreement_file_path\":null,\"interest_rate\":\"3\",\"loan_number\":\"RB-69A1128537356\",\"from_this_account\":null,\"balance\":\"8716\",\"carried_forward_balance\":\"0.00\",\"loan_settlement_file_path\":null,\"disbursed_amount\":\"9700\",\"service_fee\":\"300\",\"branch_id\":null,\"is_early_settlement\":0,\"member_id\":2,\"next_payment_date\":\"2026-03-13\",\"amount_per_installment\":\"2433.34\",\"clearance_date\":\"2026-04-10\"},\"old\":{\"id\":2,\"organization_id\":1550434,\"borrower_id\":null,\"loan_type_id\":2,\"loan_status\":\"partially_paid\",\"ai_credit_score\":null,\"default_probability\":null,\"risk_factors\":null,\"ai_recommendation\":null,\"ai_decision_reason\":null,\"ai_scored_at\":null,\"borrower_monthly_income\":null,\"borrower_employment_months\":null,\"borrower_existing_debts\":null,\"borrower_credit_history_months\":null,\"borrower_previous_defaults\":0,\"basic_pay\":null,\"recurring_allowances\":null,\"total_recurring_allowances\":null,\"other_allowances\":null,\"paye\":null,\"pension_napsa\":null,\"health_insurance\":null,\"other_recurring_deductions\":null,\"calculated_net_pay\":null,\"actual_net_pay_payslip\":null,\"qualification_status\":null,\"qualification_notes\":null,\"monthly_pay\":null,\"maximum_allowable_emi\":null,\"existing_loans_emi\":\"0.00\",\"eligible_emi\":null,\"loan_amount_eligibility\":null,\"eligibility_interest_rate\":null,\"loan_period\":null,\"principal_amount\":\"10000.00\",\"loan_amount\":\"10300.00\",\"loan_release_date\":\"2026-02-27\",\"loan_duration\":\"6\",\"duration_period\":\"week(s)\",\"transaction_reference\":null,\"created_at\":\"2026-02-27T03:41:57.000000Z\",\"updated_at\":\"2026-02-27T05:47:35.000000Z\",\"repayment_amount\":\"10715.70\",\"loan_due_date\":\"\",\"interest_amount\":\"415.70\",\"activate_loan_agreement_form\":false,\"loan_agreement_file_path\":null,\"interest_rate\":\"3\",\"loan_number\":\"RB-69A1128537356\",\"from_this_account\":null,\"balance\":\"9716\",\"carried_forward_balance\":\"0.00\",\"loan_settlement_file_path\":null,\"disbursed_amount\":\"9700\",\"service_fee\":\"300\",\"branch_id\":null,\"is_early_settlement\":0,\"member_id\":2,\"next_payment_date\":\"2026-03-13\",\"amount_per_installment\":\"1716.67\",\"clearance_date\":\"2026-04-10\"}}',NULL,'2026-03-05 07:33:08','2026-03-05 07:33:08',NULL);
INSERT INTO `activity_log` VALUES ('42','1550434','default','Member transferred to new group','App\\Models\\Member',NULL,'2','App\\Models\\User','1','{\"from_group_id\":1,\"to_group_id\":2,\"reason\":\"change in location\"}',NULL,'2026-03-05 16:06:27','2026-03-05 16:06:27',NULL);
INSERT INTO `activity_log` VALUES ('43','1550434','default','Member transferred to new group','App\\Models\\Member',NULL,'2','App\\Models\\User','1','{\"from_group_id\":2,\"to_group_id\":1,\"reason\":\"relocated\"}',NULL,'2026-03-05 16:22:28','2026-03-05 16:22:28',NULL);
INSERT INTO `activity_log` VALUES ('44','1550434','default','updated','App\\Models\\User','updated','1','App\\Models\\User','1','[]',NULL,'2026-03-05 22:56:46','2026-03-05 22:56:46',NULL);
INSERT INTO `activity_log` VALUES ('45','1550434','default','Member transferred to new group','App\\Models\\Member',NULL,'2','App\\Models\\User','1','{\"from_group_id\":1,\"to_group_id\":2,\"reason\":\"relocated\"}',NULL,'2026-03-06 07:16:07','2026-03-06 07:16:07',NULL);

DROP TABLE IF EXISTS `actvity_logs`;
CREATE TABLE `actvity_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `asset_categories`;
CREATE TABLE `asset_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `default_depreciation_rate` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `organization_id` bigint(20) unsigned DEFAULT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `asset_categories_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `assets`;
CREATE TABLE `assets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `asset_name` varchar(255) NOT NULL,
  `asset_code` varchar(255) NOT NULL,
  `asset_category_id` bigint(20) unsigned NOT NULL,
  `purchase_date` date NOT NULL,
  `purchase_cost` decimal(15,2) NOT NULL,
  `supplier` varchar(255) DEFAULT NULL,
  `useful_life_years` int(11) NOT NULL DEFAULT 5,
  `depreciation_method` enum('straight_line','reducing_balance') NOT NULL DEFAULT 'straight_line',
  `depreciation_rate` decimal(5,2) DEFAULT NULL,
  `accumulated_depreciation` decimal(15,2) NOT NULL DEFAULT 0.00,
  `net_book_value` decimal(15,2) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `custodian` varchar(255) DEFAULT NULL,
  `status` enum('active','disposed','damaged') NOT NULL DEFAULT 'active',
  `disposal_date` date DEFAULT NULL,
  `disposal_value` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `organization_id` bigint(20) unsigned DEFAULT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `assets_asset_code_unique` (`asset_code`),
  KEY `assets_asset_category_id_foreign` (`asset_category_id`),
  CONSTRAINT `assets_asset_category_id_foreign` FOREIGN KEY (`asset_category_id`) REFERENCES `asset_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `borrower_files`;
CREATE TABLE `borrower_files` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint(20) unsigned DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `borrower_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `borrower_files_borrower_id_foreign` (`borrower_id`),
  CONSTRAINT `borrower_files_borrower_id_foreign` FOREIGN KEY (`borrower_id`) REFERENCES `borrowers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `borrowers`;
CREATE TABLE `borrowers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint(20) unsigned DEFAULT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `dob` varchar(255) NOT NULL,
  `occupation` varchar(255) DEFAULT NULL,
  `identification` varchar(255) NOT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `province` varchar(255) NOT NULL,
  `zipcode` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `next_of_kin_first_name` varchar(255) DEFAULT NULL,
  `next_of_kin_last_name` varchar(255) DEFAULT NULL,
  `phone_next_of_kin` varchar(255) DEFAULT NULL,
  `address_next_of_kin` varchar(255) DEFAULT NULL,
  `relationship_next_of_kin` varchar(255) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `bank_branch` varchar(255) DEFAULT NULL,
  `bank_sort_code` varchar(255) DEFAULT NULL,
  `bank_account_number` varchar(255) DEFAULT NULL,
  `bank_account_name` varchar(255) DEFAULT NULL,
  `mobile_money_name` varchar(255) DEFAULT NULL,
  `mobile_money_number` varchar(255) DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `full_name` varchar(255) NOT NULL,
  `added_by` bigint(20) unsigned NOT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `borrowers_added_by_foreign` (`added_by`),
  CONSTRAINT `borrowers_added_by_foreign` FOREIGN KEY (`added_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `branches`;
CREATE TABLE `branches` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `branch_name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `province` varchar(255) DEFAULT NULL,
  `branch_manager` bigint(20) unsigned DEFAULT NULL,
  `zipcode` varchar(255) DEFAULT NULL,
  `added_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  `organization_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `branches_branch_manager_foreign` (`branch_manager`),
  CONSTRAINT `branches_branch_manager_foreign` FOREIGN KEY (`branch_manager`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `direct_debit_mandate_settings`;
CREATE TABLE `direct_debit_mandate_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint(20) unsigned DEFAULT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  `service_provider_reference_number` varchar(255) DEFAULT NULL COMMENT 'Service Provider Reference Number assigned by the bank',
  `days_before_payment_date` int(11) NOT NULL DEFAULT 5,
  `days_after_payment_date` int(11) NOT NULL DEFAULT 5,
  `default_payment_frequency` varchar(255) NOT NULL DEFAULT 'M' COMMENT 'D=Daily, W=Weekly, FN=Fortnightly, M=Monthly, Q=Quarterly, H=Half Yearly, A=Annually',
  `payment_date_calculation` varchar(255) NOT NULL DEFAULT 'loan_release_date' COMMENT 'loan_release_date, loan_due_date, or custom',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `employees`;
CREATE TABLE `employees` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint(20) unsigned DEFAULT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  `employee_number` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `national_id` varchar(255) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `date_of_employment` date NOT NULL,
  `basic_salary` decimal(10,2) NOT NULL,
  `housing_allowance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `transport_allowance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `medical_allowance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `other_allowances` decimal(10,2) NOT NULL DEFAULT 0.00,
  `salary_scale_id` bigint(20) unsigned DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `bank_account_number` varchar(255) DEFAULT NULL,
  `bank_branch` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employees_employee_number_unique` (`employee_number`),
  KEY `employees_salary_scale_id_foreign` (`salary_scale_id`),
  CONSTRAINT `employees_salary_scale_id_foreign` FOREIGN KEY (`salary_scale_id`) REFERENCES `salary_scales` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `expense_categories`;
CREATE TABLE `expense_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint(20) unsigned DEFAULT NULL,
  `category_name` varchar(255) NOT NULL,
  `category_code` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `expenses`;
CREATE TABLE `expenses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint(20) unsigned DEFAULT NULL,
  `expense_name` varchar(255) NOT NULL,
  `expense_amount` varchar(255) NOT NULL,
  `expense_vendor` varchar(255) NOT NULL,
  `expense_attachment` varchar(255) NOT NULL,
  `expense_date` varchar(255) NOT NULL,
  `category_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `from_this_account` varchar(255) NOT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `expenses_category_id_foreign` (`category_id`),
  CONSTRAINT `expenses_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `expense_categories` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `exports`;
CREATE TABLE `exports` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint(20) unsigned DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `file_disk` varchar(255) NOT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `exporter` varchar(255) NOT NULL,
  `processed_rows` int(10) unsigned NOT NULL DEFAULT 0,
  `total_rows` int(10) unsigned NOT NULL,
  `successful_rows` int(10) unsigned NOT NULL DEFAULT 0,
  `user_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `exports_user_id_foreign` (`user_id`),
  CONSTRAINT `exports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `exports` VALUES ('1',NULL,'2026-02-22 11:41:01','local','export-1-loans','App\\Filament\\Exports\\LoanExporter','0','0','0','1','2026-02-22 11:41:00','2026-02-22 11:41:01',NULL);

DROP TABLE IF EXISTS `failed_import_rows`;
CREATE TABLE `failed_import_rows` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint(20) unsigned DEFAULT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`data`)),
  `import_id` bigint(20) unsigned NOT NULL,
  `validation_error` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `failed_import_rows_import_id_foreign` (`import_id`),
  CONSTRAINT `failed_import_rows_import_id_foreign` FOREIGN KEY (`import_id`) REFERENCES `imports` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint(20) unsigned DEFAULT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `group_members`;
CREATE TABLE `group_members` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` bigint(20) unsigned NOT NULL,
  `member_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `group_members_group_id_foreign` (`group_id`),
  KEY `group_members_member_id_foreign` (`member_id`),
  CONSTRAINT `group_members_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `group_members_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `group_members` VALUES ('3','1','3',NULL,NULL);
INSERT INTO `group_members` VALUES ('4','2','4',NULL,NULL);
INSERT INTO `group_members` VALUES ('7','2','2',NULL,NULL);

DROP TABLE IF EXISTS `groups`;
CREATE TABLE `groups` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `registration_number` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `assigned_officer` bigint(20) unsigned DEFAULT NULL,
  `chairperson` varchar(255) DEFAULT NULL,
  `secretary` varchar(255) DEFAULT NULL,
  `treasurer` varchar(255) DEFAULT NULL,
  `minimum_members` int(11) NOT NULL DEFAULT 10,
  PRIMARY KEY (`id`),
  UNIQUE KEY `groups_registration_number_unique` (`registration_number`),
  KEY `groups_assigned_officer_foreign` (`assigned_officer`),
  CONSTRAINT `groups_assigned_officer_foreign` FOREIGN KEY (`assigned_officer`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `groups` VALUES ('1','AMANI LADIES','active','2026-02-23 07:43:36','2026-02-25 21:51:16','12345',NULL,NULL,'1',NULL,NULL,NULL,'10');
INSERT INTO `groups` VALUES ('2','BLESSED LADIES','active','2026-02-27 08:44:47','2026-02-27 08:44:47','123456','KANYAKWAR','0700000000','2',NULL,NULL,NULL,'10');

DROP TABLE IF EXISTS `imports`;
CREATE TABLE `imports` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint(20) unsigned DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `importer` varchar(255) NOT NULL,
  `processed_rows` int(10) unsigned NOT NULL DEFAULT 0,
  `total_rows` int(10) unsigned NOT NULL,
  `successful_rows` int(10) unsigned NOT NULL DEFAULT 0,
  `user_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `imports_user_id_foreign` (`user_id`),
  CONSTRAINT `imports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `organization_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `job_batches` VALUES ('a123d860-05c9-4d4c-828e-3fa6910f5bbf',NULL,'','1','0','0','[]','a:2:{s:13:\"allowFailures\";b:1;s:7:\"finally\";a:1:{i:0;O:47:\"Laravel\\SerializableClosure\\SerializableClosure\":1:{s:12:\"serializable\";O:46:\"Laravel\\SerializableClosure\\Serializers\\Signed\":2:{s:12:\"serializable\";s:9384:\"O:46:\"Laravel\\SerializableClosure\\Serializers\\Native\":5:{s:3:\"use\";a:1:{s:4:\"next\";O:46:\"Filament\\Actions\\Exports\\Jobs\\ExportCompletion\":7:{s:11:\"\0*\0exporter\";O:33:\"App\\Filament\\Exports\\LoanExporter\":3:{s:9:\"\0*\0export\";O:38:\"Filament\\Actions\\Exports\\Models\\Export\":30:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";N;s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:1;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:8:{s:7:\"user_id\";i:1;s:8:\"exporter\";s:33:\"App\\Filament\\Exports\\LoanExporter\";s:10:\"total_rows\";i:0;s:9:\"file_disk\";s:5:\"local\";s:10:\"updated_at\";s:19:\"2026-02-22 11:41:00\";s:10:\"created_at\";s:19:\"2026-02-22 11:41:00\";s:2:\"id\";i:1;s:9:\"file_name\";s:14:\"export-1-loans\";}s:11:\"\0*\0original\";a:8:{s:7:\"user_id\";i:1;s:8:\"exporter\";s:33:\"App\\Filament\\Exports\\LoanExporter\";s:10:\"total_rows\";i:0;s:9:\"file_disk\";s:5:\"local\";s:10:\"updated_at\";s:19:\"2026-02-22 11:41:00\";s:10:\"created_at\";s:19:\"2026-02-22 11:41:00\";s:2:\"id\";i:1;s:9:\"file_name\";s:14:\"export-1-loans\";}s:10:\"\0*\0changes\";a:1:{s:9:\"file_name\";s:14:\"export-1-loans\";}s:8:\"\0*\0casts\";a:4:{s:12:\"completed_at\";s:9:\"timestamp\";s:14:\"processed_rows\";s:7:\"integer\";s:10:\"total_rows\";s:7:\"integer\";s:15:\"successful_rows\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:0:{}s:10:\"\0*\0guarded\";a:0:{}}s:12:\"\0*\0columnMap\";a:27:{s:2:\"id\";s:2:\"ID\";s:11:\"loan_number\";s:7:\"Loan ID\";s:11:\"borrower_id\";s:11:\"Customer ID\";s:19:\"borrower.first_name\";s:10:\"First Name\";s:18:\"borrower.last_name\";s:9:\"Last Name\";s:15:\"borrower.mobile\";s:17:\"Main Phone Number\";s:23:\"borrower.identification\";s:10:\"NRC Number\";s:13:\"borrower.tpin\";s:4:\"TPIN\";s:12:\"borrower.dob\";s:13:\"Date of Birth\";s:15:\"borrower.gender\";s:6:\"Gender\";s:16:\"borrower.marital\";s:14:\"Marital Status\";s:14:\"borrower.email\";s:13:\"Email Address\";s:16:\"borrower.address\";s:19:\"Residential Address\";s:13:\"borrower.city\";s:4:\"Town\";s:16:\"principal_amount\";s:24:\"Loan Amount Obtained (K)\";s:16:\"disbursed_amount\";s:20:\"Disbursed Amount (K)\";s:11:\"service_fee\";s:14:\"Processing Fee\";s:13:\"interest_rate\";s:13:\"Interest rate\";s:16:\"repayment_amount\";s:24:\"Loan Repayment Amount(K)\";s:13:\"repaid_amount\";s:22:\"Loan Repaid Amount (K)\";s:7:\"balance\";s:16:\"Loan Balance (K)\";s:19:\"loan_type.loan_name\";s:9:\"Loan Name\";s:13:\"loan_duration\";s:13:\"Loan Duration\";s:19:\"outstanding_balance\";s:25:\"Total Outstanding Balance\";s:17:\"disbursement_date\";s:17:\"Disbursement Date\";s:17:\"disbursement_time\";s:17:\"Disbursement Time\";s:13:\"loan_due_date\";s:8:\"Due Date\";}s:10:\"\0*\0options\";a:0:{}}s:9:\"\0*\0export\";O:45:\"Illuminate\\Contracts\\Database\\ModelIdentifier\":5:{s:5:\"class\";s:38:\"Filament\\Actions\\Exports\\Models\\Export\";s:2:\"id\";i:1;s:9:\"relations\";a:0:{}s:10:\"connection\";s:5:\"mysql\";s:15:\"collectionClass\";N;}s:12:\"\0*\0columnMap\";a:27:{s:2:\"id\";s:2:\"ID\";s:11:\"loan_number\";s:7:\"Loan ID\";s:11:\"borrower_id\";s:11:\"Customer ID\";s:19:\"borrower.first_name\";s:10:\"First Name\";s:18:\"borrower.last_name\";s:9:\"Last Name\";s:15:\"borrower.mobile\";s:17:\"Main Phone Number\";s:23:\"borrower.identification\";s:10:\"NRC Number\";s:13:\"borrower.tpin\";s:4:\"TPIN\";s:12:\"borrower.dob\";s:13:\"Date of Birth\";s:15:\"borrower.gender\";s:6:\"Gender\";s:16:\"borrower.marital\";s:14:\"Marital Status\";s:14:\"borrower.email\";s:13:\"Email Address\";s:16:\"borrower.address\";s:19:\"Residential Address\";s:13:\"borrower.city\";s:4:\"Town\";s:16:\"principal_amount\";s:24:\"Loan Amount Obtained (K)\";s:16:\"disbursed_amount\";s:20:\"Disbursed Amount (K)\";s:11:\"service_fee\";s:14:\"Processing Fee\";s:13:\"interest_rate\";s:13:\"Interest rate\";s:16:\"repayment_amount\";s:24:\"Loan Repayment Amount(K)\";s:13:\"repaid_amount\";s:22:\"Loan Repaid Amount (K)\";s:7:\"balance\";s:16:\"Loan Balance (K)\";s:19:\"loan_type.loan_name\";s:9:\"Loan Name\";s:13:\"loan_duration\";s:13:\"Loan Duration\";s:19:\"outstanding_balance\";s:25:\"Total Outstanding Balance\";s:17:\"disbursement_date\";s:17:\"Disbursement Date\";s:17:\"disbursement_time\";s:17:\"Disbursement Time\";s:13:\"loan_due_date\";s:8:\"Due Date\";}s:10:\"\0*\0formats\";a:2:{i:0;E:47:\"Filament\\Actions\\Exports\\Enums\\ExportFormat:Csv\";i:1;E:48:\"Filament\\Actions\\Exports\\Enums\\ExportFormat:Xlsx\";}s:10:\"\0*\0options\";a:0:{}s:19:\"chainCatchCallbacks\";a:0:{}s:7:\"chained\";a:1:{i:0;s:4343:\"O:44:\"Filament\\Actions\\Exports\\Jobs\\CreateXlsxFile\":4:{s:11:\"\0*\0exporter\";O:33:\"App\\Filament\\Exports\\LoanExporter\":3:{s:9:\"\0*\0export\";O:38:\"Filament\\Actions\\Exports\\Models\\Export\":30:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";N;s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:1;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:8:{s:7:\"user_id\";i:1;s:8:\"exporter\";s:33:\"App\\Filament\\Exports\\LoanExporter\";s:10:\"total_rows\";i:0;s:9:\"file_disk\";s:5:\"local\";s:10:\"updated_at\";s:19:\"2026-02-22 11:41:00\";s:10:\"created_at\";s:19:\"2026-02-22 11:41:00\";s:2:\"id\";i:1;s:9:\"file_name\";s:14:\"export-1-loans\";}s:11:\"\0*\0original\";a:8:{s:7:\"user_id\";i:1;s:8:\"exporter\";s:33:\"App\\Filament\\Exports\\LoanExporter\";s:10:\"total_rows\";i:0;s:9:\"file_disk\";s:5:\"local\";s:10:\"updated_at\";s:19:\"2026-02-22 11:41:00\";s:10:\"created_at\";s:19:\"2026-02-22 11:41:00\";s:2:\"id\";i:1;s:9:\"file_name\";s:14:\"export-1-loans\";}s:10:\"\0*\0changes\";a:1:{s:9:\"file_name\";s:14:\"export-1-loans\";}s:8:\"\0*\0casts\";a:4:{s:12:\"completed_at\";s:9:\"timestamp\";s:14:\"processed_rows\";s:7:\"integer\";s:10:\"total_rows\";s:7:\"integer\";s:15:\"successful_rows\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:0:{}s:10:\"\0*\0guarded\";a:0:{}}s:12:\"\0*\0columnMap\";a:27:{s:2:\"id\";s:2:\"ID\";s:11:\"loan_number\";s:7:\"Loan ID\";s:11:\"borrower_id\";s:11:\"Customer ID\";s:19:\"borrower.first_name\";s:10:\"First Name\";s:18:\"borrower.last_name\";s:9:\"Last Name\";s:15:\"borrower.mobile\";s:17:\"Main Phone Number\";s:23:\"borrower.identification\";s:10:\"NRC Number\";s:13:\"borrower.tpin\";s:4:\"TPIN\";s:12:\"borrower.dob\";s:13:\"Date of Birth\";s:15:\"borrower.gender\";s:6:\"Gender\";s:16:\"borrower.marital\";s:14:\"Marital Status\";s:14:\"borrower.email\";s:13:\"Email Address\";s:16:\"borrower.address\";s:19:\"Residential Address\";s:13:\"borrower.city\";s:4:\"Town\";s:16:\"principal_amount\";s:24:\"Loan Amount Obtained (K)\";s:16:\"disbursed_amount\";s:20:\"Disbursed Amount (K)\";s:11:\"service_fee\";s:14:\"Processing Fee\";s:13:\"interest_rate\";s:13:\"Interest rate\";s:16:\"repayment_amount\";s:24:\"Loan Repayment Amount(K)\";s:13:\"repaid_amount\";s:22:\"Loan Repaid Amount (K)\";s:7:\"balance\";s:16:\"Loan Balance (K)\";s:19:\"loan_type.loan_name\";s:9:\"Loan Name\";s:13:\"loan_duration\";s:13:\"Loan Duration\";s:19:\"outstanding_balance\";s:25:\"Total Outstanding Balance\";s:17:\"disbursement_date\";s:17:\"Disbursement Date\";s:17:\"disbursement_time\";s:17:\"Disbursement Time\";s:13:\"loan_due_date\";s:8:\"Due Date\";}s:10:\"\0*\0options\";a:0:{}}s:9:\"\0*\0export\";O:45:\"Illuminate\\Contracts\\Database\\ModelIdentifier\":5:{s:5:\"class\";s:38:\"Filament\\Actions\\Exports\\Models\\Export\";s:2:\"id\";i:1;s:9:\"relations\";a:0:{}s:10:\"connection\";s:5:\"mysql\";s:15:\"collectionClass\";N;}s:12:\"\0*\0columnMap\";a:27:{s:2:\"id\";s:2:\"ID\";s:11:\"loan_number\";s:7:\"Loan ID\";s:11:\"borrower_id\";s:11:\"Customer ID\";s:19:\"borrower.first_name\";s:10:\"First Name\";s:18:\"borrower.last_name\";s:9:\"Last Name\";s:15:\"borrower.mobile\";s:17:\"Main Phone Number\";s:23:\"borrower.identification\";s:10:\"NRC Number\";s:13:\"borrower.tpin\";s:4:\"TPIN\";s:12:\"borrower.dob\";s:13:\"Date of Birth\";s:15:\"borrower.gender\";s:6:\"Gender\";s:16:\"borrower.marital\";s:14:\"Marital Status\";s:14:\"borrower.email\";s:13:\"Email Address\";s:16:\"borrower.address\";s:19:\"Residential Address\";s:13:\"borrower.city\";s:4:\"Town\";s:16:\"principal_amount\";s:24:\"Loan Amount Obtained (K)\";s:16:\"disbursed_amount\";s:20:\"Disbursed Amount (K)\";s:11:\"service_fee\";s:14:\"Processing Fee\";s:13:\"interest_rate\";s:13:\"Interest rate\";s:16:\"repayment_amount\";s:24:\"Loan Repayment Amount(K)\";s:13:\"repaid_amount\";s:22:\"Loan Repaid Amount (K)\";s:7:\"balance\";s:16:\"Loan Balance (K)\";s:19:\"loan_type.loan_name\";s:9:\"Loan Name\";s:13:\"loan_duration\";s:13:\"Loan Duration\";s:19:\"outstanding_balance\";s:25:\"Total Outstanding Balance\";s:17:\"disbursement_date\";s:17:\"Disbursement Date\";s:17:\"disbursement_time\";s:17:\"Disbursement Time\";s:13:\"loan_due_date\";s:8:\"Due Date\";}s:10:\"\0*\0options\";a:0:{}}\";}}}s:8:\"function\";s:266:\"function (\\Illuminate\\Bus\\Batch $batch) use ($next) {\n                if (! $batch->cancelled()) {\n                    \\Illuminate\\Container\\Container::getInstance()->make(\\Illuminate\\Contracts\\Bus\\Dispatcher::class)->dispatch($next);\n                }\n            }\";s:5:\"scope\";s:27:\"Illuminate\\Bus\\ChainedBatch\";s:4:\"this\";N;s:4:\"self\";s:32:\"0000000000000b4c0000000000000000\";}\";s:4:\"hash\";s:44:\"/uddX099UxaKo5FPwwQO2shNi3KMqDQde+iMtcJ17DQ=\";}}}}',NULL,'1771753261','1771753261',NULL);

DROP TABLE IF EXISTS `loan_agreement_forms`;
CREATE TABLE `loan_agreement_forms` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint(20) unsigned DEFAULT NULL,
  `loan_type_id` bigint(20) unsigned NOT NULL,
  `loan_agreement_text` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `loan_agreement_forms_loan_type_id_foreign` (`loan_type_id`),
  CONSTRAINT `loan_agreement_forms_loan_type_id_foreign` FOREIGN KEY (`loan_type_id`) REFERENCES `loan_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `loan_settlement_forms`;
CREATE TABLE `loan_settlement_forms` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint(20) unsigned DEFAULT NULL,
  `loan_settlement_text` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `loan_types`;
CREATE TABLE `loan_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint(20) unsigned DEFAULT NULL,
  `loan_name` varchar(255) NOT NULL,
  `interest_rate` decimal(10,2) NOT NULL,
  `interest_cycle` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `service_fee` decimal(64,0) NOT NULL,
  `service_fee_type` varchar(255) DEFAULT NULL,
  `service_fee_percentage` decimal(64,2) NOT NULL,
  `service_fee_custom_amount` decimal(64,2) NOT NULL,
  `penalty_fee_type` varchar(255) DEFAULT NULL,
  `penalty_fee_percentage` decimal(64,2) NOT NULL,
  `penalty_fee_custom_amount` decimal(64,2) NOT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  `early_repayment_percent` decimal(5,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `loan_types` VALUES ('1','1550434','Assset loan','5.00','week(s)','2026-02-23 06:43:44','2026-02-23 06:43:44','0','service_fee_percentage','3.00','0.00','penalty_fee_percentage','1.50','0.00',NULL,'2.00');
INSERT INTO `loan_types` VALUES ('2','1550434','Normal loan','3.00','month(s)','2026-02-23 06:44:33','2026-02-28 08:54:22','0','service_fee_percentage','3.00','0.00','penalty_fee_percentage','3.00','0.00',NULL,'2.00');

DROP TABLE IF EXISTS `loans`;
CREATE TABLE `loans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint(20) unsigned DEFAULT NULL,
  `borrower_id` bigint(20) unsigned DEFAULT NULL,
  `loan_type_id` bigint(20) unsigned NOT NULL,
  `loan_status` varchar(255) NOT NULL,
  `ai_credit_score` decimal(5,2) DEFAULT NULL,
  `default_probability` decimal(5,4) DEFAULT NULL,
  `risk_factors` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`risk_factors`)),
  `ai_recommendation` varchar(255) DEFAULT NULL,
  `ai_decision_reason` text DEFAULT NULL,
  `ai_scored_at` timestamp NULL DEFAULT NULL,
  `borrower_monthly_income` decimal(10,2) DEFAULT NULL,
  `borrower_employment_months` int(11) DEFAULT NULL,
  `borrower_existing_debts` decimal(10,2) DEFAULT NULL,
  `borrower_credit_history_months` int(11) DEFAULT NULL,
  `borrower_previous_defaults` int(11) NOT NULL DEFAULT 0,
  `basic_pay` decimal(15,2) DEFAULT NULL,
  `recurring_allowances` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`recurring_allowances`)),
  `total_recurring_allowances` decimal(15,2) DEFAULT NULL,
  `other_allowances` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`other_allowances`)),
  `paye` decimal(15,2) DEFAULT NULL,
  `pension_napsa` decimal(15,2) DEFAULT NULL,
  `health_insurance` decimal(15,2) DEFAULT NULL,
  `other_recurring_deductions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`other_recurring_deductions`)),
  `calculated_net_pay` decimal(15,2) DEFAULT NULL,
  `actual_net_pay_payslip` decimal(15,2) DEFAULT NULL,
  `qualification_status` varchar(255) DEFAULT NULL,
  `qualification_notes` text DEFAULT NULL,
  `monthly_pay` decimal(15,2) DEFAULT NULL,
  `maximum_allowable_emi` decimal(15,2) DEFAULT NULL,
  `existing_loans_emi` decimal(15,2) DEFAULT 0.00,
  `eligible_emi` decimal(15,2) DEFAULT NULL,
  `loan_amount_eligibility` decimal(15,2) DEFAULT NULL,
  `eligibility_interest_rate` decimal(5,2) DEFAULT NULL,
  `loan_period` int(11) DEFAULT NULL,
  `principal_amount` decimal(10,2) NOT NULL,
  `loan_amount` decimal(15,2) DEFAULT NULL,
  `loan_release_date` varchar(255) NOT NULL,
  `loan_duration` varchar(255) NOT NULL,
  `duration_period` varchar(255) NOT NULL,
  `transaction_reference` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `repayment_amount` decimal(10,2) NOT NULL,
  `loan_due_date` varchar(255) NOT NULL,
  `interest_amount` decimal(10,2) NOT NULL,
  `activate_loan_agreement_form` int(11) NOT NULL,
  `loan_agreement_file_path` varchar(255) DEFAULT NULL,
  `interest_rate` varchar(255) DEFAULT NULL,
  `loan_number` varchar(255) NOT NULL,
  `from_this_account` varchar(255) DEFAULT NULL,
  `balance` decimal(64,0) NOT NULL,
  `carried_forward_balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `loan_settlement_file_path` varchar(255) DEFAULT NULL,
  `disbursed_amount` decimal(64,0) NOT NULL,
  `service_fee` decimal(64,0) NOT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  `is_early_settlement` tinyint(1) NOT NULL DEFAULT 0,
  `member_id` bigint(20) unsigned DEFAULT NULL,
  `next_payment_date` date DEFAULT NULL,
  `amount_per_installment` decimal(15,2) DEFAULT NULL,
  `clearance_date` date DEFAULT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `product_description` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `loans_loan_number_unique` (`loan_number`),
  KEY `loans_borrower_id_foreign` (`borrower_id`),
  KEY `loans_loan_type_id_foreign` (`loan_type_id`),
  KEY `loans_member_id_foreign` (`member_id`),
  KEY `loans_product_id_foreign` (`product_id`),
  CONSTRAINT `loans_loan_type_id_foreign` FOREIGN KEY (`loan_type_id`) REFERENCES `loan_types` (`id`) ON DELETE CASCADE,
  CONSTRAINT `loans_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE SET NULL,
  CONSTRAINT `loans_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `loans` VALUES ('2','1550434',NULL,'2','partially_paid',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0.00',NULL,NULL,NULL,NULL,'10000.00','10300.00','2026-02-27','6','week(s)',NULL,'2026-02-27 05:41:57','2026-03-05 07:33:08','10715.70','','415.70','0',NULL,'3','RB-69A1128537356',NULL,'8716','0.00',NULL,'9700','300',NULL,'0','2','2026-03-13','2433.34','2026-04-10',NULL,NULL);

DROP TABLE IF EXISTS `media`;
CREATE TABLE `media` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint(20) unsigned DEFAULT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  `uuid` char(36) DEFAULT NULL,
  `collection_name` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(255) DEFAULT NULL,
  `disk` varchar(255) NOT NULL,
  `conversions_disk` varchar(255) DEFAULT NULL,
  `size` bigint(20) unsigned NOT NULL,
  `manipulations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`manipulations`)),
  `custom_properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`custom_properties`)),
  `generated_conversions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`generated_conversions`)),
  `responsive_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`responsive_images`)),
  `order_column` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `media_uuid_unique` (`uuid`),
  KEY `media_model_type_model_id_index` (`model_type`,`model_id`),
  KEY `media_order_column_index` (`order_column`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `members`;
CREATE TABLE `members` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `id_number` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `last_contribution_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `gender` enum('male','female') DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `marital_status` enum('single','married','divorced','widowed') DEFAULT NULL,
  `physical_address` varchar(255) DEFAULT NULL,
  `town` varchar(255) DEFAULT NULL,
  `postal_code` varchar(255) DEFAULT NULL,
  `county` varchar(255) DEFAULT NULL,
  `sub_county` varchar(255) DEFAULT NULL,
  `village` varchar(255) DEFAULT NULL,
  `nearest_market` varchar(255) DEFAULT NULL,
  `mobile_no` varchar(255) DEFAULT NULL,
  `kin_name` varchar(255) DEFAULT NULL,
  `kin_mobile` varchar(255) DEFAULT NULL,
  `kin_village` varchar(255) DEFAULT NULL,
  `kin_county` varchar(255) DEFAULT NULL,
  `kin_town` varchar(255) DEFAULT NULL,
  `kin_sub_location` varchar(255) DEFAULT NULL,
  `kin_sub_county` varchar(255) DEFAULT NULL,
  `kin_dob` date DEFAULT NULL,
  `business_name` varchar(255) DEFAULT NULL,
  `business_address` varchar(255) DEFAULT NULL,
  `business_county` varchar(255) DEFAULT NULL,
  `business_town` varchar(255) DEFAULT NULL,
  `business_sub_county` varchar(255) DEFAULT NULL,
  `business_postal_code` varchar(255) DEFAULT NULL,
  `guarantor1_name` varchar(255) DEFAULT NULL,
  `guarantor1_mobile` varchar(255) DEFAULT NULL,
  `guarantor1_relationship` varchar(255) DEFAULT NULL,
  `guarantor2_name` varchar(255) DEFAULT NULL,
  `guarantor2_mobile` varchar(255) DEFAULT NULL,
  `guarantor2_relationship` varchar(255) DEFAULT NULL,
  `assigned_officer` bigint(20) unsigned DEFAULT NULL,
  `group_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `members_id_number_unique` (`id_number`),
  KEY `members_assigned_officer_foreign` (`assigned_officer`),
  KEY `members_group_id_foreign` (`group_id`),
  CONSTRAINT `members_assigned_officer_foreign` FOREIGN KEY (`assigned_officer`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `members_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `members` VALUES ('2','','1234567892',NULL,NULL,NULL,'active',NULL,'2026-02-25 21:23:05','2026-03-06 07:16:07','MARY','JANE','AUMA','female','1989-10-17','divorced','SEME KADERO MARKET','KADERO',NULL,'KISUMU',NULL,'KADERO','KADERO MARKET','123456789','JOHN DOE','0987654321','KOBURA','KISUMU','KOBURA','KISUMU WEST','KOBURA','2004-06-08','kadel fish shop','kadel ','KISUMU','KISUMU','KIUMU WEST','12 40123',NULL,NULL,NULL,NULL,NULL,NULL,'1','2');
INSERT INTO `members` VALUES ('3','','45678932',NULL,NULL,NULL,'active',NULL,'2026-02-25 21:44:18','2026-02-25 21:44:18','AH','ATIENO','ANYANGO','female','1995-10-17','married','SEME KADERO MARKET','KADERO','28 40123 KISUMU','KISUMU','KISUMU WEST','KADERO','KADERO MARKET','011112345','MARY JANE AUMA','0123456789','KOBURA','KISUMU','KADERO','KISUMU WEST','KOBURA','2005-11-16',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1');
INSERT INTO `members` VALUES ('4','','23456789',NULL,NULL,NULL,'active',NULL,'2026-02-27 08:49:50','2026-02-27 08:49:50','JANE','DOE','ANYANGO','female','1990-09-27','married','KANYAKWAR','KANYAKWAR','28 40123 KISUMU','KISUMU','KISUMU WEST','KANYAKWAR','KIBOSWA','0712345678','JOHN DOE','9876543232','KOBURA','KISUMU','KIBOSWA','KISUMU WEST','KOBURA','1995-06-14',NULL,NULL,NULL,'KADERO',NULL,NULL,'MARY JANE AUMA','0123456789',NULL,'MARY JANE AUMA','0123456789',NULL,NULL,'2');

DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint(20) unsigned DEFAULT NULL,
  `message` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `responseText` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` VALUES ('1','2014_10_12_000000_create_users_table','1');
INSERT INTO `migrations` VALUES ('2','2014_10_12_100000_create_password_reset_tokens_table','1');
INSERT INTO `migrations` VALUES ('3','2014_10_12_200000_add_two_factor_columns_to_users_table','1');
INSERT INTO `migrations` VALUES ('4','2018_11_06_222923_create_transactions_table','1');
INSERT INTO `migrations` VALUES ('5','2018_11_07_192923_create_transfers_table','1');
INSERT INTO `migrations` VALUES ('6','2018_11_15_124230_create_wallets_table','1');
INSERT INTO `migrations` VALUES ('7','2019_08_19_000000_create_failed_jobs_table','1');
INSERT INTO `migrations` VALUES ('8','2019_12_14_000001_create_personal_access_tokens_table','1');
INSERT INTO `migrations` VALUES ('9','2021_11_02_202021_update_wallets_uuid_table','1');
INSERT INTO `migrations` VALUES ('10','2023_11_10_070254_create_sessions_table','1');
INSERT INTO `migrations` VALUES ('11','2023_11_10_125034_create_borrowers_table','1');
INSERT INTO `migrations` VALUES ('12','2023_11_10_126001_create_borrower_files_table','1');
INSERT INTO `migrations` VALUES ('13','2023_11_14_095618_create_actvity_logs_table','1');
INSERT INTO `migrations` VALUES ('14','2023_11_22_102257_next_of_kin','1');
INSERT INTO `migrations` VALUES ('15','2023_11_22_102529_bank_details','1');
INSERT INTO `migrations` VALUES ('16','2023_11_22_115100_borrower_attachments','1');
INSERT INTO `migrations` VALUES ('17','2023_11_22_123811_create_media_table','1');
INSERT INTO `migrations` VALUES ('18','2023_11_23_110138_create_loan_types_table','1');
INSERT INTO `migrations` VALUES ('19','2023_11_23_113837_create_loans_table','1');
INSERT INTO `migrations` VALUES ('20','2023_11_23_190433_concartenation','1');
INSERT INTO `migrations` VALUES ('21','2023_11_23_211428_repayments_due_daten','1');
INSERT INTO `migrations` VALUES ('22','2023_11_23_231004_interest_amount','1');
INSERT INTO `migrations` VALUES ('23','2023_11_30_190002_amount','1');
INSERT INTO `migrations` VALUES ('24','2023_12_02_204934_create_expense_categories_table','1');
INSERT INTO `migrations` VALUES ('25','2023_12_02_204948_create_expenses_table','1');
INSERT INTO `migrations` VALUES ('26','2023_12_04_211509_from_this_account','1');
INSERT INTO `migrations` VALUES ('27','2023_12_05_210403_create_loan_agreement_forms_table','1');
INSERT INTO `migrations` VALUES ('28','2023_12_06_130039_activate_loan_agreement_form','1');
INSERT INTO `migrations` VALUES ('29','2023_12_06_135737_loan_agreement_file_path','1');
INSERT INTO `migrations` VALUES ('30','2023_12_06_154947_interest_rate','1');
INSERT INTO `migrations` VALUES ('31','2023_12_07_151705_loan_number','1');
INSERT INTO `migrations` VALUES ('32','2023_12_07_153050_from_this_account','1');
INSERT INTO `migrations` VALUES ('33','2023_12_07_183306_balance','1');
INSERT INTO `migrations` VALUES ('34','2023_12_13_083455_create_repayments_table','1');
INSERT INTO `migrations` VALUES ('35','2023_12_13_092154_transaction_number','1');
INSERT INTO `migrations` VALUES ('36','2023_12_13_105905_loan_number','1');
INSERT INTO `migrations` VALUES ('37','2023_12_16_130656_create_loan_settlement_forms_table','1');
INSERT INTO `migrations` VALUES ('38','2023_12_16_142458_loan_settlement_file_path','1');
INSERT INTO `migrations` VALUES ('39','2024_01_12_094938_create_third_parties_table','1');
INSERT INTO `migrations` VALUES ('40','2024_01_12_135033_create_permission_tables','1');
INSERT INTO `migrations` VALUES ('41','2024_08_13_222531_create_job_batches_table','1');
INSERT INTO `migrations` VALUES ('42','2024_08_13_222619_create_notifications_table','1');
INSERT INTO `migrations` VALUES ('43','2024_08_13_222633_create_imports_table','1');
INSERT INTO `migrations` VALUES ('44','2024_08_13_222634_create_failed_import_rows_table','1');
INSERT INTO `migrations` VALUES ('45','2024_08_13_225543_create_exports_table','1');
INSERT INTO `migrations` VALUES ('46','2024_08_22_182047_borrower_added_by','1');
INSERT INTO `migrations` VALUES ('47','2025_01_15_100000_create_direct_debit_mandate_settings_table','1');
INSERT INTO `migrations` VALUES ('48','2025_01_20_100000_create_employees_table','1');
INSERT INTO `migrations` VALUES ('49','2025_01_20_100001_create_tax_bands_table','1');
INSERT INTO `migrations` VALUES ('50','2025_01_20_100002_create_payroll_runs_table','1');
INSERT INTO `migrations` VALUES ('51','2025_01_20_100003_create_payslips_table','1');
INSERT INTO `migrations` VALUES ('52','2025_01_20_100004_create_salary_scales_table','1');
INSERT INTO `migrations` VALUES ('53','2025_01_20_100005_add_salary_scale_id_to_employees_table','1');
INSERT INTO `migrations` VALUES ('54','2025_07_08_152936_create_activity_log_table','1');
INSERT INTO `migrations` VALUES ('55','2025_07_08_152937_add_event_column_to_activity_log_table','1');
INSERT INTO `migrations` VALUES ('56','2025_07_08_152938_add_batch_uuid_column_to_activity_log_table','1');
INSERT INTO `migrations` VALUES ('57','2025_07_08_181850_create_messages_table','1');
INSERT INTO `migrations` VALUES ('58','2025_07_20_181531_service_fee','1');
INSERT INTO `migrations` VALUES ('59','2025_07_20_182706_disbursement_amount','1');
INSERT INTO `migrations` VALUES ('60','2025_07_20_185806_service_fee','1');
INSERT INTO `migrations` VALUES ('61','2025_07_24_210059_add_organization_id_to_all_tables','1');
INSERT INTO `migrations` VALUES ('62','2025_07_28_000541_create_payments_table','1');
INSERT INTO `migrations` VALUES ('63','2025_08_17_131631_service_fees','1');
INSERT INTO `migrations` VALUES ('64','2025_08_20_082131_create_branches_table','1');
INSERT INTO `migrations` VALUES ('65','2025_08_20_090711_add_branch_ids','1');
INSERT INTO `migrations` VALUES ('66','2025_08_20_124252_organization_id','1');
INSERT INTO `migrations` VALUES ('67','2025_10_05_135732_create_asset_categories_table','1');
INSERT INTO `migrations` VALUES ('68','2025_10_05_140705_create_assets_table','1');
INSERT INTO `migrations` VALUES ('69','2025_10_05_164617_early_statement_reduction','1');
INSERT INTO `migrations` VALUES ('70','2025_10_06_102716_is_early_settlement','1');
INSERT INTO `migrations` VALUES ('71','2025_10_17_182053_add_ai_credit_scoring_to_loans_table','1');
INSERT INTO `migrations` VALUES ('72','2025_10_17_222022_organization_id','1');
INSERT INTO `migrations` VALUES ('73','2025_10_17_222218_organization_id','1');
INSERT INTO `migrations` VALUES ('74','2025_10_17_222906_branch_id','1');
INSERT INTO `migrations` VALUES ('75','2025_10_17_222923_branch_id','1');
INSERT INTO `migrations` VALUES ('76','2025_11_23_160332_add_company_profile_fields_to_users_table','1');
INSERT INTO `migrations` VALUES ('77','2025_11_23_161258_add_company_representative_contact_fields_to_users_table','1');
INSERT INTO `migrations` VALUES ('78','2025_11_23_164145_make_branch_manager_nullable_in_branches_table','1');
INSERT INTO `migrations` VALUES ('79','2025_11_23_170000_add_civil_service_calculator_to_loans_table','1');
INSERT INTO `migrations` VALUES ('80','2025_11_24_180022_update_direct_debit_mandate_settings_reference_number','1');
INSERT INTO `migrations` VALUES ('81','2025_11_25_091331_add_brand_color_to_users_table','1');
INSERT INTO `migrations` VALUES ('82','2025_12_15_082153_add_new_pmec_eligibility_calculator_to_loans_table','1');
INSERT INTO `migrations` VALUES ('83','2025_12_15_093933_add_loan_period_to_loans_table','1');
INSERT INTO `migrations` VALUES ('84','2026_02_22_083405_create_members_table','2');
INSERT INTO `migrations` VALUES ('85','2026_02_22_083445_create_groups_table','2');
INSERT INTO `migrations` VALUES ('86','2026_02_22_083601_create_group_members_table','2');
INSERT INTO `migrations` VALUES ('87','2026_02_22_083614_create_savings_table','2');
INSERT INTO `migrations` VALUES ('88','2026_02_22_083715_create_penalties_table','2');
INSERT INTO `migrations` VALUES ('89','2026_02_22_083731_create_supervisor_groups_table','2');
INSERT INTO `migrations` VALUES ('90','2026_02_24_190737_add_details_to_groups_table','3');
INSERT INTO `migrations` VALUES ('91','2026_02_25_182249_add_details_to_members_table','4');
INSERT INTO `migrations` VALUES ('92','2026_02_25_183353_add_assigned_officer_to_members_table','5');
INSERT INTO `migrations` VALUES ('93','2026_02_25_211501_add_group_id_to_members_table','6');
INSERT INTO `migrations` VALUES ('94','2026_02_26_091708_add_member_id_to_loans_table','7');
INSERT INTO `migrations` VALUES ('95','2026_02_26_000001_add_fields_to_loans_table','8');
INSERT INTO `migrations` VALUES ('96','2026_02_26_200000_add_loan_amount_to_loans_table','8');
INSERT INTO `migrations` VALUES ('97','2026_02_27_053503_fix_borrower_id_constraint_on_loans_table','9');
INSERT INTO `migrations` VALUES ('98','2026_02_27_060000_add_carried_forward_balance_to_loans_table','10');
INSERT INTO `migrations` VALUES ('99','2026_03_05_000001_create_products_table','11');

DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  `organization_id` bigint(20) unsigned DEFAULT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  `organization_id` bigint(20) unsigned DEFAULT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `model_has_roles` VALUES ('1','App\\Models\\User','1',NULL,NULL);
INSERT INTO `model_has_roles` VALUES ('3','App\\Models\\User','2',NULL,NULL);
INSERT INTO `model_has_roles` VALUES ('3','App\\Models\\User','3',NULL,NULL);

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `organization_id` bigint(20) unsigned DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) unsigned NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `organization_id` bigint(20) unsigned DEFAULT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint(20) unsigned DEFAULT NULL,
  `payer_id` bigint(20) unsigned DEFAULT NULL,
  `payment_amount` decimal(64,0) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `payment_made_at` datetime NOT NULL,
  `payment_expires_at` datetime NOT NULL,
  `transaction_reference` varchar(255) DEFAULT NULL,
  `gateway` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payments_payer_id_foreign` (`payer_id`),
  CONSTRAINT `payments_payer_id_foreign` FOREIGN KEY (`payer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `payments` VALUES ('1','1550434','1','0',NULL,'2026-02-22 00:09:02','2026-03-01 00:09:02','499625','7 DAYS FREE TRIAL','2026-02-22 00:09:02','2026-02-22 00:09:02',NULL);
INSERT INTO `payments` VALUES ('2','2689064','2','0',NULL,'2026-02-26 06:58:02','2026-03-05 06:58:02','459532','7 DAYS FREE TRIAL','2026-02-26 06:58:02','2026-02-26 06:58:02',NULL);
INSERT INTO `payments` VALUES ('3','3629395','3','0',NULL,'2026-02-27 17:29:56','2026-03-06 17:29:56','106242','7 DAYS FREE TRIAL','2026-02-27 17:29:56','2026-02-27 17:29:56',NULL);

DROP TABLE IF EXISTS `payroll_runs`;
CREATE TABLE `payroll_runs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint(20) unsigned DEFAULT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  `payroll_number` varchar(255) NOT NULL,
  `period_name` varchar(255) NOT NULL,
  `pay_period_start` date NOT NULL,
  `pay_period_end` date NOT NULL,
  `payment_date` date NOT NULL,
  `status` enum('draft','processing','completed','cancelled') NOT NULL DEFAULT 'draft',
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payroll_runs_payroll_number_unique` (`payroll_number`),
  KEY `payroll_runs_created_by_foreign` (`created_by`),
  CONSTRAINT `payroll_runs_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `payslips`;
CREATE TABLE `payslips` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint(20) unsigned DEFAULT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  `payroll_run_id` bigint(20) unsigned NOT NULL,
  `employee_id` bigint(20) unsigned NOT NULL,
  `payslip_number` varchar(255) NOT NULL,
  `basic_salary` decimal(10,2) NOT NULL,
  `housing_allowance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `transport_allowance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `medical_allowance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `other_allowances` decimal(10,2) NOT NULL DEFAULT 0.00,
  `gross_salary` decimal(10,2) NOT NULL,
  `paye` decimal(10,2) NOT NULL DEFAULT 0.00,
  `napsa` decimal(10,2) NOT NULL DEFAULT 0.00,
  `nhima` decimal(10,2) NOT NULL DEFAULT 0.00,
  `other_deductions` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_deductions` decimal(10,2) NOT NULL,
  `net_pay` decimal(10,2) NOT NULL,
  `payslip_sent` tinyint(1) NOT NULL DEFAULT 0,
  `payslip_sent_at` datetime DEFAULT NULL,
  `payslip_file_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payslips_payslip_number_unique` (`payslip_number`),
  KEY `payslips_payroll_run_id_foreign` (`payroll_run_id`),
  KEY `payslips_employee_id_foreign` (`employee_id`),
  CONSTRAINT `payslips_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payslips_payroll_run_id_foreign` FOREIGN KEY (`payroll_run_id`) REFERENCES `payroll_runs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `penalties`;
CREATE TABLE `penalties` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `loan_id` bigint(20) unsigned NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `penalty_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `penalties_loan_id_foreign` (`loan_id`),
  CONSTRAINT `penalties_loan_id_foreign` FOREIGN KEY (`loan_id`) REFERENCES `loans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=468 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `permissions` VALUES ('1',NULL,'view_role','web','2026-02-22 00:06:14','2026-02-22 00:06:14',NULL);
INSERT INTO `permissions` VALUES ('2',NULL,'view_any_role','web','2026-02-22 00:06:14','2026-02-22 00:06:14',NULL);
INSERT INTO `permissions` VALUES ('3',NULL,'create_role','web','2026-02-22 00:06:14','2026-02-22 00:06:14',NULL);
INSERT INTO `permissions` VALUES ('4',NULL,'update_role','web','2026-02-22 00:06:14','2026-02-22 00:06:14',NULL);
INSERT INTO `permissions` VALUES ('5',NULL,'delete_role','web','2026-02-22 00:06:14','2026-02-22 00:06:14',NULL);
INSERT INTO `permissions` VALUES ('6',NULL,'delete_any_role','web','2026-02-22 00:06:14','2026-02-22 00:06:14',NULL);
INSERT INTO `permissions` VALUES ('7',NULL,'view_activitylog','web','2026-02-22 00:06:54','2026-02-22 00:06:54',NULL);
INSERT INTO `permissions` VALUES ('8',NULL,'view_any_activitylog','web','2026-02-22 00:06:54','2026-02-22 00:06:54',NULL);
INSERT INTO `permissions` VALUES ('9',NULL,'create_activitylog','web','2026-02-22 00:06:54','2026-02-22 00:06:54',NULL);
INSERT INTO `permissions` VALUES ('10',NULL,'update_activitylog','web','2026-02-22 00:06:54','2026-02-22 00:06:54',NULL);
INSERT INTO `permissions` VALUES ('11',NULL,'restore_activitylog','web','2026-02-22 00:06:54','2026-02-22 00:06:54',NULL);
INSERT INTO `permissions` VALUES ('12',NULL,'restore_any_activitylog','web','2026-02-22 00:06:54','2026-02-22 00:06:54',NULL);
INSERT INTO `permissions` VALUES ('13',NULL,'replicate_activitylog','web','2026-02-22 00:06:54','2026-02-22 00:06:54',NULL);
INSERT INTO `permissions` VALUES ('14',NULL,'reorder_activitylog','web','2026-02-22 00:06:54','2026-02-22 00:06:54',NULL);
INSERT INTO `permissions` VALUES ('15',NULL,'delete_activitylog','web','2026-02-22 00:06:54','2026-02-22 00:06:54',NULL);
INSERT INTO `permissions` VALUES ('16',NULL,'delete_any_activitylog','web','2026-02-22 00:06:54','2026-02-22 00:06:54',NULL);
INSERT INTO `permissions` VALUES ('17',NULL,'force_delete_activitylog','web','2026-02-22 00:06:54','2026-02-22 00:06:54',NULL);
INSERT INTO `permissions` VALUES ('18',NULL,'force_delete_any_activitylog','web','2026-02-22 00:06:54','2026-02-22 00:06:54',NULL);
INSERT INTO `permissions` VALUES ('19',NULL,'view_asset','web','2026-02-22 00:06:54','2026-02-22 00:06:54',NULL);
INSERT INTO `permissions` VALUES ('20',NULL,'view_any_asset','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('21',NULL,'create_asset','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('22',NULL,'update_asset','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('23',NULL,'restore_asset','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('24',NULL,'restore_any_asset','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('25',NULL,'replicate_asset','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('26',NULL,'reorder_asset','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('27',NULL,'delete_asset','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('28',NULL,'delete_any_asset','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('29',NULL,'force_delete_asset','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('30',NULL,'force_delete_any_asset','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('31',NULL,'view_asset::category','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('32',NULL,'view_any_asset::category','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('33',NULL,'create_asset::category','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('34',NULL,'update_asset::category','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('35',NULL,'restore_asset::category','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('36',NULL,'restore_any_asset::category','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('37',NULL,'replicate_asset::category','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('38',NULL,'reorder_asset::category','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('39',NULL,'delete_asset::category','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('40',NULL,'delete_any_asset::category','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('41',NULL,'force_delete_asset::category','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('42',NULL,'force_delete_any_asset::category','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('43',NULL,'view_borrower','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('44',NULL,'view_any_borrower','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('45',NULL,'create_borrower','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('46',NULL,'update_borrower','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('47',NULL,'restore_borrower','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('48',NULL,'restore_any_borrower','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('49',NULL,'replicate_borrower','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('50',NULL,'reorder_borrower','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('51',NULL,'delete_borrower','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('52',NULL,'delete_any_borrower','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('53',NULL,'force_delete_borrower','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('54',NULL,'force_delete_any_borrower','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('55',NULL,'view_branches','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('56',NULL,'view_any_branches','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('57',NULL,'create_branches','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('58',NULL,'update_branches','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('59',NULL,'restore_branches','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('60',NULL,'restore_any_branches','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('61',NULL,'replicate_branches','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('62',NULL,'reorder_branches','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('63',NULL,'delete_branches','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('64',NULL,'delete_any_branches','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('65',NULL,'force_delete_branches','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('66',NULL,'force_delete_any_branches','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('67',NULL,'view_bulk::repayments','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('68',NULL,'view_any_bulk::repayments','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('69',NULL,'create_bulk::repayments','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('70',NULL,'update_bulk::repayments','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('71',NULL,'restore_bulk::repayments','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('72',NULL,'restore_any_bulk::repayments','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('73',NULL,'replicate_bulk::repayments','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('74',NULL,'reorder_bulk::repayments','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('75',NULL,'delete_bulk::repayments','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('76',NULL,'delete_any_bulk::repayments','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('77',NULL,'force_delete_bulk::repayments','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('78',NULL,'force_delete_any_bulk::repayments','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('79',NULL,'view_contact::messages','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('80',NULL,'view_any_contact::messages','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('81',NULL,'create_contact::messages','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('82',NULL,'update_contact::messages','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('83',NULL,'restore_contact::messages','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('84',NULL,'restore_any_contact::messages','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('85',NULL,'replicate_contact::messages','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('86',NULL,'reorder_contact::messages','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('87',NULL,'delete_contact::messages','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('88',NULL,'delete_any_contact::messages','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('89',NULL,'force_delete_contact::messages','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('90',NULL,'force_delete_any_contact::messages','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('91',NULL,'view_direct::debit::mandate::settings','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('92',NULL,'view_any_direct::debit::mandate::settings','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('93',NULL,'create_direct::debit::mandate::settings','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('94',NULL,'update_direct::debit::mandate::settings','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('95',NULL,'restore_direct::debit::mandate::settings','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('96',NULL,'restore_any_direct::debit::mandate::settings','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('97',NULL,'replicate_direct::debit::mandate::settings','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('98',NULL,'reorder_direct::debit::mandate::settings','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('99',NULL,'delete_direct::debit::mandate::settings','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('100',NULL,'delete_any_direct::debit::mandate::settings','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('101',NULL,'force_delete_direct::debit::mandate::settings','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('102',NULL,'force_delete_any_direct::debit::mandate::settings','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('103',NULL,'view_employee','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('104',NULL,'view_any_employee','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('105',NULL,'create_employee','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('106',NULL,'update_employee','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('107',NULL,'restore_employee','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('108',NULL,'restore_any_employee','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('109',NULL,'replicate_employee','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('110',NULL,'reorder_employee','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('111',NULL,'delete_employee','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('112',NULL,'delete_any_employee','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('113',NULL,'force_delete_employee','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('114',NULL,'force_delete_any_employee','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('115',NULL,'view_expense','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('116',NULL,'view_any_expense','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('117',NULL,'create_expense','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('118',NULL,'update_expense','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('119',NULL,'restore_expense','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('120',NULL,'restore_any_expense','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('121',NULL,'replicate_expense','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('122',NULL,'reorder_expense','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('123',NULL,'delete_expense','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('124',NULL,'delete_any_expense','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('125',NULL,'force_delete_expense','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('126',NULL,'force_delete_any_expense','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('127',NULL,'view_expense::category','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('128',NULL,'view_any_expense::category','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('129',NULL,'create_expense::category','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('130',NULL,'update_expense::category','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('131',NULL,'restore_expense::category','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('132',NULL,'restore_any_expense::category','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('133',NULL,'replicate_expense::category','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('134',NULL,'reorder_expense::category','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('135',NULL,'delete_expense::category','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('136',NULL,'delete_any_expense::category','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('137',NULL,'force_delete_expense::category','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('138',NULL,'force_delete_any_expense::category','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('139',NULL,'view_loan','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('140',NULL,'view_any_loan','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('141',NULL,'create_loan','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('142',NULL,'update_loan','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('143',NULL,'restore_loan','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('144',NULL,'restore_any_loan','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('145',NULL,'replicate_loan','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('146',NULL,'reorder_loan','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('147',NULL,'delete_loan','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('148',NULL,'delete_any_loan','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('149',NULL,'force_delete_loan','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('150',NULL,'force_delete_any_loan','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('151',NULL,'view_loan::agreement::forms','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('152',NULL,'view_any_loan::agreement::forms','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('153',NULL,'create_loan::agreement::forms','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('154',NULL,'update_loan::agreement::forms','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('155',NULL,'restore_loan::agreement::forms','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('156',NULL,'restore_any_loan::agreement::forms','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('157',NULL,'replicate_loan::agreement::forms','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('158',NULL,'reorder_loan::agreement::forms','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('159',NULL,'delete_loan::agreement::forms','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('160',NULL,'delete_any_loan::agreement::forms','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('161',NULL,'force_delete_loan::agreement::forms','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('162',NULL,'force_delete_any_loan::agreement::forms','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('163',NULL,'view_loan::restructure','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('164',NULL,'view_any_loan::restructure','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('165',NULL,'create_loan::restructure','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('166',NULL,'update_loan::restructure','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('167',NULL,'restore_loan::restructure','web','2026-02-22 00:06:55','2026-02-22 00:06:55',NULL);
INSERT INTO `permissions` VALUES ('168',NULL,'restore_any_loan::restructure','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('169',NULL,'replicate_loan::restructure','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('170',NULL,'reorder_loan::restructure','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('171',NULL,'delete_loan::restructure','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('172',NULL,'delete_any_loan::restructure','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('173',NULL,'force_delete_loan::restructure','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('174',NULL,'force_delete_any_loan::restructure','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('175',NULL,'view_loan::roll::over','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('176',NULL,'view_any_loan::roll::over','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('177',NULL,'create_loan::roll::over','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('178',NULL,'update_loan::roll::over','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('179',NULL,'restore_loan::roll::over','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('180',NULL,'restore_any_loan::roll::over','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('181',NULL,'replicate_loan::roll::over','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('182',NULL,'reorder_loan::roll::over','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('183',NULL,'delete_loan::roll::over','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('184',NULL,'delete_any_loan::roll::over','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('185',NULL,'force_delete_loan::roll::over','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('186',NULL,'force_delete_any_loan::roll::over','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('187',NULL,'view_loan::settlement::forms','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('188',NULL,'view_any_loan::settlement::forms','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('189',NULL,'create_loan::settlement::forms','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('190',NULL,'update_loan::settlement::forms','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('191',NULL,'restore_loan::settlement::forms','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('192',NULL,'restore_any_loan::settlement::forms','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('193',NULL,'replicate_loan::settlement::forms','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('194',NULL,'reorder_loan::settlement::forms','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('195',NULL,'delete_loan::settlement::forms','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('196',NULL,'delete_any_loan::settlement::forms','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('197',NULL,'force_delete_loan::settlement::forms','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('198',NULL,'force_delete_any_loan::settlement::forms','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('199',NULL,'view_loan::type','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('200',NULL,'view_any_loan::type','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('201',NULL,'create_loan::type','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('202',NULL,'update_loan::type','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('203',NULL,'restore_loan::type','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('204',NULL,'restore_any_loan::type','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('205',NULL,'replicate_loan::type','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('206',NULL,'reorder_loan::type','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('207',NULL,'delete_loan::type','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('208',NULL,'delete_any_loan::type','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('209',NULL,'force_delete_loan::type','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('210',NULL,'force_delete_any_loan::type','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('211',NULL,'view_messages','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('212',NULL,'view_any_messages','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('213',NULL,'create_messages','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('214',NULL,'update_messages','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('215',NULL,'restore_messages','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('216',NULL,'restore_any_messages','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('217',NULL,'replicate_messages','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('218',NULL,'reorder_messages','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('219',NULL,'delete_messages','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('220',NULL,'delete_any_messages','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('221',NULL,'force_delete_messages','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('222',NULL,'force_delete_any_messages','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('223',NULL,'view_payroll::run','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('224',NULL,'view_any_payroll::run','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('225',NULL,'create_payroll::run','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('226',NULL,'update_payroll::run','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('227',NULL,'restore_payroll::run','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('228',NULL,'restore_any_payroll::run','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('229',NULL,'replicate_payroll::run','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('230',NULL,'reorder_payroll::run','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('231',NULL,'delete_payroll::run','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('232',NULL,'delete_any_payroll::run','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('233',NULL,'force_delete_payroll::run','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('234',NULL,'force_delete_any_payroll::run','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('235',NULL,'view_payslip','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('236',NULL,'view_any_payslip','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('237',NULL,'create_payslip','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('238',NULL,'update_payslip','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('239',NULL,'restore_payslip','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('240',NULL,'restore_any_payslip','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('241',NULL,'replicate_payslip','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('242',NULL,'reorder_payslip','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('243',NULL,'delete_payslip','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('244',NULL,'delete_any_payslip','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('245',NULL,'force_delete_payslip','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('246',NULL,'force_delete_any_payslip','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('247',NULL,'view_repayments','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('248',NULL,'view_any_repayments','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('249',NULL,'create_repayments','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('250',NULL,'update_repayments','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('251',NULL,'restore_repayments','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('252',NULL,'restore_any_repayments','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('253',NULL,'replicate_repayments','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('254',NULL,'reorder_repayments','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('255',NULL,'delete_repayments','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('256',NULL,'delete_any_repayments','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('257',NULL,'force_delete_repayments','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('258',NULL,'force_delete_any_repayments','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('259',NULL,'view_salary::scale','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('260',NULL,'view_any_salary::scale','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('261',NULL,'create_salary::scale','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('262',NULL,'update_salary::scale','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('263',NULL,'restore_salary::scale','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('264',NULL,'restore_any_salary::scale','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('265',NULL,'replicate_salary::scale','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('266',NULL,'reorder_salary::scale','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('267',NULL,'delete_salary::scale','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('268',NULL,'delete_any_salary::scale','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('269',NULL,'force_delete_salary::scale','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('270',NULL,'force_delete_any_salary::scale','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('271',NULL,'view_subscriptions','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('272',NULL,'view_any_subscriptions','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('273',NULL,'create_subscriptions','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('274',NULL,'update_subscriptions','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('275',NULL,'restore_subscriptions','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('276',NULL,'restore_any_subscriptions','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('277',NULL,'replicate_subscriptions','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('278',NULL,'reorder_subscriptions','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('279',NULL,'delete_subscriptions','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('280',NULL,'delete_any_subscriptions','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('281',NULL,'force_delete_subscriptions','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('282',NULL,'force_delete_any_subscriptions','web','2026-02-22 00:06:56','2026-02-22 00:06:56',NULL);
INSERT INTO `permissions` VALUES ('283',NULL,'view_switch::branch','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('284',NULL,'view_any_switch::branch','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('285',NULL,'create_switch::branch','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('286',NULL,'update_switch::branch','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('287',NULL,'restore_switch::branch','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('288',NULL,'restore_any_switch::branch','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('289',NULL,'replicate_switch::branch','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('290',NULL,'reorder_switch::branch','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('291',NULL,'delete_switch::branch','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('292',NULL,'delete_any_switch::branch','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('293',NULL,'force_delete_switch::branch','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('294',NULL,'force_delete_any_switch::branch','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('295',NULL,'view_tax::band','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('296',NULL,'view_any_tax::band','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('297',NULL,'create_tax::band','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('298',NULL,'update_tax::band','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('299',NULL,'restore_tax::band','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('300',NULL,'restore_any_tax::band','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('301',NULL,'replicate_tax::band','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('302',NULL,'reorder_tax::band','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('303',NULL,'delete_tax::band','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('304',NULL,'delete_any_tax::band','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('305',NULL,'force_delete_tax::band','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('306',NULL,'force_delete_any_tax::band','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('307',NULL,'view_third::party','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('308',NULL,'view_any_third::party','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('309',NULL,'create_third::party','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('310',NULL,'update_third::party','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('311',NULL,'restore_third::party','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('312',NULL,'restore_any_third::party','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('313',NULL,'replicate_third::party','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('314',NULL,'reorder_third::party','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('315',NULL,'delete_third::party','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('316',NULL,'delete_any_third::party','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('317',NULL,'force_delete_third::party','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('318',NULL,'force_delete_any_third::party','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('319',NULL,'view_transactions','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('320',NULL,'view_any_transactions','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('321',NULL,'create_transactions','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('322',NULL,'update_transactions','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('323',NULL,'restore_transactions','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('324',NULL,'restore_any_transactions','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('325',NULL,'replicate_transactions','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('326',NULL,'reorder_transactions','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('327',NULL,'delete_transactions','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('328',NULL,'delete_any_transactions','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('329',NULL,'force_delete_transactions','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('330',NULL,'force_delete_any_transactions','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('331',NULL,'view_transfers','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('332',NULL,'view_any_transfers','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('333',NULL,'create_transfers','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('334',NULL,'update_transfers','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('335',NULL,'restore_transfers','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('336',NULL,'restore_any_transfers','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('337',NULL,'replicate_transfers','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('338',NULL,'reorder_transfers','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('339',NULL,'delete_transfers','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('340',NULL,'delete_any_transfers','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('341',NULL,'force_delete_transfers','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('342',NULL,'force_delete_any_transfers','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('343',NULL,'view_user','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('344',NULL,'view_any_user','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('345',NULL,'create_user','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('346',NULL,'update_user','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('347',NULL,'restore_user','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('348',NULL,'restore_any_user','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('349',NULL,'replicate_user','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('350',NULL,'reorder_user','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('351',NULL,'delete_user','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('352',NULL,'delete_any_user','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('353',NULL,'force_delete_user','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('354',NULL,'force_delete_any_user','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('355',NULL,'view_wallet','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('356',NULL,'view_any_wallet','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('357',NULL,'create_wallet','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('358',NULL,'update_wallet','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('359',NULL,'restore_wallet','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('360',NULL,'restore_any_wallet','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('361',NULL,'replicate_wallet','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('362',NULL,'reorder_wallet','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('363',NULL,'delete_wallet','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('364',NULL,'delete_any_wallet','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('365',NULL,'force_delete_wallet','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('366',NULL,'force_delete_any_wallet','web','2026-02-22 00:06:57','2026-02-22 00:06:57',NULL);
INSERT INTO `permissions` VALUES ('367',NULL,'page_ProfileCompletion','web','2026-02-22 00:06:58','2026-02-22 00:06:58',NULL);
INSERT INTO `permissions` VALUES ('368',NULL,'widget_StatsOverview','web','2026-02-22 00:06:58','2026-02-22 00:06:58',NULL);
INSERT INTO `permissions` VALUES ('369',NULL,'widget_PrincipleReleased','web','2026-02-22 00:06:58','2026-02-22 00:06:58',NULL);
INSERT INTO `permissions` VALUES ('370',NULL,'widget_TotalCollected','web','2026-02-22 00:06:58','2026-02-22 00:06:58',NULL);
INSERT INTO `permissions` VALUES ('371',NULL,'widget_Expenses','web','2026-02-22 00:06:58','2026-02-22 00:06:58',NULL);
INSERT INTO `permissions` VALUES ('372',NULL,'widget_OutstandingBalance','web','2026-02-22 00:06:59','2026-02-22 00:06:59',NULL);
INSERT INTO `permissions` VALUES ('395',NULL,'view_any_group','web','2026-02-27 16:12:57','2026-02-27 16:12:57',NULL);
INSERT INTO `permissions` VALUES ('396',NULL,'create_group','web','2026-02-27 16:12:57','2026-02-27 16:12:57',NULL);
INSERT INTO `permissions` VALUES ('397',NULL,'restore_group','web','2026-02-27 16:12:57','2026-02-27 16:12:57',NULL);
INSERT INTO `permissions` VALUES ('398',NULL,'restore_any_group','web','2026-02-27 16:12:57','2026-02-27 16:12:57',NULL);
INSERT INTO `permissions` VALUES ('399',NULL,'replicate_group','web','2026-02-27 16:12:57','2026-02-27 16:12:57',NULL);
INSERT INTO `permissions` VALUES ('400',NULL,'reorder_group','web','2026-02-27 16:12:57','2026-02-27 16:12:57',NULL);
INSERT INTO `permissions` VALUES ('401',NULL,'delete_group','web','2026-02-27 16:12:57','2026-02-27 16:12:57',NULL);
INSERT INTO `permissions` VALUES ('402',NULL,'delete_any_group','web','2026-02-27 16:12:57','2026-02-27 16:12:57',NULL);
INSERT INTO `permissions` VALUES ('403',NULL,'force_delete_group','web','2026-02-27 16:12:57','2026-02-27 16:12:57',NULL);
INSERT INTO `permissions` VALUES ('404',NULL,'force_delete_any_group','web','2026-02-27 16:12:57','2026-02-27 16:12:57',NULL);
INSERT INTO `permissions` VALUES ('405',NULL,'view_group','web','2026-02-27 16:21:41','2026-02-27 16:21:41',NULL);
INSERT INTO `permissions` VALUES ('406',NULL,'update_group','web','2026-02-27 16:21:41','2026-02-27 16:21:41',NULL);
INSERT INTO `permissions` VALUES ('407',NULL,'view_member','web','2026-02-27 16:21:41','2026-02-27 16:21:41',NULL);
INSERT INTO `permissions` VALUES ('408',NULL,'view_any_member','web','2026-02-27 16:21:41','2026-02-27 16:21:41',NULL);
INSERT INTO `permissions` VALUES ('409',NULL,'create_member','web','2026-02-27 16:21:41','2026-02-27 16:21:41',NULL);
INSERT INTO `permissions` VALUES ('410',NULL,'update_member','web','2026-02-27 16:21:41','2026-02-27 16:21:41',NULL);
INSERT INTO `permissions` VALUES ('411',NULL,'restore_member','web','2026-02-27 16:21:41','2026-02-27 16:21:41',NULL);
INSERT INTO `permissions` VALUES ('412',NULL,'restore_any_member','web','2026-02-27 16:21:41','2026-02-27 16:21:41',NULL);
INSERT INTO `permissions` VALUES ('413',NULL,'replicate_member','web','2026-02-27 16:21:41','2026-02-27 16:21:41',NULL);
INSERT INTO `permissions` VALUES ('414',NULL,'reorder_member','web','2026-02-27 16:21:41','2026-02-27 16:21:41',NULL);
INSERT INTO `permissions` VALUES ('415',NULL,'delete_member','web','2026-02-27 16:21:41','2026-02-27 16:21:41',NULL);
INSERT INTO `permissions` VALUES ('416',NULL,'delete_any_member','web','2026-02-27 16:21:42','2026-02-27 16:21:42',NULL);
INSERT INTO `permissions` VALUES ('417',NULL,'force_delete_member','web','2026-02-27 16:21:42','2026-02-27 16:21:42',NULL);
INSERT INTO `permissions` VALUES ('418',NULL,'force_delete_any_member','web','2026-02-27 16:21:42','2026-02-27 16:21:42',NULL);
INSERT INTO `permissions` VALUES ('419',NULL,'view_penalty','web','2026-02-27 16:21:42','2026-02-27 16:21:42',NULL);
INSERT INTO `permissions` VALUES ('420',NULL,'view_any_penalty','web','2026-02-27 16:21:42','2026-02-27 16:21:42',NULL);
INSERT INTO `permissions` VALUES ('421',NULL,'create_penalty','web','2026-02-27 16:21:42','2026-02-27 16:21:42',NULL);
INSERT INTO `permissions` VALUES ('422',NULL,'update_penalty','web','2026-02-27 16:21:42','2026-02-27 16:21:42',NULL);
INSERT INTO `permissions` VALUES ('423',NULL,'restore_penalty','web','2026-02-27 16:21:42','2026-02-27 16:21:42',NULL);
INSERT INTO `permissions` VALUES ('424',NULL,'restore_any_penalty','web','2026-02-27 16:21:42','2026-02-27 16:21:42',NULL);
INSERT INTO `permissions` VALUES ('425',NULL,'replicate_penalty','web','2026-02-27 16:21:42','2026-02-27 16:21:42',NULL);
INSERT INTO `permissions` VALUES ('426',NULL,'reorder_penalty','web','2026-02-27 16:21:42','2026-02-27 16:21:42',NULL);
INSERT INTO `permissions` VALUES ('427',NULL,'delete_penalty','web','2026-02-27 16:21:42','2026-02-27 16:21:42',NULL);
INSERT INTO `permissions` VALUES ('428',NULL,'delete_any_penalty','web','2026-02-27 16:21:42','2026-02-27 16:21:42',NULL);
INSERT INTO `permissions` VALUES ('429',NULL,'force_delete_penalty','web','2026-02-27 16:21:42','2026-02-27 16:21:42',NULL);
INSERT INTO `permissions` VALUES ('430',NULL,'force_delete_any_penalty','web','2026-02-27 16:21:42','2026-02-27 16:21:42',NULL);
INSERT INTO `permissions` VALUES ('431',NULL,'view_saving','web','2026-02-27 16:21:42','2026-02-27 16:21:42',NULL);
INSERT INTO `permissions` VALUES ('432',NULL,'view_any_saving','web','2026-02-27 16:21:42','2026-02-27 16:21:42',NULL);
INSERT INTO `permissions` VALUES ('433',NULL,'create_saving','web','2026-02-27 16:21:42','2026-02-27 16:21:42',NULL);
INSERT INTO `permissions` VALUES ('434',NULL,'update_saving','web','2026-02-27 16:21:42','2026-02-27 16:21:42',NULL);
INSERT INTO `permissions` VALUES ('435',NULL,'restore_saving','web','2026-02-27 16:21:42','2026-02-27 16:21:42',NULL);
INSERT INTO `permissions` VALUES ('436',NULL,'restore_any_saving','web','2026-02-27 16:21:42','2026-02-27 16:21:42',NULL);
INSERT INTO `permissions` VALUES ('437',NULL,'replicate_saving','web','2026-02-27 16:21:42','2026-02-27 16:21:42',NULL);
INSERT INTO `permissions` VALUES ('438',NULL,'reorder_saving','web','2026-02-27 16:21:42','2026-02-27 16:21:42',NULL);
INSERT INTO `permissions` VALUES ('439',NULL,'delete_saving','web','2026-02-27 16:21:42','2026-02-27 16:21:42',NULL);
INSERT INTO `permissions` VALUES ('440',NULL,'delete_any_saving','web','2026-02-27 16:21:42','2026-02-27 16:21:42',NULL);
INSERT INTO `permissions` VALUES ('441',NULL,'force_delete_saving','web','2026-02-27 16:21:42','2026-02-27 16:21:42',NULL);
INSERT INTO `permissions` VALUES ('442',NULL,'force_delete_any_saving','web','2026-02-27 16:21:42','2026-02-27 16:21:42',NULL);
INSERT INTO `permissions` VALUES ('443',NULL,'page_MemberProfile','web','2026-02-27 16:21:43','2026-02-27 16:21:43',NULL);
INSERT INTO `permissions` VALUES ('444',NULL,'page_PayLoan','web','2026-02-27 16:21:43','2026-02-27 16:21:43',NULL);
INSERT INTO `permissions` VALUES ('445',NULL,'page_GroupReport','web','2026-02-27 16:21:44','2026-02-27 16:21:44',NULL);
INSERT INTO `permissions` VALUES ('446',NULL,'page_LoanReport','web','2026-02-27 16:21:44','2026-02-27 16:21:44',NULL);
INSERT INTO `permissions` VALUES ('447',NULL,'page_MemberReport','web','2026-02-27 16:21:44','2026-02-27 16:21:44',NULL);
INSERT INTO `permissions` VALUES ('448',NULL,'page_OfficerPerformanceReport','web','2026-02-27 16:21:44','2026-02-27 16:21:44',NULL);
INSERT INTO `permissions` VALUES ('449',NULL,'view_product','web','2026-03-05 20:47:23','2026-03-05 20:47:23',NULL);
INSERT INTO `permissions` VALUES ('450',NULL,'view_any_product','web','2026-03-05 20:47:23','2026-03-05 20:47:23',NULL);
INSERT INTO `permissions` VALUES ('451',NULL,'create_product','web','2026-03-05 20:47:23','2026-03-05 20:47:23',NULL);
INSERT INTO `permissions` VALUES ('452',NULL,'update_product','web','2026-03-05 20:47:23','2026-03-05 20:47:23',NULL);
INSERT INTO `permissions` VALUES ('453',NULL,'restore_product','web','2026-03-05 20:47:23','2026-03-05 20:47:23',NULL);
INSERT INTO `permissions` VALUES ('454',NULL,'restore_any_product','web','2026-03-05 20:47:23','2026-03-05 20:47:23',NULL);
INSERT INTO `permissions` VALUES ('455',NULL,'replicate_product','web','2026-03-05 20:47:23','2026-03-05 20:47:23',NULL);
INSERT INTO `permissions` VALUES ('456',NULL,'reorder_product','web','2026-03-05 20:47:23','2026-03-05 20:47:23',NULL);
INSERT INTO `permissions` VALUES ('457',NULL,'delete_product','web','2026-03-05 20:47:23','2026-03-05 20:47:23',NULL);
INSERT INTO `permissions` VALUES ('458',NULL,'delete_any_product','web','2026-03-05 20:47:23','2026-03-05 20:47:23',NULL);
INSERT INTO `permissions` VALUES ('459',NULL,'force_delete_product','web','2026-03-05 20:47:23','2026-03-05 20:47:23',NULL);
INSERT INTO `permissions` VALUES ('460',NULL,'force_delete_any_product','web','2026-03-05 20:47:23','2026-03-05 20:47:23',NULL);
INSERT INTO `permissions` VALUES ('461',NULL,'page_DefaultersReportPage','web','2026-03-05 20:47:24','2026-03-05 20:47:24',NULL);
INSERT INTO `permissions` VALUES ('462',NULL,'page_GroupPayment','web','2026-03-05 20:47:24','2026-03-05 20:47:24',NULL);
INSERT INTO `permissions` VALUES ('463',NULL,'page_LoanReportPage','web','2026-03-05 20:47:24','2026-03-05 20:47:24',NULL);
INSERT INTO `permissions` VALUES ('464',NULL,'page_MemberReportPage','web','2026-03-05 20:47:25','2026-03-05 20:47:25',NULL);
INSERT INTO `permissions` VALUES ('465',NULL,'page_RepaymentsReportPage','web','2026-03-05 20:47:25','2026-03-05 20:47:25',NULL);
INSERT INTO `permissions` VALUES ('466',NULL,'page_SavingsReportPage','web','2026-03-05 20:47:25','2026-03-05 20:47:25',NULL);
INSERT INTO `permissions` VALUES ('467',NULL,'widget_OfficerPaymentsChart','web','2026-03-05 20:47:27','2026-03-05 20:47:27',NULL);

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint(20) unsigned DEFAULT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `products` VALUES ('1','Mabati','Gauge 30','1000.00','active','2026-03-06 09:57:26','2026-03-06 09:57:26');

DROP TABLE IF EXISTS `repayments`;
CREATE TABLE `repayments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint(20) unsigned DEFAULT NULL,
  `loan_id` bigint(20) unsigned NOT NULL,
  `balance` decimal(64,0) NOT NULL,
  `payments` decimal(64,0) NOT NULL,
  `principal` decimal(64,0) NOT NULL,
  `payments_method` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `reference_number` varchar(255) NOT NULL,
  `loan_number` varchar(255) DEFAULT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `repayments_loan_id_foreign` (`loan_id`),
  CONSTRAINT `repayments_loan_id_foreign` FOREIGN KEY (`loan_id`) REFERENCES `loans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `repayments` VALUES ('1','1550434','2','9716','1000','10000','cash','2026-02-27 07:47:35','2026-02-27 07:47:35','No reference was entered by Raymond - raywalcott14@gmail.com','RB-69A1128537356',NULL);
INSERT INTO `repayments` VALUES ('2','1550434','2','8716','1000','10000','group_payment','2026-03-05 07:33:08','2026-03-05 07:33:08','GPS-1-20260305073308','RB-69A1128537356',NULL);

DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  `organization_id` bigint(20) unsigned DEFAULT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `role_has_permissions` VALUES ('1','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('2','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('3','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('4','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('5','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('6','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('7','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('8','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('9','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('10','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('11','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('12','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('13','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('14','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('15','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('16','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('17','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('18','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('19','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('20','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('21','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('22','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('23','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('24','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('25','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('26','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('27','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('28','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('29','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('30','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('31','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('32','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('33','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('34','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('35','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('36','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('37','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('38','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('39','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('40','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('41','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('42','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('43','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('44','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('45','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('46','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('47','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('48','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('49','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('50','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('51','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('52','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('53','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('54','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('55','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('56','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('57','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('58','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('59','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('60','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('61','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('62','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('63','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('64','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('65','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('66','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('67','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('68','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('69','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('70','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('71','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('72','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('73','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('74','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('75','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('76','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('77','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('78','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('79','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('80','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('81','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('82','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('83','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('84','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('85','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('86','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('87','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('88','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('89','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('90','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('91','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('92','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('93','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('94','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('95','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('96','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('97','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('98','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('99','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('100','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('101','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('102','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('103','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('104','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('105','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('106','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('107','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('108','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('109','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('110','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('111','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('112','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('113','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('114','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('115','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('116','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('117','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('118','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('119','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('120','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('121','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('122','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('123','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('124','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('125','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('126','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('127','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('128','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('129','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('130','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('131','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('132','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('133','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('134','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('135','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('136','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('137','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('138','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('139','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('139','3',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('140','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('140','3',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('141','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('141','3',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('142','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('142','3',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('143','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('144','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('145','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('146','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('147','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('148','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('149','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('150','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('151','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('152','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('153','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('154','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('155','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('156','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('157','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('158','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('159','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('160','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('161','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('162','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('163','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('164','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('165','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('166','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('167','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('168','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('169','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('170','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('171','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('172','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('173','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('174','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('175','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('176','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('177','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('178','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('179','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('180','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('181','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('182','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('183','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('184','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('185','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('186','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('187','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('188','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('189','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('190','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('191','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('192','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('193','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('194','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('195','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('196','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('197','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('198','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('199','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('200','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('201','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('202','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('203','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('204','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('205','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('206','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('207','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('208','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('209','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('210','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('211','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('212','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('213','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('214','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('215','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('216','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('217','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('218','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('219','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('220','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('221','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('222','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('223','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('224','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('225','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('226','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('227','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('228','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('229','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('230','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('231','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('232','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('233','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('234','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('235','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('236','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('237','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('238','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('239','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('240','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('241','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('242','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('243','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('244','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('245','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('246','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('247','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('247','3',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('248','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('248','3',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('249','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('249','3',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('250','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('250','3',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('251','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('252','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('253','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('254','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('255','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('256','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('257','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('258','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('259','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('260','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('261','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('262','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('263','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('264','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('265','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('266','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('267','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('268','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('269','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('270','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('271','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('272','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('273','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('274','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('275','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('276','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('277','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('278','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('279','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('280','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('281','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('282','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('283','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('284','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('285','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('286','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('287','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('288','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('289','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('290','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('291','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('292','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('293','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('294','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('295','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('296','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('297','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('298','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('299','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('300','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('301','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('302','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('303','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('304','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('305','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('306','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('307','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('308','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('309','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('310','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('311','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('312','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('313','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('314','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('315','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('316','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('317','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('318','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('319','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('319','3',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('320','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('320','3',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('321','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('321','3',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('322','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('322','3',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('323','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('324','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('325','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('326','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('327','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('328','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('329','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('330','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('331','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('332','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('333','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('334','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('335','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('336','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('337','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('338','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('339','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('340','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('341','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('342','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('343','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('344','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('345','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('346','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('347','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('348','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('349','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('350','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('351','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('352','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('353','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('354','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('355','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('356','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('357','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('358','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('359','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('360','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('361','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('362','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('363','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('364','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('365','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('366','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('367','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('367','3',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('368','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('368','3',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('369','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('369','3',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('370','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('370','3',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('371','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('372','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('372','3',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('395','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('395','3',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('396','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('397','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('398','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('399','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('400','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('401','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('402','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('403','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('404','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('405','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('405','3',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('406','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('406','3',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('407','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('407','3',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('408','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('408','3',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('409','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('409','3',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('410','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('410','3',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('411','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('412','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('413','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('414','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('415','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('416','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('417','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('418','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('419','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('420','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('421','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('422','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('423','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('424','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('425','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('426','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('427','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('428','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('429','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('430','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('431','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('431','3',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('432','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('432','3',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('433','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('433','3',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('434','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('434','3',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('435','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('436','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('437','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('438','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('439','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('440','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('441','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('442','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('443','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('443','3',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('444','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('444','3',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('445','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('446','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('447','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('448','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('449','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('449','3',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('450','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('451','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('452','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('453','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('454','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('455','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('456','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('457','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('458','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('459','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('460','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('461','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('462','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('463','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('464','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('465','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('466','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('467','1',NULL,NULL);
INSERT INTO `role_has_permissions` VALUES ('467','3',NULL,NULL);

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `roles` VALUES ('1',NULL,'super_admin','web','2026-02-22 00:06:14','2026-02-22 00:06:14',NULL);
INSERT INTO `roles` VALUES ('2',NULL,'panel_user','web','2026-02-22 00:12:37','2026-02-22 00:12:37',NULL);
INSERT INTO `roles` VALUES ('3',NULL,'Officer','web','2026-02-26 06:56:14','2026-02-26 06:56:14',NULL);

DROP TABLE IF EXISTS `salary_scales`;
CREATE TABLE `salary_scales` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint(20) unsigned DEFAULT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  `scale_name` varchar(255) NOT NULL,
  `scale_code` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `basic_salary` decimal(10,2) NOT NULL,
  `housing_allowance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `transport_allowance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `medical_allowance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `other_allowances` decimal(10,2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `savings`;
CREATE TABLE `savings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `member_id` bigint(20) unsigned NOT NULL,
  `group_id` bigint(20) unsigned NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `contribution_date` date NOT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `savings_member_id_foreign` (`member_id`),
  KEY `savings_group_id_foreign` (`group_id`),
  CONSTRAINT `savings_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `savings_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `savings` VALUES ('1','2','1','1000.00','2026-02-27',NULL,'2026-02-27 06:27:31','2026-02-27 06:27:31');
INSERT INTO `savings` VALUES ('3','2','1','500.00','2026-02-27',NULL,'2026-02-27 08:25:12','2026-02-27 08:25:12');
INSERT INTO `savings` VALUES ('4','3','1','150.00','2026-02-27',NULL,'2026-02-27 08:31:15','2026-02-27 08:31:15');
INSERT INTO `savings` VALUES ('5','2','1','100.00','2026-02-28',NULL,'2026-02-28 08:28:08','2026-02-28 08:28:08');
INSERT INTO `savings` VALUES ('6','2','1','500.00','2026-03-05','Group payment session: GPS-1-20260305073308','2026-03-05 07:33:08','2026-03-05 07:33:08');
INSERT INTO `savings` VALUES ('7','3','1','500.00','2026-03-05','Group payment session: GPS-1-20260305073308','2026-03-05 07:33:08','2026-03-05 07:33:08');

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `organization_id` bigint(20) unsigned DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `sessions` VALUES ('pDYIKeRmKDRLmCxLlduadfEttTakMgyjmstdSOTB',NULL,'1','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36','YTo3OntzOjY6Il90b2tlbiI7czo0MDoib0FQazFKY3VaZjlsY2d1WVlUb0NJalRDV25oQmI1dE9aQ013cktoMSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQyOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vbWVtYmVyLXByb2ZpbGUiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjYwOiIkMnkkMTIkMUZuUGswa2RBdTBDVWt2YURiS2hnLkJQbE81NUxhNUJRS3JyODZiZHFMZWhrWDlmeTlkWFMiO3M6ODoiZmlsYW1lbnQiO2E6MDp7fX0=','1772785135',NULL);

DROP TABLE IF EXISTS `supervisor_groups`;
CREATE TABLE `supervisor_groups` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `group_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `supervisor_groups_user_id_foreign` (`user_id`),
  KEY `supervisor_groups_group_id_foreign` (`group_id`),
  CONSTRAINT `supervisor_groups_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `supervisor_groups_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `tax_bands`;
CREATE TABLE `tax_bands` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint(20) unsigned DEFAULT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `min_income` decimal(10,2) NOT NULL,
  `max_income` decimal(10,2) DEFAULT NULL,
  `tax_rate` decimal(5,2) NOT NULL,
  `fixed_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `third_parties`;
CREATE TABLE `third_parties` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `base_uri` varchar(255) DEFAULT NULL,
  `endpoint` varchar(255) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `sender_id` varchar(255) DEFAULT NULL,
  `is_active` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `transactions`;
CREATE TABLE `transactions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint(20) unsigned DEFAULT NULL,
  `payable_type` varchar(255) NOT NULL,
  `payable_id` bigint(20) unsigned NOT NULL,
  `wallet_id` bigint(20) unsigned NOT NULL,
  `type` enum('deposit','withdraw') NOT NULL,
  `amount` decimal(64,0) NOT NULL,
  `confirmed` tinyint(1) NOT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `uuid` char(36) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transactions_uuid_unique` (`uuid`),
  KEY `transactions_payable_type_payable_id_index` (`payable_type`,`payable_id`),
  KEY `payable_type_payable_id_ind` (`payable_type`,`payable_id`),
  KEY `payable_type_ind` (`payable_type`,`payable_id`,`type`),
  KEY `payable_confirmed_ind` (`payable_type`,`payable_id`,`confirmed`),
  KEY `payable_type_confirmed_ind` (`payable_type`,`payable_id`,`type`,`confirmed`),
  KEY `transactions_type_index` (`type`),
  KEY `transactions_wallet_id_foreign` (`wallet_id`),
  CONSTRAINT `transactions_wallet_id_foreign` FOREIGN KEY (`wallet_id`) REFERENCES `wallets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `transfers`;
CREATE TABLE `transfers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint(20) unsigned DEFAULT NULL,
  `from_type` varchar(255) NOT NULL,
  `from_id` bigint(20) unsigned NOT NULL,
  `to_type` varchar(255) NOT NULL,
  `to_id` bigint(20) unsigned NOT NULL,
  `status` enum('exchange','transfer','paid','refund','gift') NOT NULL DEFAULT 'transfer',
  `status_last` enum('exchange','transfer','paid','refund','gift') DEFAULT NULL,
  `deposit_id` bigint(20) unsigned NOT NULL,
  `withdraw_id` bigint(20) unsigned NOT NULL,
  `discount` decimal(64,0) NOT NULL DEFAULT 0,
  `fee` decimal(64,0) NOT NULL DEFAULT 0,
  `uuid` char(36) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transfers_uuid_unique` (`uuid`),
  KEY `transfers_from_type_from_id_index` (`from_type`,`from_id`),
  KEY `transfers_to_type_to_id_index` (`to_type`,`to_id`),
  KEY `transfers_deposit_id_foreign` (`deposit_id`),
  KEY `transfers_withdraw_id_foreign` (`withdraw_id`),
  CONSTRAINT `transfers_deposit_id_foreign` FOREIGN KEY (`deposit_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transfers_withdraw_id_foreign` FOREIGN KEY (`withdraw_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `company_representative` varchar(255) DEFAULT NULL,
  `company_representative_phone` varchar(255) DEFAULT NULL,
  `company_representative_email` varchar(255) DEFAULT NULL,
  `company_phone` varchar(255) DEFAULT NULL,
  `company_address` text DEFAULT NULL,
  `profile_completion_modal_shown` tinyint(1) NOT NULL DEFAULT 0,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `two_factor_secret` text DEFAULT NULL,
  `two_factor_recovery_codes` text DEFAULT NULL,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `current_team_id` bigint(20) unsigned DEFAULT NULL,
  `profile_photo_path` varchar(2048) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` VALUES ('1','1550434','Raymond',NULL,NULL,NULL,NULL,NULL,'0','raywalcott14@gmail.com',NULL,'$2y$12$1FnPk0kdAu0CUkvaDbKhg.BPlO55La5BQKrr86bdqLehkX9fy9dXS',NULL,NULL,NULL,'PXGtUIux49aNziqiEtoLFYmPNFmiryTjYT6odYhAp6kmTTEsiQhSYfu3vyFk',NULL,NULL,'2026-02-22 00:09:02','2026-03-05 22:56:46',NULL);
INSERT INTO `users` VALUES ('2','1550434','mary',NULL,NULL,NULL,NULL,NULL,'0','mary@gmail.com',NULL,'$2y$12$AZjB7qfi3m8uePQnVdkaaODj8OnCmjxAC0LXC5rttdSt2pU4FQkbu',NULL,NULL,NULL,NULL,NULL,NULL,'2026-02-26 06:58:02','2026-03-05 23:01:05',NULL);
INSERT INTO `users` VALUES ('3','1550434','Felix Otieno',NULL,NULL,NULL,NULL,NULL,'0','felixotieno@gmail.com',NULL,'$2y$12$8Pk01KhdcfuCcag4UiLTH.bu/aWSE2iifj21oi6rtNB1cfCfqjmya',NULL,NULL,NULL,NULL,NULL,NULL,'2026-02-27 17:29:56','2026-02-27 19:58:59',NULL);

DROP TABLE IF EXISTS `wallets`;
CREATE TABLE `wallets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint(20) unsigned DEFAULT NULL,
  `holder_type` varchar(255) NOT NULL,
  `holder_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `uuid` char(36) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `balance` decimal(64,0) NOT NULL DEFAULT 0,
  `decimal_places` smallint(5) unsigned NOT NULL DEFAULT 2,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `amount` decimal(64,0) NOT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wallets_holder_type_holder_id_slug_unique` (`holder_type`,`holder_id`,`slug`),
  UNIQUE KEY `wallets_uuid_unique` (`uuid`),
  KEY `wallets_holder_type_holder_id_index` (`holder_type`,`holder_id`),
  KEY `wallets_slug_index` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


SET FOREIGN_KEY_CHECKS=1;
