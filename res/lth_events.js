$(document).ready(function() {
    var storage = $("#storage").val();
    $('#calendar').fullCalendar({
        header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
        },
        defaultDate: Date(),
        dayClick: function(date, allDay, jsEvent, view) {
            var dateFormat = new DateFormat("yyyy-MM-dd HH:mm:ss");
            var str = dateFormat.format(new Date(date)); // Date to String

            var allDayParam;
            if (allDay) {
                    //alert('Clicked on the entire day: ' + str);
                    allDayParam = 1;
            } else {
                    //alert('Clicked on the slot: ' + str);
                    allDayParam = 0;
            }

            $.ajax({
                    type: 'POST',
                    url: 'index.php',
                    data: {
                        eID : 'tx_lthevents_pi1',
                        action : 'getEventForm',
                        sid : Math.random(),
                        allday: allDayParam,
                        dateTm: str
                    },
                    dataType: 'json',
                    success: function(data) {
                            $('#editdialog').html(data);
                            //
                            // initialize input widgets first
                            $('#caldlgfrm .time').timepicker({
                                'showDuration': true,
                                'timeFormat': 'H:i:s'
                            });

                            $('#caldlgfrm .date').datepicker({
                                'format': 'yyyy-mm-dd',
                                'autoclose': true
                            });

                            // initialize datepair
                            var caldlgfrmEl = document.getElementById('caldlgfrm');
                            var datepair = new Datepair(caldlgfrmEl);
                            //
                    },
                    error:function() {
                            alert('Error occur');
                    }
            });
            $("#editdialog").dialog({
                    height: 450,
                    width: 600,
                    title: "New/Edit event",
                    modal: true
                 });
                 $("#editdialog").dialog("open");
                //$( "#editdialog" ).dialog().dialog( "open" );
        },
        eventClick: function(calEvent, jsEvent, view) {

        alert('Event: ' + calEvent.title);
        alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
        alert('View: ' + view.name);

        // change the border color just for fun
        $(this).css('border-color', 'red');

        },
        editable: true,
        eventLimit: true, // allow "more" link when too many events
        events: {
            url : 'index.php',
            data: {
                eID : 'tx_lthevents_pi1',
                action : 'getEvents',
                query : JSON.stringify({"storage":storage}),
                sid : Math.random(),
            },
            error: function(errMsg) {
                $('#script-warning').show();
            }
        },
        loading: function(bool) {
            $('#loading').toggle(bool);
        }
    });
});

function saveEditForm() {
        var event_subject = $("#event_subject").val();
        var event_description = $("#event_description").val();
        var event_start_date = $("#event_start_date").val();
        var event_start_time = $("#event_start_time").val();
        var event_end_date = $("#event_end_date").val();
        var event_end_time = $("#event_end_time").val();
        //var storage = $("#storage").val();

        var dlg_allday = 0;
        if($("#dlg_allday").prop('checked')){
                dlg_allday = 1;
        }
        
        var dlg_signup = 0;
        if($("#dlg_signup").prop('checked')){
                dlg_signup = 1;
        }

        if(event_subject == ""){
                alert("Input subject");
                return;
        }

        $.ajax({
            type: 'POST',
            url: 'index.php',
            data: {
                eID : 'tx_lthevents_pi1',
                action : 'saveEventForm',
                query : JSON.stringify({"storage":storage, "event":event_subject,"description":event_description,"start":event_start_date + " " + event_start_time,"end":event_end_date + " " + event_end_time,"dlg_allday":dlg_allday,"dlg_signup":dlg_signup}),
                sid : Math.random(),
                allday: dlg_allday
            },
            dataType: 'json',
            success: function(data) {
                alert('tjo');
                $('#editdialog').dialog('close');
            },
            error:function() {
                 alert('Error occured');
            }
        });
}