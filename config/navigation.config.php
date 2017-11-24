<?php
//Manage Action Navigation CSS classes
DEFINE("ACTION_CREATE", "btn btn-success");
DEFINE("ACTION_UPDATE", "btn btn-primary");
DEFINE("ACTION_DELETE", "btn btn-danger");
DEFINE("ACTION_ARCHIVE", "btn btn-warning");
DEFINE("ACTION_GENERIC", "btn btn-secondary");

//Manage Navigation Links

//<editor-fold desc="Student Links">
DEFINE("GUEST_LINKS", array(
    array(
        'url' => 'home',
        'title' => 'Upcoming Apes',
        'text' => 'Home'
    ),

    array(
        'url' => 'login',
        'title' => 'Login',
        'text' => 'Login'
    )
));
//</editor-fold>

//<editor-fold desc="Student Links">
DEFINE("STUDENT_LINKS", array(
    array(
        'url' => 'home',
        'title' => 'Student Home',
        'text' => 'Home'
    ),

    array(
        'url' => '',
        'title' => '',
        'text' => 'Apes',
        'children' => true,
        'childLinks' => array(
            array(
                'url' => 'viewUpcomingApes.php',
                'title' => 'View Upcoming Apes',
                'text' => 'Upcoming'
            )
        )
    ),

    array(
        'url' => 'grades.php',
        'title' => 'View Your APE Grades',
        'text' => 'Grades'
    ),

    array(
        'url' => 'logout',
        'title' => 'Logout of Your Account',
        'text' => 'Logout'
    )
));
//</editor-fold>

//<editor-fold desc="Grader Links">
DEFINE("GRADER_LINKS", array(
    array(
        'url' => 'graderHome',
        'title' => 'Grader Home',
        'text' => 'Home'
    ),

    array(
        'url' => '',
        'title' => '',
        'text' => 'Apes',
        'children' => true,
        'childLinks' => array(
            array(
                'url' => 'viewUpcomingApes.php',
                'title' => 'View Upcoming Apes',
                'text' => 'Upcoming'
            )
        )
    ),

    array(
        'url' => 'grading',
        'title' => 'Assigned Exam Categories',
        'text' => 'Grading'
    ),

    array(
        'url' => 'logout',
        'title' => 'Logout of Your Account',
        'text' => 'Logout'
    )
));
//</editor-fold>

//<editor-fold desc="Teacher Links">
DEFINE("TEACHER_LINKS", array(
    array(
        'url' => 'home',
        'title' => 'Teacher Home',
        'text' => 'Home'
    ),

    array(
        'url' => '',
        'title' => '',
        'text' => 'Apes',
        'children' => true,
        'childLinks' => array(
            array(
                'url' => 'viewUpcomingApes.php',
                'title' => 'View Upcoming Apes',
                'text' => 'Upcoming'
            ),

            array(
                'url' => 'teacherExams.php',
                'title' => 'Teachers Exams',
                'text' => 'My exams'
            ),

            array(
                'url' => 'viewArchivesExams.php',
                'title' => 'View Archived Apes',
                'text' => 'Archived'
            )
        )
    ),

    array(
        'url' => '',
        'title' => '',
        'text' => 'Tools',
        'children' => true,
        'childLinks' => array(
            array(
                'url' => 'reports.php',
                'title' => 'Generate Reports',
                'text' => 'Reports'
            ),

            array(
                'url' => 'registerClass.php',
                'title' => 'Register multiple students for an APE',
                'text' => 'Register Class'
            )
        )
    ),

    array(
        'url' => 'logout',
        'title' => 'Logout',
        'text' => 'Logout'
    )
));
//</editor-fold>

//<editor-fold desc="Admin Links">
DEFINE("ADMIN_LINKS", array(
    array(
        'url' => 'home',
        'title' => 'Admin Home',
        'text' => 'Home'
    ),

    array(
        'url' => '',
        'title' => '',
        'text' => 'Apes',
        'children' => true,
        'childLinks' => array(
            array(
                'url' => 'manageApes.php',
                'title' => 'Manage Apes',
                'text' => 'Manage'
            ),

            array(
                'url' => 'viewUpcomingApes.php',
                'title' => 'View Upcoming Apes',
                'text' => 'Upcoming'
            ),

            array(
                'url' => 'viewArchivesExams.php',
                'title' => 'View Archived Apes',
                'text' => 'Archived'
            )
        )
    ),

    array(
        'url' => '',
        'title' => '',
        'text' => 'Grades',
        'children' => true,
        'childLinks' => array(
            array(
                'url' => 'gradeExams.php',
                'title' => 'Grade Exams',
                'text' => 'Grade Exams'
            ),

            array(
                'url' => 'gradeSubmissions.php',
                'title' => 'View Your Submissions',
                'text' => 'Submitted Grades'
            )
        )
    ),

    array(
        'url' => 'manageUsers.php',
        'title' => 'Manage Users',
        'text' => 'Users'
    ),

    array(
        'url' => 'locations',
        'title' => 'Manage Locations',
        'text' => 'Locations'
    ),

    array(
        'url' => '',
        'title' => '',
        'text' => 'Tools',
        'children' => true,
        'childLinks' => array(
            array(
                'url' => 'reports.php',
                'title' => 'Generate Reports',
                'text' => 'Reports'
            ),

            array(
                'url' => 'registerClass.php',
                'title' => 'Register multiple students for an APE',
                'text' => 'Register Class'
            )
        )
    ),

    array(
        'url' => 'logout',
        'title' => 'Logout',
        'text' => 'Logout'
    )
));
//</editor-fold>