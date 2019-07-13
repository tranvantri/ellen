@extends('admin.layout.index')
@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
          <div class="chart-bot">
               <div id="piechart"></div>
               <input type="hidden" id="from_db" value="{{$db}}">
               <input type="hidden" id="from_dialog" value="{{$dialog}}">
               <input type="hidden" id="from_none" value="{{$none}}">
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