// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.

/**
 * Version details
 *
 * @package block_course_slider
 * @copyright 2016 Kyriaki Hadjicosta (Coventry University)
 * @copyright
 *
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

require(['jquery'],function($) {

$(document).ready(function() {
    
    $('#id_submitbutton').prop('disabled', true);
    // Generate courseslider and associate it with courseslidernav.
    $('#id_pretestscoredisplay').prop('disabled', true);
    $('#id_posttestscoredisplay').prop('disabled', true);
    $("#id_liveobservation1").focusout(function () {
		
	var live_observation = $('#id_liveobservation1').val();
        
    if (live_observation > 2.7){
    	$('#id_liveobservation2').prop('disabled', true);
    	$('#id_modeobservation2').prop('disabled', true);
    	$('#id_observationdate2_day').prop('disabled', true);
    	$('#id_observationdate2_month').prop('disabled', true);
    	$('#id_observationdate2_year').prop('disabled', true);
    	$('#id_observationdate2_calendar').hide();
    	$('#id_tclocation2').prop('disabled', true);
    	
    }else{
    	$('#id_liveobservation2').prop('disabled', false);
    	$('#id_modeobservation2').prop('disabled', false);
    	$('#id_observationdate2_day').prop('disabled', false);
    	$('#id_observationdate2_month').prop('disabled', false);
    	$('#id_observationdate2_year').prop('disabled', false);
    	$('#id_observationdate2_calendar').show();
    	$('#id_tclocation2').prop('disabled', false);
    }
    });
        // $('.courseslider').owlCarousel({
        // loop:true,
        // margin:30,
        // nav: true,
        // dots: false,
        // responsive:{
        //     0:{
        //         items:1
        //     },
        //     600:{
        //         items:3
        //     },
        //     1000:{
        //         items:4
        //     }
        // }
        // });
    });
});
