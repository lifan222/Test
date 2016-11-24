<?php
    $usertype = $this->session->userdata("usertype");
    if($usertype == "Admin" || $usertype == "Teacher" || $usertype == "TeacherManager" || $usertype == "Student" || $usertype == "Receptionist" || $usertype == "Salesman") {

?>


  <div class="row">
    <div class="col-sm-12">
        <div class="box box-primary">
            <div class="box-body no-padding">
                <!-- THE CALENDAR -->
                <div id="calendar">
                    <div id="custom-header" class="row">
                        <div id="header-left">
                            <p>课程日历</p>
                            <p>Curriculum Calendar</p>
                        </div>

                        <div id="header-center">
                            <div class="fc-button-group">
                                <button id="cus-button-prev" type="button" class="btn btn-default"> ＜ </button>
                                <div>
                                    <p id="center-title"></p>
                                    <button id="cus-button-today" type="button" class="btn btn-default">今日</button>
                                </div>
                                <button id="cus-button-next" type="button" class="btn btn-default"> ＞ </button>
                            </div>



                        </div>

                        <div id="header-right">
                            <div class="fc-button-group">
                                <button id="cus-button-month" type="button" class="btn btn-default">月</button>
                                <button id="cus-button-week" type="button" class="btn btn-default">周</button>
                                <button id="cus-button-day" type="button" class="btn btn-default"> 日 </button>
                            </div>
                        </div>



                </div>
            </div><!-- /.box-body -->
        </div><!-- /. box -->
    </div><!-- /.col -->
 </div><!-- /.row -->

  <script type="text/javascript" src="<?php echo base_url('assets/fullcalendar/fullcalendar.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/fullcalendar/lang-all.js'); ?>"></script>


  <style>
    .fc-event{
        font-size: 14px;
    }
    .fc-event:hover{
        font-size: 16px;
        cursor:pointer;
    }

    #custom-header {
        margin-left: 13%;
        width: 78%;
        position: absolute;
        top: 15px;
        color: #5fa7d8;
        column-count: 3;
        font-family: Helvetica;
        font-weight: bolder;
    }

      #center-title {
          padding-left: 10px;
          padding-right: 10px;
          margin-top: 0;
          font-size: 1.5vw;
      }

      #cus-button-today {
          width: 60%;
          margin-left: 20%;
          margin-right: 20%;
      }

      #header-left p {
          font-size: 1.5vw;
      }

      #header-right div {
          width: 100%;
          margin-left: 60%;
      }




  </style>


  <script type="text/javascript">




    $(function() {
        var date = new Date();
        var d = date.getDate(),
            m = date.getMonth(),
            y = date.getFullYear();
        $('#calendar').fullCalendar({
            timezone: 'local',
            timeFormat: 'H:mm',
            lang: 'en',
            weekMode: 'liquid',
            header: {
                left: '',
                center: '',
                right: 'month,agendaWeek,agendaDay'
            },
            eventLimit: true,
    		events: [
                <?php
                    $var = array();
                    foreach ($routines as $routine) {
                        $subject_teacher_details = $this->subject_teacher_details_m->get_by_subjectID($routine->subjectID);
                        $teacherNames = ' ';
                        foreach($subject_teacher_details as $item) {
                            $teacher = $this->teacher_m->get_teacher($item->teacherID);
                            if($teacher){
                                $teacherNames = $teacherNames.' | '.$teacher->name;
                            }else{
                                $teacherNames = $teacherNames.' | '.$item->name;
                            }
                        }

                        if (array_key_exists($routine->date, $var)) {
                            $var[$routine->date] ++;
                        }else  {
                            $var[$routine->date] = 1;
                        }


//                        echo '{';
//                            echo "id: '".$routine->routineID."', ";
//                            echo "title: '有".$var[$routine->date]."门课程', ";
//                            echo "start: '".$routine->date."T".$routine->start_time."', ";
//                            echo "end: '".$routine->date."T".$routine->end_time."', ";
//                            echo "textColor:'#FF00FF',";
//                            echo "color: '".$routine->color."', ";
//                        echo '},';
                    }

                    foreach ($var as $key => $value) {
                        echo '{';
                        echo "title: '有".$value."门课程', ";
                        echo "start: '".$key."', ";
                        echo "color: 'transparent', ";
                        echo '},';
                    }

                ?>
            ],
            eventMouseover: function (calEvent, jsEvent, view) {
          	},

            eventClick:  function(event, jsEvent, view) {
              $.ajax({
      	       //  url: 'ajax.php',
      	       //  data: { var_PHP_data : event.id },
                 url: "<?=base_url('dashboard/getRoutine')?>",
                 data: 'id='+ event.id ,
      	         type: "POST"
      	       }).done(function(data) {
                     console.log(data);
        	     $('#subject-name').text(data.subject);

        	     $('#subject-date').text(data.date);
        	     $('#subject-time').text(data.start_time + "-" + data.end_time);
        	     $('#subject-room').text(data.room);
                 $('#subject-teachers').text(data.teachers);
      	         $('#exampleModal').modal();
      	      });
            }
        });
    });


    //      ------------------------------------------------------------

//    var titleleft = document.getElementById("title-left");
//    var calendar = $('#calendar').fullCalendar('getCalendar');
//    console.log(m);



    $(document).ready( function(){

        $('.fc-toolbar').css('marginButtom', '0');
        $('.fc-right').hide();
        var agendaWeed = function(){
            $('.fc-month-button').click();
        };

        var momentRefresh = function () {
            var moment = $('#calendar').fullCalendar('getDate');
            var dateViewStr = moment.format("Y")+"年"+moment.format("M")+"月";
            $('#center-title').html(dateViewStr);
        };
        setInterval(momentRefresh, 100);

        $('#cus-button-prev').click(function() {
            $('#calendar').fullCalendar('prev');
        });
        $('#cus-button-next').click(function() {
            $('#calendar').fullCalendar('next');
        });
        $('#cus-button-today').click(function() {
            $('#calendar').fullCalendar('today');
        });



    });



    //      ------------------------------------------------------------


  </script>


<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel"><i class="glyphicon glyphicon-info-sign"></i> 课程详细</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal">
			<div class="form-group">
				<label class="col-sm-2 control-label" for="subject-name">课程名:</label>
				<div class="col-sm-10">
					<label class="form-control" for=""><span id ="subject-name"></span></label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="subject-date">日期:</label>
				<div class="col-sm-10">
				    <label class="form-control" for=""><span id ="subject-date"></span></label>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label" for="subject-time">时间段:</label>
				<div class="col-sm-10">
				    <label class="form-control" for=""><span id ="subject-time"></span></label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="subject-room">教室:</label>
				<div class="col-sm-10">
					<label class="form-control" for=""><span id ="subject-room"></span></label>
				</div>
			</div>
            <div class="form-group">
				<label class="col-sm-2 control-label" for="subject-room">教师:</label>
				<div class="col-sm-10">
					<label class="form-control" for=""><span id ="subject-teachers"></span></label>
				</div>
			</div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning " data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> 关闭</button>
      </div>
    </div>
  </div>
</div>


<?php } ?>
