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
$courseid = required_param('id', PARAM_INT);
 if (!has_capability('local/trainer_record:view', context_system::instance())){
 redirect($CFG->wwwroot.'/my');
 }
?>
        <script>
function onSelectcourse(select) {
                $.ajax({
                url: '<?php echo $CFG->wwwroot; ?>/local/trainer_record/ajax_trainer_record.php',
                type: 'POST',
                data: 'action=getUsers&groupid=' + select,
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
                    // $('#id_preteachback1').val(prescore);
                }
            });
     
};

function onSelectuser(select) {
               $('#id_submitbutton').prop('disabled', false);
               var groupid = $('.coursevars').find(":selected").val();
               var courseid = "<?php echo $courseid; ?>";
                $.ajax({
                url: '<?php echo $CFG->wwwroot; ?>/local/trainer_record/ajax_trainer_record.php',
                type: 'POST',
                data: 'action=getPreandPostscore&courseid=' + courseid + '&userid=' + select,
                success: function (data) {
                var obj = jQuery.parseJSON(data);
                    var prescore = obj.prescore;
                    var postscore = obj.postscore;
                    $('#id_userid').val(select);
                    $('#id_groupid').val(groupid);
                    $('#id_pretestscore').val(prescore);
                    $('#id_posttestscore').val(postscore);
                    $('#id_pretestscoredisplay').val(prescore);
                    $('#id_posttestscoredisplay').val(postscore);
                }
            });
     
};

        </script>
        
   
<?php
require_once($CFG->dirroot . '/user/editlib.php');
require_once('trainer_record_form.php');

$PAGE->set_url("/local/trainer_record/trainer_record.?id=$courseid");
$PAGE->set_context(context_system::instance());

$mform_trainer_record = new trainer_record_form(null, $courseid);

if ($mform_trainer_record->is_cancelled()) {
    redirect("$CFG->wwwroot/my");

} else if ($trainerdata = $mform_trainer_record->get_data()) {
    $trainerdata->courseid = $courseid;
    $DB->insert_record('trainer_record', $trainerdata);
    redirect("$CFG->wwwroot/local/trainer_record/trainer_record.php?id=$courseid");
} else {
    
    $feeddata = get_string('feeddata', 'local_trainer_record');

$coursename = $DB->get_field('course', 'shortname', array('id' => $courseid));
$courselink = new moodle_url('/course/view.php', array('id'=>$courseid));
$PAGE->navbar->add($coursename,$courselink);
$PAGE->navbar->add($feeddata);

$PAGE->set_title($feeddata);
$PAGE->set_heading($SITE->fullname);

echo $OUTPUT->header();
$mform_trainer_record->display();
echo $OUTPUT->footer();
}
