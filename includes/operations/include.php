<?php
require_once INCLUDES_PATH . 'operations/Operation.class.php';

require_once INCLUDES_PATH . 'operations/AccountIDValid.class.php';
require_once INCLUDES_PATH . 'operations/AccountIDExists.class.php';
require_once INCLUDES_PATH . 'operations/AccountGeneralInfo.class.php';
require_once INCLUDES_PATH . 'operations/AccountDetails.class.php';
require_once INCLUDES_PATH . 'operations/AssignGrader.class.php';
require_once INCLUDES_PATH . 'operations/AvailableGraders.class.php';
require_once INCLUDES_PATH . 'operations/CanEditExam.class.php';
require_once INCLUDES_PATH . 'operations/CanFinalizeExam.class.php';
require_once INCLUDES_PATH . 'operations/Categories.class.php';
require_once INCLUDES_PATH . 'operations/Category.class.php';
require_once INCLUDES_PATH . 'operations/CreateAccount.class.php';
require_once INCLUDES_PATH . 'operations/TempToFull.class.php';
require_once INCLUDES_PATH . 'operations/ResolveBlock.class.php';
require_once INCLUDES_PATH . 'operations/CreateAccounts.class.php';
require_once INCLUDES_PATH . 'operations/CreateCategory.class.php';
require_once INCLUDES_PATH . 'operations/CreateExam.class.php';
require_once INCLUDES_PATH . 'operations/CreateLocation.class.php';
require_once INCLUDES_PATH . 'operations/CreateReport.class.php';
require_once INCLUDES_PATH . 'operations/CreateRoom.class.php';
require_once INCLUDES_PATH . 'operations/DefaultCategories.class.php';
require_once INCLUDES_PATH . 'operations/DeleteCategory.class.php';
require_once INCLUDES_PATH . 'operations/DeleteLocation.class.php';
require_once INCLUDES_PATH . 'operations/DeleteRoom.class.php';
require_once INCLUDES_PATH . 'operations/ExamDetails.class.php';
require_once INCLUDES_PATH . 'operations/ExamDetailsFull.class.php';
require_once INCLUDES_PATH . 'operations/ExamFinalizationProgress.class.php';
require_once INCLUDES_PATH . 'operations/ExamGraders.class.php';
require_once INCLUDES_PATH . 'operations/ExamGradeInfo.class.php';
require_once INCLUDES_PATH . 'operations/ExamRegistrations.class.php';
require_once INCLUDES_PATH . 'operations/ExamCategories.class.php';
require_once INCLUDES_PATH . 'operations/ExamLocation.class.php';
require_once INCLUDES_PATH . 'operations/GenerateReport.class.php';
require_once INCLUDES_PATH . 'operations/GraderAssignedExamDetails.class.php';
require_once INCLUDES_PATH . 'operations/GraderAssignedExams.class.php';
require_once INCLUDES_PATH . 'operations/GraderProgress.class.php';
require_once INCLUDES_PATH . 'operations/Locations.class.php';
require_once INCLUDES_PATH . 'operations/Location.class.php';
require_once INCLUDES_PATH . 'operations/MyExams.class.php';
require_once INCLUDES_PATH . 'operations/Name.class.php';
require_once INCLUDES_PATH . 'operations/Reports.class.php';
require_once INCLUDES_PATH . 'operations/ReportTypes.class.php';
require_once INCLUDES_PATH . 'operations/RegisterForExam.class.php';
require_once INCLUDES_PATH . 'operations/RegisterStudentForExam.class.php';
require_once INCLUDES_PATH . 'operations/Room.class.php';
require_once INCLUDES_PATH . 'operations/Rooms.class.php';
require_once INCLUDES_PATH . 'operations/StudentState.class.php';
require_once INCLUDES_PATH . 'operations/StudentUpcomingExams.class.php';
require_once INCLUDES_PATH . 'operations/CreateInClassExam.class.php';
require_once INCLUDES_PATH . 'operations/TeacherExams.class.php';
require_once INCLUDES_PATH . 'operations/UpdateCategory.class.php';
require_once INCLUDES_PATH . 'operations/UpdateDefaultCategories.class.php';
require_once INCLUDES_PATH . 'operations/UpcomingExams.class.php';
require_once INCLUDES_PATH . 'operations/UpdateLocationName.class.php';
require_once INCLUDES_PATH . 'operations/UpdateLocationRooms.class.php';
require_once INCLUDES_PATH . 'operations/UpdateReport.class.php';
require_once INCLUDES_PATH . 'operations/UpdateRoom.class.php';
require_once INCLUDES_PATH . 'operations/UpdateExamState.class.php';
require_once INCLUDES_PATH . 'operations/UpdateExamTime.class.php';
require_once INCLUDES_PATH . 'operations/UpdateExamLocation.class.php';
require_once INCLUDES_PATH . 'operations/UpdateExamGrades.class.php';