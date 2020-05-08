<?php
$functions = array(
        'local_test_courses' => array(
                'classname'   => 'local_test_external',
                'methodname'  => 'courses',
                'classpath'   => 'local/test/externallib.php',
                'description' => 'Return all courses',
                'type'        => 'read',
        ),
		'local_test_users' => array(
                'classname'   => 'local_test_external',
                'methodname'  => 'users',
                'classpath'   => 'local/test/externallib.php',
                'description' => 'Return all users',
                'type'        => 'read'
        ),
		'local_test_user_courses' => array(
                'classname'   => 'local_test_external',
                'methodname'  => 'user_courses',
                'classpath'   => 'local/test/externallib.php',
                'description' => 'Return all user courses and grader',
                'type'        => 'read'
        )
);

$services = array(
        'My test service' => array(
					'functions' => array ('local_test_courses', 'local_test_users', 'local_test_user_courses'),
					'restrictedusers' => 0,
					'enabled'=>1,
        )
);
