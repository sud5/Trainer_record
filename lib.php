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

defined('MOODLE_INTERNAL') || die;
/**
 * Event handler for 'user_created'
 * For 'email' authentication (only) add this user
 * to the defined role and company.
 * @param mixed $user user id or user object
 */


function local_trainer_record_extend_navigation(global_navigation $nav) {
global $CFG, $PAGE, $USER, $DB, $COURSE;
if($COURSE->id > 1){
if (has_capability('local/trainer_record:view', context_system::instance())){
    $node = $nav->add(
    get_string('feeddata', 'local_trainer_record'),
        new moodle_url($CFG->wwwroot . '/local/trainer_record/trainer_record.php?id='. $COURSE->id),
        navigation_node::TYPE_CUSTOM,
        '', 'statistics',
        null
    );
        $node->showinflatnavigation = true;
        $node = $nav->add(
    get_string('updatedata', 'local_trainer_record'),
        new moodle_url($CFG->wwwroot . '/local/trainer_record/edit_trainer_record.php?id='. $COURSE->id),
        navigation_node::TYPE_CUSTOM,
        '', 'statistics2',
        null
    );
    $node->showinflatnavigation = true;
 }
}
}

