<?php
require_once($CFG->libdir . "/externallib.php");

class local_test_external extends external_api {

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function courses_parameters() {
        return new external_function_parameters(                                                                                    
            array(                                                                                                                  
                'visible' => new external_value(PARAM_INT, 'visible',  VALUE_DEFAULT, 1),
                'limit' => new external_value(PARAM_INT, 'limit',  VALUE_DEFAULT, 20),
            )
        );
    }

    /**
     * Returns courses
     * @return array courses
     */
    public static function courses($visible, $limit){
		global $DB, $USER;
		$params = self::validate_parameters(self::courses_parameters(), ['visible'=>$visible, 'limit'=>$limit]);
        $context = get_context_instance(CONTEXT_USER, $USER->id);
        self::validate_context($context);
        if (!has_capability('moodle/course:view', $context)) {
            throw new moodle_exception('nopermissions');
        }
		return $tes = $DB->get_records('course',['visible'=>$params['visible']], '', '*', $limitfrom=0, $limitnum=$params['limit']);
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
            array(                                                                                                                  
                'suspended' => new external_value(PARAM_INT, 'suspended',  VALUE_DEFAULT, 0),
                'limit' => new external_value(PARAM_INT, 'limit',  VALUE_DEFAULT, 20),
            )
        );
    }

    /**
     * Returns users
     * @return array users
     */
    public static function users($suspended, $limit) {
		global $DB, $USER;

		$params = self::validate_parameters(self::users_parameters(), ['suspended'=>$suspended, 'limit'=>$limit]);
		
        $context = get_context_instance(CONTEXT_USER, $USER->id);
        self::validate_context($context);

        if (!has_capability('moodle/user:viewdetails', $context)) {
            throw new moodle_exception('nopermissions');
        }
		
		return $tes = $DB->get_records('user',['suspended'=>$params['suspended']], '', '*', $limitfrom=0, $limitnum=$params['limit']);
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
            array(                                                                                                                  
                'suspended' => new external_value(PARAM_INT, 'suspended',  VALUE_DEFAULT, 0),
                'limit' => new external_value(PARAM_INT, 'limit',  VALUE_DEFAULT, 20),
            )
        );
    }

    /**
     * Returns welcome message
     * @return array user_courses
     */
    public static function user_courses($suspended, $limit) {
		global $DB, $USER;
		
		$params = self::validate_parameters(self::users_parameters(), ['suspended'=>$suspended, 'limit'=>$limit]);
		
        $context = get_context_instance(CONTEXT_USER, $USER->id);
        self::validate_context($context);

        if (!has_capability('moodle/user:viewdetails', $context)) {
            throw new moodle_exception('nopermissions');
        }
			 
		$sql = "SELECT DISTINCT u.id AS user_id, c.id AS course_id, c.fullname AS course_name, CONCAT(u.lastname, ' ', u.firstname) AS user_name, gg.finalgrade AS grade, gg.userid AS ag_user_id, gi.itemname AS item_name, gi.id AS item_id
				  FROM 	{user} u
			 LEFT JOIN 	{user_enrolments} ue ON(u.id = ue.userid)
			 LEFT JOIN 	{enrol} e ON(ue.enrolid = e.id)
			 LEFT JOIN 	{course} c ON(e.courseid = c.id)
			 LEFT JOIN 	{grade_items} gi ON(c.id = gi.courseid)
			 LEFT JOIN 	{grade_grades} gg ON(gi.id = gg.itemid AND gg.userid = u.id)
				 WHERE  c.id > 0 
						AND gi.itemname IS NOT NULL 
						AND u.id IN(SELECT * FROM (SELECT u1.id FROM {user} u1 WHERE u1.suspended = :suspended LIMIT ".(int)$params['limit'].") u2 ) 
			 ";//LIMIT :limit видає помилку

		$result = $DB->get_recordset_sql($sql, ['suspended'=>$params['suspended']]);
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
