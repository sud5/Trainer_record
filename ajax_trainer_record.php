<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This file processes AJAX enrolment actions and returns JSON
 *
 * The general idea behind this file is that any errors should throw exceptions
 * which will be returned and acted upon by the calling AJAX script.
 *
 * @package    core_enrol
 * @copyright  2010 Sam Hemelryk
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
/**
 * Common module - All Ajax Hits at this page
 * Date Creation - 01/06/2014
 * Date Modification : 27/10/2014
 */
//define('AJAX_SCRIPT', true);

require_once('../../config.php');
global $USER, $DB;
// Must have the sesskey

$action = required_param('action', PARAM_ALPHANUMEXT);

$outcome = new stdClass();
$outcome->success = true;
$outcome->response = new stdClass();
$outcome->error = '';


switch ($action) {
    
    
    case 'getUsers':
        
        GLOBAL $USER, $DB;
        
        $groupid = optional_param('groupid', 0, PARAM_INT);
        $sql = "SELECT DISTINCT u.id, concat (u.firstname,' ',u.lastname) as fullname FROM {user} u "
                . " INNER JOIN {groups_members} gm ON gm.userid = u.id"
                . " INNER JOIN {role_assignments} ra ON ra.userid = u.id INNER JOIN {context} ct ON ct.id = ra.contextid "
                . "INNER JOIN {role} r ON r.id = ra.roleid WHERE NOT EXISTS "
                . "( SELECT tr.id FROM mdl_trainer_record tr WHERE u.id = tr.userid AND gm.groupid = tr.groupid) "
                . "and r.id =5 and gm.groupid = $groupid"; 
        
        $enrolled_students = $DB->get_records_sql($sql);
        $students = array();
        foreach ($enrolled_students as $id => $value){
          $students[$id] = $value->fullname;
        }
        $outcome->userarr= json_encode($students);
        break;

        case 'getPreandPostscore':
        
        GLOBAL $USER, $DB;
        
        $courseid = optional_param('courseid', 0, PARAM_INT);
        $userid = optional_param('userid', 0, PARAM_INT);

        $sql = "SELECT qg.id, qg.grade FROM {quiz_grades} as qg LEFT JOIN {quiz} as qu on (qg.quiz = qu.id) WHERE qu.quiztype = 'pre' and qg.userid = $userid and qu.course = $courseid";
        $quiz_grades = $DB->get_records_sql($sql);
        $prequiz_score = 0;
        $prescore = 0;
        if(!empty($quiz_grades)){
        $number_of_prequiz = count($quiz_grades);
        foreach ($quiz_grades as $quiz_grade_key => $quiz_grade_value){
          $prequiz_score += $quiz_grade_value->grade;
        }
        $prescore =  $prequiz_score/$number_of_prequiz;
        }
        $outcome->prescore = $prescore;



        $sql = "SELECT qg.id, qg.grade FROM {quiz_grades} as qg LEFT JOIN {quiz} as qu on (qg.quiz = qu.id) WHERE qu.quiztype = 'post' and qg.userid = $userid and qu.course = $courseid";
        $quiz_grades_post = $DB->get_records_sql($sql);
        $postquiz_score = 0;
        $postscore = 0;
        if(!empty($quiz_grades_post)){
        $number_of_postquiz = count($quiz_grades_post);
        foreach ($quiz_grades_post as $quiz_grade__post_key => $quiz_grade__post_value){
          $postquiz_score += $quiz_grade__post_value->grade;
        }
        $postscore =  $postquiz_score/$number_of_postquiz;
        }
        $outcome->postscore = $postscore;
        
        break;
        
        case 'getFilledusers':
        
        GLOBAL $USER, $DB;
        
        $groupid = optional_param('groupid', 0, PARAM_INT);
        $sql = "SELECT u.id, concat (u.firstname,' ',u.lastname) as fullname "
                . "FROM {user} u left JOIN {trainer_record} tr ON tr.userid = u.id where tr.groupid = $groupid";
        $enrolled_students = $DB->get_records_sql($sql);
        $students = array();
        foreach ($enrolled_students as $id => $value){
          $students[$id] = $value->fullname;
        }
        $outcome->userarr= json_encode($students);
        // $outcome->userarr= json_encode(array('5' =>'user5', '6'=> 'user6'));
        break;

        case 'getFulldata':
        
        GLOBAL $USER, $DB;
        
        $groupid = optional_param('groupid', 0, PARAM_INT);
        $userid = optional_param('userid', 0, PARAM_INT);

        $sql = "SELECT * from {trainer_record} as tr where tr.userid = $userid and tr.groupid = $groupid";
//        $outcome->userarr= json_encode(array('5' => $groupid, '6'=> $userid));
        $outcome = $DB->get_record_sql($sql);
        break;
        
    default:
        throw new enrol_ajax_exception('unknowajaxaction');
}

echo json_encode($outcome);
die();
