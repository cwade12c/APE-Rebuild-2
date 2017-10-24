<!DOCTYPE html>
<html>
<?php include "teacher_navbar.php"; ?>
<head>
    <title>Teacher Reports</title>
</head>
<body>
<form>
    <fieldset>
        <legend>Create Report</legend>
        Select APE:
        <form>
            <select>
                <option value="Winter17">Winter 2017</option>
                <option value="Fall16">Fall 2016</option>
                <option value="Summer16">Summer 2016</option>
                <option value="Spring16">Spring 2016</option>
                <option value="Winter16">Winter 2016</option>
            </select>
        </form>
        <br><br>
        Report Type:
        <form>
            <select>
                <option value="finalGrades">Final Grades</option>
                <option value="RegStudents">Registered Students</option>
            </select>
        </form>

        <form action="./teacher_create_report.php">
            <input type="submit" value="Create New Type">
        </form>

        <br><br>

        <div class="field"><input name="username" type="checkbox" value="1"/>
            Username
        </div>
        <label for="ewuid">&nbsp;</label>
        <div class="field"><input name="ewuid" type="checkbox" value="1"
                                  checked="checked"/> EWU ID
        </div>

        <label for="first_name">&nbsp;</label>
        <div class="field"><input name="first_name" type="checkbox" value="1"
                                  checked="checked"/> First Name
        </div>

        <label for="last_name">&nbsp;</label>
        <div class="field"><input name="last_name" type="checkbox" value="1"
                                  checked="checked"/> Last Name
        </div>

        <label for="email">&nbsp;</label>
        <div class="field"><input name="email" type="checkbox" value="1"/> Email
        </div>

        <label for="address">&nbsp;</label>
        <div class="field"><input name="address" type="checkbox" value="1"/>
            Mailing Address
        </div>

        <label for="seat_id">&nbsp;</label>
        <div class="field"><input name="seat_id" type="checkbox" value="1"
                                  checked="checked"/> Seat ID
        </div>

        <label for="attempts">&nbsp;</label>
        <div class="field"><input name="attempts" type="checkbox" value="1"/>
            Number of Attempts
        </div>

        <label for="pass">&nbsp;</label>
        <div class="field"><input name="pass" type="checkbox" value="1"
                                  checked="checked"/> Pass/Fail
        </div>

        <label for="state">&nbsp;</label>
        <div class="field"><input name="state" type="checkbox" value="1"
                                  checked="checked"/> Student State
        </div>

        <label for="score">&nbsp;</label>
        <div class="field"><input name="score" type="checkbox" value="1"
                                  checked="checked"/> Total Score
        </div>

        <label for="itemized">&nbsp;</label>
        <div class="field"><input name="itemized" type="checkbox" value="1"/>
            Itemized Score
        </div>

        <label for="possible">&nbsp;</label>
        <div class="field"><input name="possible" type="checkbox" value="1"/>
            Include Possible Score
        </div>

        <br>
        <input type="submit" value="Create Report">
    </fieldset>
</form>
</body>
</html>