#
# Table structure for table 'sys_dmail_category'
#
CREATE TABLE sys_dmail_category (
    user_feuserextension_description text NOT NULL,
    user_feuserextension_parent int(11) DEFAULT '0' NOT NULL,
    user_feuserextension_order int(11) DEFAULT '0' NOT NULL
);