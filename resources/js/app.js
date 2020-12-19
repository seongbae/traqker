require('./bootstrap');

import {Calendar} from "@fullcalendar/core";
import { formatDate } from '@fullcalendar/core'
import dayGridPlugin from "@fullcalendar/daygrid";
import interaction from "@fullcalendar/interaction";
import bootstrapPlugin from "@fullcalendar/bootstrap";
import Vue from 'vue'
import App from './views/app.vue'

import { BootstrapVue } from 'bootstrap-vue'
Vue.component('thread', require('./components/Thread.vue').default);
Vue.component('reply', require('./components/Reply.vue').default);
Vue.component('channel-subscribe', require('./components/ChannelSubscribe.vue').default);
Vue.use(BootstrapVue)

const app = new Vue({
    el: '#app',
    components: { App }
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
            //height: 650,
            // dayClick: function(date, jsEvent, view) {
            //     $("#myModal").modal("show");
            // },
            // eventClick:  function(event, jsEvent, view) {
            //     $('#modalTitle').html(event.title);
            //     $('#modalBody').html(event.description);
            //     $('#eventUrl').attr('href',event.url);
            //     $('#calendarModal').modal();
            // },
            // eventContent: function (info) {
            //
            // },
            selectable: true,
            selectHelper: true,
            select: function (selectionInfo) {
                //$("#myModal").modal("show");
                var start = moment(selectionInfo.start).format('YYYY-MM-DD HH:mm:ss');
                var end = moment(selectionInfo.end).subtract(1, "minute").format('YYYY-MM-DD HH:mm:ss');
                var csrf = $('meta[name="csrf-token"]'). attr('content');
                var title = prompt('Title:');

                if (title) {
                    $.ajax({
                        url: "/tasks",
                        data: {
                            _token: csrf,
                            project_id: projectId,
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


        if (calendarEl.getAttribute("model") == 'project') {
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                calendar.render();
            });
        } else
            calendar.render();

        $(document).ready(function(){
            $("#newTaskForm").submit(function(event){
                alert('test');
                submitForm();
                return false;
            });


        });

        // function submitForm(){
        //     alert('test');
        //     $.ajax({
        //         type: "POST",
        //         url: "saveContact.php",
        //         cache:false,
        //         data: $('form#contactForm').serialize(),
        //         success: function(response){
        //             $("#contact").html(response)
        //             $("#contact-modal").modal('hide');
        //         },
        //         error: function(){
        //             alert("Error");
        //         }
        //     });
        // }
    }
});
