require('./bootstrap');

import {Calendar} from "@fullcalendar/core";
import { formatDate } from '@fullcalendar/core'
import dayGridPlugin from "@fullcalendar/daygrid";
import interaction from "@fullcalendar/interaction";
import bootstrapPlugin from "@fullcalendar/bootstrap";

Echo.channel('private-App.User.'+window.Laravel.user.id)
    .listen('MessageReceived', (e) => {
        console.log(e.sender, e.thread, e.message);
    });

Echo.channel('private-App.User.'+window.Laravel.user.id)
    .listen('TaskAssigned', (e) => {
        var notifCount = parseInt($('#notif-count-badge').text(), 0);
        notifCount = notifCount + 1;
        $('#notif-count-badge').text(notifCount);
        $('#notif-count-badge').show();
        $('#notif-bell-dark').removeClass("far").addClass("fas");
    });

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    if (calendarEl !== null ) {
        var eventListingURL = "/calendar/user";
        var projectId = "";

        if (calendarEl.getAttribute("data-model") == 'project') {
            projectId = calendarEl.getAttribute("data-model-id");
            eventListingURL = "/calendar/project/" + projectId;
        }

        var calendar = new Calendar(calendarEl, {
            plugins: [ dayGridPlugin, interaction, bootstrapPlugin ],
            events: eventListingURL,
            displayEventTime: false,
            editable: true,
            selectable: true,
            // selectHelper: true,
            select: function (selectionInfo) {
                var start = moment(selectionInfo.start).format('YYYY-MM-DD');
                var end = moment(selectionInfo.end).subtract(1, "minute").format('YYYY-MM-DD');

                $('#calendarModal #start_on').val(start);
                $('#calendarModal #due_on').val(end);
                $('#calendarModal').modal("show");

                $('#submitButton').click( function() {
                    var title = $('#calendarModal #name').val();
                    calendar.addEvent({
                        title: title,
                        start: start,
                        end: selectionInfo.end,
                        allDay: true
                    });
                });
            },
            eventDrop: function (eventDropInfo, delta) {
                var start = moment(eventDropInfo.event.start).format("YYYY-MM-DD HH:mm:ss");
                var end = moment(eventDropInfo.event.end).subtract(1, "minute").format("YYYY-MM-DD HH:mm:ss");
                var csrf = $('meta[name="csrf-token"]'). attr('content');

                $.ajax({
                    url: '/tasks/'+eventDropInfo.event.extendedProps.id,
                    data: {
                        _token: csrf,
                        _method: "PUT",
                        start_on: start,
                        due_on: end
                    },
                    type: "POST",
                    success: function (response) {

                    }
                });
            }
        });


        if (calendarEl.getAttribute("model") == 'project') {
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                calendar.render();
            });
        } else
            calendar.render();

    }
});



// File#: _1_drawer
// Usage: codyhouse.co/license
(function() {


    // button.addEventListener('click', () => {
    //     pane.classList.toggle('open');
    // });
}());
