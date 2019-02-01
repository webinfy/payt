<div class="main-content-section">
    <ul class="dashboard-menu">
        <li><a href="<?= HTTP_ROOT . "merchants/"; ?>">Overall</a></li>
        <li><a class="active" href="<?= HTTP_ROOT . "merchants/payment-success-ratio"; ?>">Payment Success Ratio</a></li>
        <li><a href="<?= HTTP_ROOT . "merchants/modes-of-payment"; ?>">Mode of Payments</a></li>
        <li><a href="<?= HTTP_ROOT . "merchants/date-wise-status"; ?>">Date Wise Payment Status</a></li>
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
        <h1 style="text-align: center;">Payment Success Ratio</h1>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <ul class="chart-box">
                        <?php foreach ($donutchart as $key => $value) {?>
                            <li class="options">
                                <input class="input" type="checkbox" name="<?= $value['section'] ?>" value="<?= $value['total'] ?>" checked>
                                <label class="name"><?= $value['section'] ?></label>
                                <span class="value">(<?= $value['total'] ?>)</span>
                            </li>  
                        <?php } ?>
                    </ul>
                </div>
            </div>   
            <div class="row">
                <div class="col-md-12 text-center">
                    <div id="donutchart" class="charts"></div>
                </div>
            </div>
        </div>
        <script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
	    <script src="https://www.amcharts.com/lib/3/pie.js"></script>
	    <script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
        <script src="js/charts.js"></script>
        <script>
            var chart = AmCharts.makeChart("donutchart", {
                "type": "pie",
                "theme": "light",
                "dataProvider": <?php echo json_encode($donutchart); ?>,
                "valueField": "total",
                "titleField": "section",
                "innerRadius": "40%",
                "balloon": {
                    "fixedPosition": false
                }
            });
            $(".options .input").click(function(event) {
                chart.dataProvider = [];
                $(".options .input").each(function(key,value)
                {
                    if ($(value).is(":checked"))
                    {
                        chart.dataProvider.push({
                            section: $(value).attr('name'),
                            total: $(value).val()
                           });
                    }
                })
                chart.validateData();
            })
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
    .chart-box
    {
        display: inline-block;
        padding: 10px;
        position: absolute;
        top:0px;
        left: 0px;
        z-index: 9999999;
    }
    ul.chart-box .options
    {
        padding: 5px;
    }
    </style>