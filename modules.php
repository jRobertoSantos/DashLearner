<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
//defined('MOODLE_INTERNAL') || die();

require('../../config.php');
require('lib.php');
$course_id = required_param('course_id', PARAM_INT);

// $user_id = required_param('user_id', PARAM_INT);

$user_id   = $USER->id;

global $DB, $CFG;


$courseparams = get_course($course_id);
$coursename = get_string('course', 'block_dashlearner') . ": " . $courseparams->fullname;

?>
<html style="background-color: #f4f4f4;">
<div style="width: 250px;height: 80%;position:absolute;left:0; right:0;top:0; bottom:0;margin:auto;max-width:100%;max-height:100%;
overflow:auto;background-color: white;border-radius: 0px;padding: 20px;border: 2px solid darkgray;text-align: center;">
    <div style="text-align: left">
        <form action="modulesurl.php" method="get">
            <br><br>
            <H1><?php echo get_string('submissions','block_dashlearner'); ?></H1>
            <br> <br>
            <?php
            foreach (block_dashlearner_get_course_modules_activities($course_id) as $result) {
                echo "<li> <a href= {$CFG->wwwroot}/blocks/dashlearner/modulesurl.php?course_id={$course_id}&module={$result->name}
                      target=_blank>" . get_string($result->name, 'block_dashlearner') . "</a>";
            }
            ?>
            <br><br>
    </div>
    </form>
</div>
</html>
