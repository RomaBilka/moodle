<?php
require_once($CFG->libdir . "/externallib.php");

class local_test_external extends external_api {

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function courses_parameters() {
        return new external_function_parameters(
                array()
        );
    }

    /**
     * Returns courses
     * @return array courses
     */
    public static function courses(){
        return get_courses();
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function courses_returns() {
		return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT, 'id'),
                    'fullname' => new external_value(PARAM_TEXT, 'course name'),
                )
            )
        );
    }


	 /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function users_parameters() {
        return new external_function_parameters(
                array()
        );
    }

    /**
     * Returns users
     * @return array users
     */
    public static function users() {
		global $DB;
		return $DB->get_records('user');
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function users_returns() {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT, 'id'),
                    'username' => new external_value(PARAM_TEXT, 'user name'),
                    'firstname' => new external_value(PARAM_TEXT, 'user firstname'),
                    'lastname' => new external_value(PARAM_TEXT, 'user lastname'),
                    'email' => new external_value(PARAM_TEXT, 'user email'),
                )
            )
        );
    }
	
	
	
	/**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function user_courses_parameters() {
        return new external_function_parameters(
                array()
        );
    }

    /**
     * Returns welcome message
     * @return array user_courses
     */
    public static function user_courses() {
		global $DB;
		$sql = "SELECT 	u.id AS user_id, c.id AS course_id, c.fullname AS course_name, CONCAT(u.lastname, ' ', u.firstname) AS user_name, ag.grade AS grade, ag.userid AS ag_user_id, a.name AS item_name, a.id AS item_id
				  FROM 	`user` AS u
			 LEFT JOIN 	`user_enrolments` AS ue ON(u.id = ue.userid)
			 LEFT JOIN 	`enrol` AS e ON(ue.enrolid = e.id)
			 LEFT JOIN 	`course` AS c ON(e.courseid = c.id)
			 LEFT JOIN 	`assign` AS a ON(c.id = a.course)
			 LEFT JOIN 	`assign_grades` AS ag ON(a.id = ag.assignment AND u.id = ag.userid)
			 WHERE c.id > 0
			 ";
		$result = $DB->get_recordset_sql($sql);
		$users = [];
		foreach($result as $row){
			if(empty($users[$row->user_id])){
				$users[$row->user_id] = [
					'user_name'=>$row->user_name,
					'user_id'=>(int)$row->user_id,
					'course' => []
				];
			}
			if(empty($users[$row->user_id]['course'][$row->course_id])){
					$users[$row->user_id]['course'][$row->course_id] = [
																		'course_name'=>$row->course_name,
																		'course_id'=>(int)$row->course_id,
																		'item' => []
																	];
			}
				
			if(isset($row->grade)){
				$users[$row->user_id]['course'][$row->course_id]['item'][$row->item_id]=['item_id'=>(int)$row->item_id, 'item_name'=>$row->item_name,'grade' => (int)$row->grade];
			}

		}
		$result->close();
        return $users;
    }
	
    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function user_courses_returns() {
        return new external_multiple_structure(
				new external_single_structure(
					array(
						'user_id' => new external_value(PARAM_INT, 'user id'),
						'user_name' => new external_value(PARAM_TEXT, 'user name'),
						'course'	=>	new external_multiple_structure(
											new external_single_structure(
												array(
													'course_id' => new external_value(PARAM_INT, 'course id'),
													'course_name' => new external_value(PARAM_TEXT, 'course name'),
													'item'	=>	new external_multiple_structure(
														new external_single_structure(
															array(
																'item_id' => new external_value(PARAM_INT, 'item id'),
																'item_name' => new external_value(PARAM_TEXT, 'item name'),
																'grade' => new external_value(PARAM_INT, 'grade name')
															)
														)
													)
												)
											)
										)
					)
				)                 
            
			);
    }

}
