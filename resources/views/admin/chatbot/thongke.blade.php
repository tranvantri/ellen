@extends('admin.layout.index')
@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
          <div class="chart-bot col-md-12 col-12 col-lg-12">
               <div id="piechart"></div>
               <input type="hidden" id="from_db" value="{{$db->count()}}">
               <input type="hidden" id="from_dialog" value="{{$dialog->count()}}">
               <input type="hidden" id="from_none" value="{{$none->count()}}">
          </div>
            <hr/>
          <div class="col-md-12">
              {{--  Show report  --}}
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">From Database</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">From Dialog</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Unknow</a>
                </li>
              </ul>


              <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <table class="table table-bordered">
                                <thead>
                                  <tr>
                                    <th>Bộ Hỏi</th>
                                    <th>Bot trả lời</th>
                                  </tr>
                                </thead>
                                <tbody>
                                    @foreach($db as $child)
                                    <tr>
                                        <td>{{$child->user_ask}}</td>
                                        <td>{{$child->bot_reply}}</td>
                                    </tr>
                                  @endforeach
                                </tbody>
                        </table>
                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <table class="table table-bordered">
                                <thead>
                                  <tr>
                                    <th>Bộ Hỏi</th>
                                    <th>Bot trả lời</th>
                                    <th>Intent dialog</th>
                                  </tr>
                                </thead>
                                <tbody>
                                    @foreach($dialog as $child)
                                    <tr>
                                        <td>{{$child->user_ask}}</td>
                                        <td>{{$child->bot_reply}}</td>
                                        <td>{{$child->intent_dialog_flow}}</td>
                                    </tr>
                                  @endforeach
                                </tbody>
                        </table>
                </div>
                <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                        <table class="table table-bordered">
                                <thead>
                                  <tr>
                                    <th>Bộ Hỏi</th>
                                    {{--  <th>Bot trả lời</th>
                                    <th>Sửa</th>  --}}
                                  </tr>
                                </thead>
                                <tbody>
                                    @foreach($none as $child)
                                    <tr>
                                        <td>{{$child->user_ask}}</td>
                                        {{--  <td>{{$child->bot_reply}}</td>
                                        <td>john@example.com</td>  --}}
                                    </tr>
                                  @endforeach
                                </tbody>
                        </table>
                </div>
              </div>
              


          </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
     /** Chart from Google
 * Link:
 * https://www.w3schools.com/howto/howto_google_charts.asp
 * 
 */          
     google.charts.load('current', {'packages':['corechart']});
     google.charts.setOnLoadCallback(drawChart);

     // Draw the chart and set the chart values
     function drawChart() {
          var database = document.getElementById("from_db").value;
          var dialog = document.getElementById("from_dialog").value;
          var none = document.getElementById("from_none").value;

     var data = google.visualization.arrayToDataTable([
     ['Task', 'Hours per Day'],
     ['From Dialog Flow', parseInt(dialog)],
     ['From Database',parseInt(database)],
     ['Not Set Value',parseInt(none)]
     ]);

     // Optional; add a title and set the width and height of the chart
     var options = {'title':'Botman report', 'width':550, 'height':400};

     // Display the chart inside the <div> element with id="piechart"
     var chart = new google.visualization.PieChart(document.getElementById('piechart'));
     chart.draw(data, options);
     }



</script>
@endsection