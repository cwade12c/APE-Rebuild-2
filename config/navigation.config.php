<?php
//Manage Navigation Links

//<editor-fold desc="Student Links">
DEFINE("GUEST_LINKS", array(
    array(
        'url' => 'home.php',
        'title' => 'Upcoming Apes',
        'text' => 'Home'
    ),

    array(
        'url' => 'login.php',
        'title' => 'Login',
        'text' => 'Login'
    )
));
//</editor-fold>

//<editor-fold desc="Student Links">
DEFINE("STUDENT_LINKS", array(
    array(
        'url' => 'home.php',
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
        'url' => 'logout.php',
        'title' => 'Logout of Your Account',
        'text' => 'Logout'
    )
));
//</editor-fold>

//<editor-fold desc="Grader Links">
DEFINE("GRADER_LINKS", array(
    array(
        'url' => 'home.php',
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
        'url' => 'logout.php',
        'title' => 'Logout of Your Account',
        'text' => 'Logout'
    )
));
//</editor-fold>

//<editor-fold desc="Teacher Links">
DEFINE("TEACHER_LINKS", array(
    array(
        'url' => 'home.php',
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
        'url' => 'logout.php',
        'title' => 'Logout',
        'text' => 'Logout'
    )
));
//</editor-fold>

//<editor-fold desc="Admin Links">
DEFINE("ADMIN_LINKS", array(
    array(
        'url' => 'home.php',
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
        'url' => 'manageLocations.php',
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
        'url' => 'logout.php',
        'title' => 'Logout',
        'text' => 'Logout'
    )
));
//</editor-fold>