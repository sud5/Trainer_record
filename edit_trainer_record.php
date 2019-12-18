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
 * user trainer_record page.
 *
 * @package    core
 * @subpackage auth
 * @copyright  1999 onwards Martin Dougiamas  http://dougiamas.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once('../../config.php');
require_login();
 if (!has_capability('local/trainer_record:view', context_system::instance())){
 redirect($CFG->wwwroot.'/my');
 }
 $courseid = required_param('id', PARAM_INT);
?>
<html>
        <script>
            
function onSelectcourse(select) {
                $.ajax({
                url: '<?php echo $CFG->wwwroot; ?>/local/trainer_record/ajax_trainer_record.php',
                type: 'POST',
                data: 'action=getFilledusers&groupid=' + select,
                success: function (data) {
                 var obj = jQuery.parseJSON(data);
                     var users = obj.userarr;
                     var userslist = jQuery.parseJSON(users);
                     var userfeed = "<option value=''>Select User</option>";
                     for (var k in userslist){
                      if (userslist.hasOwnProperty(k)) {
                      userfeed += "<option value= " + k + ">" + userslist[k] + "</option>";
                       }
                       }
                     $(".uservars").html(userfeed);
                }
            });
     
};

function onSelectuser(select) {
$('#id_submitbutton').prop('disabled', false);
               var groupid = $('.coursevars').find(":selected").val(); 
                $.ajax({
                url: '<?php echo $CFG->wwwroot; ?>/local/trainer_record/ajax_trainer_record.php',
                type: 'POST',
                data: 'action=getFulldata&groupid=' + groupid + '&userid=' + select,
                success: function (data) {
                var obj = jQuery.parseJSON(data);
                    $('#id_userid').val(obj.userid);
                    $('#id_groupid').val(obj.groupid);
                    $('#id_pretestscore').val(obj.pretestscore);
                    $('#id_preteachback1').val(obj.preteachback1);
                    $('#id_preteachback2').val(obj.preteachback2);
                    $('#id_preteachback3').val(obj.preteachback3);
                    $('#id_posttestscore').val(obj.posttestscore);
                    $('#id_pretestscoredisplay').val(obj.pretestscore);
                    $('#id_posttestscoredisplay').val(obj.posttestscore);
                    $('#id_liveobservation1').val(obj.liveobservation1);
                    $('#id_modeobservation1').val(obj.modeobservation1);
                    $('#id_observationdate1').val(obj.observationdate1);
                    $('#id_tclocation1').val(obj.tclocation1);
                    $('#id_liveobservation2').val(obj.liveobservation2);
                    $('#id_modeobservation2').val(obj.modeobservation2);
                    $('#id_observationdate2').val(obj.observationdate2);
                    $('#id_tclocation2').val(obj.tclocation2);
                    if(obj.liveobservation2 < 0){
                    $('#id_liveobservation2').val('');
                      }
                        if (obj.liveobservation1 > 2.7){
                    $('#id_liveobservation2').prop('disabled', true);
    	            $('#id_modeobservation2').prop('disabled', true);
    	            $('#id_observationdate2_day').prop('disabled', true);
                    $('#id_observationdate2_month').prop('disabled', true);
    	            $('#id_observationdate2_year').prop('disabled', true);
    	            $('#id_observationdate2_calendar').hide();
    	            $('#id_tclocation2').prop('disabled', true);
    	
                 }
                }
            });
     
};
        </script>
    </body>
</html>

<?php
require_once($CFG->dirroot . '/user/editlib.php');
require_once('edit_trainer_record_form.php');

$PAGE->set_url('/local/edit_trainer_record.php');
$PAGE->set_context(context_system::instance());

$mform_trainer_record = new edit_trainer_record_form(null, $courseid);

if ($mform_trainer_record->is_cancelled()) {
    redirect("$CFG->wwwroot/my");

} else if ($trainerdata = $mform_trainer_record->get_data()) {
     $trainerdata->courseid = $courseid;
     $already_exist = $DB->get_field('trainer_record','id',array('userid'=> $trainerdata->userid, 'courseid' => $trainerdata->courseid));
     $trainerdata->id = $already_exist;
     $DB->update_record('trainer_record', $trainerdata); 
    redirect("$CFG->wwwroot/local/trainer_record/edit_trainer_record.php?id=$courseid");
}

$updatedata = get_string('updatedata', 'local_trainer_record');

$coursename = $DB->get_field('course', 'shortname', array('id' => $courseid));
$courselink = new moodle_url('/course/view.php', array('id'=>$courseid));
$PAGE->navbar->add($coursename,$courselink);
$PAGE->navbar->add($updatedata);

$PAGE->set_title($updatedata);
$PAGE->set_heading($SITE->fullname);

echo $OUTPUT->header();
$mform_trainer_record->display();
echo $OUTPUT->footer();
