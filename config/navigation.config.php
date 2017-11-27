<?php
//Manage Action Navigation CSS classes
DEFINE("ACTION_CREATE", "btn btn-success");
DEFINE("ACTION_UPDATE", "btn btn-primary");
DEFINE("ACTION_DELETE", "btn btn-danger");
DEFINE("ACTION_ARCHIVE", "btn btn-warning");
DEFINE("ACTION_GENERIC", "btn btn-secondary");

//Manage Navigation Links

//<editor-fold desc="Guest Links">
DEFINE("GUEST_LINKS", array(
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
    )
));
//</editor-fold>

//<editor-fold desc="Grader Links">
DEFINE("GRADER_LINKS", array(
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

));
//</editor-fold>

//<editor-fold desc="Teacher Links">
DEFINE("TEACHER_LINKS", array(
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
                'url' => 'archivedExams.php',
                'title' => 'View Archived Apes',
                'text' => 'Archived'
            ),

            array(
                'url' => 'examSearch.php',
                'title' => 'Exam Search',
                'text' => 'Search Exams'
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
    )
));
//</editor-fold>

//<editor-fold desc="Admin Links">
DEFINE("ADMIN_LINKS", array(
    array(
        'url' => 'exams',
        'title' => 'Manage Exams',
        'text' => 'Exams'
    ),

    array(
        'url' => 'accounts',
        'title' => 'Manage Users',
        'text' => 'Accounts'
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
    )
));
//</editor-fold>