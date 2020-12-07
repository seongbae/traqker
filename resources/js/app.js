require('./bootstrap');

import {Calendar} from "@fullcalendar/core";
import { formatDate } from '@fullcalendar/core'
import dayGridPlugin from "@fullcalendar/daygrid";
import interaction from "@fullcalendar/interaction";
import bootstrapPlugin from "@fullcalendar/bootstrap";
import Vue from 'vue'
import App from './views/app.vue'

// const app = new Vue({
//     el: '#app',
//     components: { App }
// });

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    if (calendarEl !== null ) {
        var calendar = new Calendar(calendarEl, {
            plugins: [ dayGridPlugin, interaction, bootstrapPlugin ],
            events: "/calendar/user",
            displayEventTime: false,
            editable: true,
            eventContent: function (info) {

            },
            selectable: true,
            selectHelper: true,
            select: function (selectionInfo) {
                var start = moment(selectionInfo.start).format('YYYY-MM-DD HH:mm:ss');
                var end = moment(selectionInfo.end).subtract(1, "minute").format('YYYY-MM-DD HH:mm:ss');
                var csrf = $('meta[name="csrf-token"]'). attr('content');
                var title = prompt('Title:');

                if (title) {
                    $.ajax({
                        url: "/tasks",
                        data: {
                            _token: csrf,
                            name: title,
                            start_on: start,
                            due_on: end
                        },
                        type: "POST",
                        success: function (data) {
                            calendar.addEvent({
                                title: title,
                                start: selectionInfo.start,
                                end: selectionInfo.end,
                                allDay: selectionInfo.allDay
                            });
                        }
                    });
                }
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

        calendar.render();
    }



});
