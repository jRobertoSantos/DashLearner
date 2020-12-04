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

defined('MOODLE_INTERNAL') || die();

class block_dashlearner extends block_base {

    public function init() {
        $this->title = get_string('dashlearner', 'block_dashlearner');
    }

    public function get_content() {
        global $CFG;

        $course = $this->page->course;

        $this->content = new stdClass;

        $this->content->text = "<li> <a href= {$CFG->wwwroot}/blocks/dashlearner/notas.php?course_id={$course->id}
                          target=_blank>" . get_string('notas', 'block_dashlearner') . "</a>";
        $this->content->text .= "<li> <a href= {$CFG->wwwroot}/blocks/dashlearner/modules.php?course_id={$course->id}
                          target=_blank>" . get_string('modulos', 'block_dashlearner') . "</a>";
        $this->content->text .= "<li> <a href= {$CFG->wwwroot}/blocks/dashlearner/acessos.php?course_id={$course->id}
                          target=_blank>" . get_string('acessos', 'block_dashlearner') . "</a>";
        $this->content->footer = '<hr/>';
        return $this->content;
    }
}  
