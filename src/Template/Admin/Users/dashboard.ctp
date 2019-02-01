<div class="main-content-section">
    <ul class="dashboard-menu">
        <li><a class="active" href="<?= HTTP_ROOT . "admin/"; ?>">Overall</a></li>
        <li><a href="<?= HTTP_ROOT . "admin/payment-success-ratio"; ?>">Payment Success Ratio</a></li>
        <li><a href="<?= HTTP_ROOT . "admin/modes-of-payment"; ?>">Mode of Payments</a></li>
        <li><a href="<?= HTTP_ROOT . "admin/date-wise-status"; ?>">Date Wise Payment Status</a></li>
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
        <h1 style="text-align: center;">Overall</h1>
        <div class="container">
            <div class="row">
                <ul>
                <?php foreach ($piechart as $value) {
                    echo "<li><span style='display:inline-block;height: 15px;width: 20px;background-color:".$value['color'].";'></span><span>".ucfirst($value['section'])." (".$value['total'].")</span></li>";
                }?>
                </ul>
            </div>
        </div>    
        <div class="row">
        	<div class="col-md-12 text-center">
        		<div id="piechart" class="charts"></div>
        	</div>
        </div>

        <script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
	    <script src="https://www.amcharts.com/lib/3/pie.js"></script>
	    <script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
        <script src="js/charts.js"></script>
        <script>
	    	var chart = AmCharts.makeChart("piechart", {
		        "type": "pie",
		        "theme": "light",
		        "dataProvider": <?= json_encode($piechart); ?>,
		        "valueField": "total",
		        "titleField": "section",
                "colorField": "color",
		        "balloon": {
		            "fixedPosition": true
		        }
		    });
		</script>
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
</style>