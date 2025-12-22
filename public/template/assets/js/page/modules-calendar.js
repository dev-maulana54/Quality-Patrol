"use strict";

$.ajax({
  url: $('.baseUrl').val() + 'calendar-events/get-all-data',
  method: 'GET',
  dataType: 'json',
  success: function (response) {
    let allEventData = []
    response.forEach(function (row) {
      allEventData.push({
        "title": row.title,
        "start" : row.start,
        "end": row.end,
        "backgroundColor": "#6777ef",
        "borderColor": "#6777ef",
        "textColor": "#fff"
      })
    })
    $("#myEvent").fullCalendar({
      locale: 'id',
      height: 'auto',
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek,agendaDay,listWeek'
      },
      editable: false,
      events: allEventData

    });
  },
  error: {}
});

