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

/**
 * User sign-up form.
 *
 * @package    core
 * @subpackage auth
 * @copyright  1999 onwards Martin Dougiamas  http://dougiamas.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/formslib.php');
require_once($CFG->dirroot.'/user/profile/lib.php');
require_once($CFG->dirroot . '/user/editlib.php');
require_once($CFG->libdir . '/grouplib.php');

class edit_trainer_record_form extends moodleform {
    function definition() {
        global $USER, $CFG, $PAGE;
        $PAGE->requires->js('/local/trainer_record/module.js');
        $mform = $this->_form;
        $courseid = $this->_customdata;
        $trainer_groups = groups_get_all_groups($courseid, $USER->id);

        $coursevars = array();
        foreach($trainer_groups as $trainer_groups_key => $trainer_groups_value){
        $coursevars[$trainer_groups_value->id] = $trainer_groups_value->name;
        }

        $options = "<option value=''>" . get_string('select_course', 'local_trainer_record') .
                   "</option>";
        foreach ($coursevars as $i => $value) {
            $options .= "<option value='{$i}'>$value</option>";
        }
        $mform->addElement('html', '<div class = "row mr_top_items">');

        $mform->addElement('html', '<div class = "col-sm-5">');

        $select = "<select class='coursevars form-control' onchange='onSelectcourse(this.value)'>
                 $options</select>";
        $htmlcourse = "<div class='fitem row'><div class='fitemtitle col-sm-6'>Select Batch:</div><div class='felement col-sm-6'>
                 $select</div></div>";
        $mform->addElement('html', $htmlcourse);

        $mform->addElement('html', '</div>');

        $mform->addElement('html', '<div class = "col-sm-4">');

        $options = "<option value=''>" . get_string('select_course_first', 'local_trainer_record') .
                   "</option>";
         
        $select = "<select class='uservars form-control' onchange='onSelectuser(this.value)'>
                 $options</select>";
        $htmluser = "<div class='useritem row'><div class='fitemtitle col-sm-6'>Select User:</div><div class='userelement col-sm-6'>
                 $select</div></div>";


        $mform->addElement('html', $htmluser);
        $mform->addElement('html', '</div>');
        $mform->addElement('html', '</div>');

            $mform->addElement('html', '<div class = "my_hidden_class">');

            $mform->addElement('text', 'groupid', null, 'maxlength="20" size="5"');
            $mform->setType('groupid', PARAM_FLOAT);

            $mform->addElement('text', 'userid', null, 'maxlength="20" size="5"');
            $mform->setType('userid', PARAM_FLOAT);

            $mform->addElement('text', 'pretestscore', null, 'maxlength="20" size="5"');
            $mform->setType('pretestscore', PARAM_FLOAT);

            $mform->addElement('text', 'posttestscore', null, 'maxlength="20" size="5"');
            $mform->setType('posttestscore', PARAM_FLOAT);

            $mform->addElement('html', '</div>');


            $mform->addElement('header', 'pretest', get_string('pretest', 'local_trainer_record'), '');
            $mform->addElement('html', '<div class = "row custom_form_row">');
            $mform->addElement('html', '<div class = "col-sm-6">');
            $mform->addElement('text', 'pretestscoredisplay', get_string('pretestscore',  'local_trainer_record'), 'maxlength="20" size="5"');
            $mform->setType('pretestscoredisplay', PARAM_FLOAT);
            $mform->addElement('html', '</div>');
            $mform->addElement('html', '</div>');

            $mform->addElement('html', '<div class = "row custom_form_row col_width_50">');
            $mform->addElement('html', '<div class = "col-sm-4">');
            $mform->addElement('text', 'preteachback1', get_string('preteachback1',  'local_trainer_record'), 'maxlength="20" size="5"');
            $mform->setType('preteachback1', PARAM_TEXT);
            $mform->addRule('preteachback1', get_string('rangeexceeds', 'local_trainer_record'), 'required', null, 'client');
            $mform->addElement('html', '</div>');

            $mform->addElement('html', '<div class = "col-sm-4">');
            $mform->addElement('text', 'preteachback2', get_string('preteachback2',  'local_trainer_record'), 'maxlength="20" size="5"');
            $mform->setType('preteachback2', PARAM_TEXT);
            $mform->addRule('preteachback2', get_string('rangeexceeds', 'local_trainer_record'), 'required', null, 'client');
            $mform->addElement('html', '</div>');
            // $mform->addElement('html', '</div>');
            
            // $mform->addElement('html', '<div class = "row custom_form_row">');
            $mform->addElement('html', '<div class = "col-sm-4">');
            
            $mform->addElement('text', 'preteachback3', get_string('preteachback3',  'local_trainer_record'), 'maxlength="20" size="5"');
            $mform->setType('preteachback3', PARAM_TEXT);
            $mform->addRule('preteachback3', get_string('rangeexceeds', 'local_trainer_record'), 'required', null, 'client');           

            $mform->addElement('html', '</div>');
            $mform->addElement('html', '</div>');


            $mform->addElement('header', 'posttest', get_string('posttest', 'local_trainer_record'), '');
            $mform->addElement('html', '<div class = "row custom_form_row">');
            $mform->addElement('html', '<div class = "col-sm-6">');
            $mform->addElement('text', 'posttestscoredisplay', get_string('posttestscore','local_trainer_record'), 'maxlength="20" size="5"');
            $mform->setType('posttestscoredisplay', PARAM_FLOAT);
            $mform->addElement('html', '</div>');
            $mform->addElement('html', '</div>');

            $mform->addElement('header', 'headliveobservation1', get_string('headliveobservation1', 'local_trainer_record'), '');
            $mform->addElement('html', '<div class = "row custom_form_row col_width_33">');
            $mform->addElement('html', '<div class = "col-sm-2">');

            $mform->addElement('text', 'liveobservation1', get_string('liveobservation1',  'local_trainer_record'), 'maxlength="20" size="5"');
            $mform->setType('liveobservation1', PARAM_TEXT);
            $mform->addRule('liveobservation1', get_string('rangeexceeds', 'local_trainer_record'), null, null, 'client');
            $mform->addElement('html', '</div>');

            $mform->addElement('html', '<div class = "col-sm-2">');

            $mode_observation = array('live' => 'Live', 'remote' => 'Remote', 'footage' => 'Footage');
            $mform->addElement('select', 'modeobservation1', get_string('modeobservation1', 'local_trainer_record'), $mode_observation);
            $mform->addElement('html', '</div>');
            // $mform->addElement('html', '</div>');

           
            // $mform->addElement('html', '<div class = "row custom_form_row">');
            $mform->addElement('html', '<div class = "col-sm-5">');

            $mform->addElement('date_selector', 'observationdate1', get_string('observationdate1', 'local_trainer_record'),
            array('optional' => false));

            $mform->addElement('html', '</div>');

            $mform->addElement('html', '<div class = "col-sm-3">');
            $mform->addElement('text', 'tclocation1', get_string('tclocation',  'local_trainer_record'), 'maxlength="100" size="25"');
            $mform->setType('tclocation1', PARAM_TEXT);
            $mform->addRule('tclocation1', get_string('rangeexceeds', 'local_trainer_record'), null, null, 'client');

            $mform->addElement('html', '</div>');
            $mform->addElement('html', '</div>');
            
            $mform->addElement('header', 'headliveobservation2', get_string('headliveobservation2', 'local_trainer_record'), '');
            $mform->addElement('html', '<div class = "row custom_form_row col_width_33">');
            $mform->addElement('html', '<div class = "col-sm-2">');

            $mform->addElement('text', 'liveobservation2', get_string('liveobservation2',  'local_trainer_record'), 'maxlength="20" size="5"');
            $mform->setType('liveobservation2', PARAM_TEXT);
            $mform->addRule('liveobservation2', get_string('rangeexceeds', 'local_trainer_record'), null, null, 'client');
            $mform->addElement('html', '</div>');

            $mform->addElement('html', '<div class = "col-sm-2">');
           
            $mform->addElement('select', 'modeobservation2', get_string('modeobservation2', 'local_trainer_record'), $mode_observation);
            $mform->addElement('html', '</div>');
            // $mform->addElement('html', '</div>');

            // $mform->addElement('html', '<div class = "row custom_form_row">');
            $mform->addElement('html', '<div class = "col-sm-5">');
            $mform->addElement('date_selector', 'observationdate2', get_string('observationdate2', 'local_trainer_record'),
            array('optional' => false), null);

            $mform->addElement('html', '</div>');

            $mform->addElement('html', '<div class = "col-sm-3">');
            $mform->addElement('text', 'tclocation2', get_string('tclocation',  'local_trainer_record'), 'maxlength="100" size="25"');
            $mform->setType('tclocation2', PARAM_TEXT);
            $mform->addRule('tclocation2', get_string('rangeexceeds', 'local_trainer_record'), null, null, 'client');
            $mform->addElement('html', '</div>');
            $mform->addElement('html', '</div>');

            $mform->addElement('hidden', 'id', $courseid);
            $mform->setType('id', PARAM_RAW);


        // buttons
        $this->add_action_buttons(true, get_string('update', 'local_trainer_record'));

    }


    function validation($data, $files) {
        global $CFG, $DB;
        $errors = array();
        if($data['preteachback1'] < 0 || $data['preteachback1'] > 3 || !is_numeric($data['preteachback1'])) {
            $errors['preteachback1'] = get_string('notlimit', 'local_trainer_record');
        }


        if($data['preteachback2'] < 0 || $data['preteachback2'] > 3 || !is_numeric($data['preteachback2'])) {
            $errors['preteachback2'] = get_string('notlimit', 'local_trainer_record');
        }


        if($data['preteachback3'] < 0 || $data['preteachback3'] > 3 || !is_numeric($data['preteachback3'])) {
            $errors['preteachback3'] = get_string('notlimit', 'local_trainer_record');
        }


        if($data['liveobservation1'] < 0 || $data['liveobservation1'] > 3 || !is_numeric($data['liveobservation1'])) {
            $errors['liveobservation1'] = get_string('notlimit', 'local_trainer_record');
        }

        if(empty($data['tclocation1'])){
            $errors['tclocation1'] = get_string('locationrequired', 'local_trainer_record');
        }
         
//        if($data['liveobservation1'] <= 2.7){
//        if($data['liveobservation2'] < 0 || $data['liveobservation2'] > 3 || !is_numeric($data['liveobservation2'])) {
//            $errors['liveobservation2'] = get_string('notlimit', 'local_trainer_record');
//        } 
//
//        if(empty($data['tclocation2'])){
//            $errors['tclocation2'] = get_string('locationrequired', 'local_trainer_record');
//        }
//        }
        return $errors;

    }


}
