<div class="main-content-section">
  <ul class="dashboard-menu">
    <li><a href="<?= HTTP_ROOT . "admin/"; ?>">Overall</a></li>
    <li><a href="<?= HTTP_ROOT . "admin/payment-success-ratio"; ?>">Payment Success Ratio</a></li>
    <li><a href="<?= HTTP_ROOT . "admin/modes-of-payment"; ?>">Mode of Payments</a></li>
    <li><a class="active" href="<?= HTTP_ROOT . "admin/date-wise-status"; ?>">Date Wise Payment Status</a></li>
  </ul>
  <div class="dashboard-menu search-item-div">
    <?= $this->Form->create(NULL, ['type' => 'get']) ?>
    <div class="container">
      <div class="row">
        <div class="col-md-5">
          <div class="row">
            <div class="col-sm-5">
              <label for="start_date" style="padding-top: 7px;">Select Start Date</label>
            </div>
            <div class="col-sm-7">
              <input type="date" id="start_date" class="form-control" placeholder="Select From Date" name="from" value="<?= @$from;?>">
            </div>
          </div>
        </div>
        <div class="col-md-5">
          <div class="row">
            <div class="col-sm-5">
              <label for="end_date" style="padding-top: 7px;">Select End Date</label>
            </div>
            <div class="col-sm-7">
              <input type="date" id="end_date" class="form-control" placeholder="Select To Date" name="to" value="<?= @$to;?>">
            </div>
          </div>
        </div>
        <div class="col-md-2">
          <input type="submit" name="view" value="view" class="btn form-control btn-blue">
        </div>
      </div>
    </div>
    <?= $this->Form->end() ?>
  </div>
  <div class="main-content" style="min-height: 400px;">
    <h1 style="text-align: center;">Date Wise Payment Status</h1>
    <ul class="periods">
      <li class="period">Day</li>
      <li class="period">Week</li>
      <li class="period active">Month</li>
    </ul>
    <div class="row">
      <div class="col-md-12 text-center">
        <div id="barchart" class="charts"></div>
      </div>
    </div>
    <script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
    <script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
    <script src="https://www.amcharts.com/lib/3/serial.js"></script>
    <div id="response"></div>
  </div>
</div>
<style>
.charts {
width: 100%;
height: 500px;
margin: auto;
}
.charts .amcharts-main-div .amcharts-chart-div a {
display: none !important;
}
.periods {
text-align: right;
padding: 20px;
}
.periods li {
cursor: pointer;
display: inline-block;
box-sizing: border-box;
width: 55px;
text-align: center;
padding: 4px 8px;
color: white;
font-size: 12px;
background-color: #777;
border-radius: 0.25rem;
}
.periods li.active {
font-size: 12px;
font-weight: bold;
background-color: #aaa;
}
</style>
<script src="js/charts.js"></script>
<script>
   var chart = AmCharts.makeChart("barchart", {
      "type": "serial",
      "theme": "light",
      "marginBottom": 25,
      "marginRight": 50,
      "valueAxes": [{
      "axisAlpha": 0.2,
      "dashLength": 1,
      "position": "left"
      }],
      "mouseWheelZoomEnabled": true,
      "graphs": [{
      "id": "g1",
      "balloonText": "[[value]]",
      "bullet": "round",
      "bulletBorderAlpha": 1,
      "bulletColor": "rgba(255,255,255,0.5)",
      "lineColor": "red",
      "hideBulletsCount": 50,
      "title": "red line",
      "valueField": "unpaid",
      "useLineColorForBulletBorder": true,
      "balloon": {
      "drop": false
      }
      },
      {
        "id": "g2",
      "balloonText": "[[value]]",
      "bullet": "round",
      "bulletBorderAlpha": 1,
      "bulletColor": "rgba(255,255,255,0.5)",
      "lineColor": "green",
      "hideBulletsCount": 50,
      "title": "green line",
      "valueField": "paid",
      "useLineColorForBulletBorder": true,
      "balloon": {
      "drop": true
      }
      }],
      "chartScrollbar": {
      "autoGridCount": true,
      "graph": "g1",
      "scrollbarHeight": 40
      },
      "chartCursor": {
      "categoryBalloonDateFormat": "MMM-YYYY",
      },
      "categoryField": "date",
      "categoryAxis": {
          "parseDates": true,
          "axisColor": "#DADADA",
          "dashLength": 1,
          "minorGridEnabled": true
        },
    });
  $(".period").click(function(event){
    var chartPeriod = $(this).text();
    $(this).addClass('active');
    $(this).siblings().removeClass('active');
    var ajaxdata = $.ajax({
      url: './admin/barchart',
      type: 'GET',
      data: {
        from: "<?= (isset($_GET['from']))? $_GET['from'] : 0 ?>",
        to: "<?= (isset($_GET['to']))? $_GET['to'] : 0 ?>",
        period: $(this).text()
      },
      success: function(data){
        data = $.parseJSON(data);
        chart.dataProvider = data;
        if (chartPeriod == "Day")
        {
          console.log("1");
        }
        else-if()
        {}
        chart.validateData();
      }
    });
  });
  var ajaxdata = $.ajax({
  url: './admin/barchart',
  type: 'GET',
  data: {
  from: "<?= (isset($_GET['from']))? $_GET['from'] : 0 ?>",
  to: "<?= (isset($_GET['to']))? $_GET['to'] : 0 ?>",
  period: 'Month'
  },
  success: function(data){
  data = $.parseJSON(data);
  chart.dataProvider = data;
  chart.validateData();
  }
  });
</script>