<?php header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate'); ?>
<?php header('content-Type: text/html; charset=UTF-8'); ?>
<?php @ require_once 'database/database.php'; ?>
<?php @ require_once 'function/checkCookie.php'; ?>
<?php @ require_once 'function/functions.php'; ?>
<?php
$userfactorystr = $_COOKIE['cuserfactory'];
$userfactoryarr=explode("','",$userfactorystr);
$datearr = datearr($startdate, $enddate); //时间数组
if ($_COOKIE['cdepartment'] == 'works') {
    ?>
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <meta http-equiv="pragma" content="no-cache"/>
            <meta http-equiv="Cache-Control" content="no-cache, no-store"/>
            <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
            <title>Device Maintian Summary</title>
            <link rel="SHORTCUT ICON" href="images/title.png"/>
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <link rel="stylesheet" href="css/bootstrap-select.css">
            <link rel="stylesheet" href="css/jquery.dataTables.css">
            <link rel="stylesheet" href="css/fixedColumns.dataTables.css">
            <script src="js/jquery-2.0.3.min.js"></script>
            <script type="text/javascript" src="My97DatePicker/WdatePicker.js"></script>
            <script language="javascript" src="layer/layer.min.js"></script>
            <script src="js/jquery.dataTables.min.js"></script>
            <script src="js/jquery.dataTables.bootstrap.js"></script>
            <script src="js/dataTables.fixedColumns.js"></script>
            <script src="js/bootstrap.select.js"></script>
            <script src="js/getDevice.js"></script>
            <script src="js/hightcharts/highcharts.js"></script>
            <script src="js/hightcharts/modules/series-label.js"></script>
            <script src="js/hightcharts/modules/exporting.js"></script>
            <script>
                        $(document).ready(function () {
                $('#table').DataTable({
                "paging": false,
                        "ordering": false,
                        "info": false,
                        "searching": false,
                        "scrollX": true,
                        fixedColumns: {//关键是这里了，需要第一列不滚动就设置1
                        leftColumns: 3
                        },
                });
                        $("#deviceDataChart").addClass("active");
                        $("#deviceData").addClass("active open");
                        $("#pilot").addClass("active open");
                        $("#factory option[value='<?= $factory ?>']").attr("selected", true);
    <?php
    $str = "SELECT aa.SYSTEM FROM device_information_works aa
LEFT JOIN card_mapping bb ON bb.RFCARD=aa.RFCARD
LEFT JOIN position_works cc ON cc.DVSCARD=bb.DVSCARD
WHERE aa.FLAG !='1' AND bb.FLAG !='1' AND cc.FLAG !='1' AND aa.DATA_METHOD in ('1','2') AND cc.FACTORY='" . $factory . "' GROUP BY aa.SYSTEM";
    $ssystem = $pdo->query($str);
    $systemarr = $ssystem->fetchAll();
    foreach ($systemarr as $value) {
        echo "$('#system').append('<option value=\'" . $value['SYSTEM'] . "\'>" . getNamech($pdo, $value['SYSTEM']) . "</opption>');";
    }
    ?>
                $("#system option[value='<?= $system ?>']").attr("selected", true);
    <?php
    $str = "SELECT aa.TYPE FROM device_information_works aa
LEFT JOIN card_mapping bb ON bb.RFCARD=aa.RFCARD
LEFT JOIN position_works cc ON cc.DVSCARD=bb.DVSCARD
WHERE aa.FLAG !='1' AND bb.FLAG !='1' AND cc.FLAG !='1' AND aa.DATA_METHOD in ('1','2') AND cc.FACTORY='" . $factory . "' AND aa.SYSTEM='" . $system . "' GROUP BY aa.TYPE";
    $stype = $pdo->query($str);
    $stypearr = $stype->fetchAll();
    foreach ($stypearr as $value) {
        echo "$('#type').append('<option value=\'" . $value['TYPE'] . "\'>" . getNamech($pdo, $value['TYPE']) . "</opption>');";
    }
    ?>
                $("#type option[value='<?= $type ?>']").attr("selected", true);
    <?php
    $str = "SELECT aa.DEVICE_NAME FROM device_information_works aa
LEFT JOIN card_mapping bb ON bb.RFCARD=aa.RFCARD
LEFT JOIN position_works cc ON cc.DVSCARD=bb.DVSCARD
WHERE aa.FLAG !='1' AND bb.FLAG !='1' AND cc.FLAG !='1' AND aa.DATA_METHOD in ('1','2') AND cc.FACTORY='" . $factory . "' AND aa.SYSTEM='" . $system . "' AND aa.TYPE='" . $type . "' GROUP BY aa.DEVICE_NAME";
    $sdevice_name = $pdo->query($str);
    $sdnarr = $sdevice_name->fetchAll();
    foreach ($sdnarr as $value) {
        echo "$('#device_name').append('<option value=\'" . $value['DEVICE_NAME'] . "\'>" . $value['DEVICE_NAME'] . "</opption>');";
    }
    ?>
                $("#device_name option[value='<?= $device_name ?>']").attr("selected", true);
    <?php
    $str = "SELECT aa.DEVICE_SN FROM device_information_works aa
LEFT JOIN card_mapping bb ON bb.RFCARD=aa.RFCARD
LEFT JOIN position_works cc ON cc.DVSCARD=bb.DVSCARD
WHERE aa.FLAG !='1' AND bb.FLAG !='1' AND cc.FLAG !='1' AND aa.DATA_METHOD in ('1','2') AND cc.FACTORY='" . $factory . "' AND aa.SYSTEM='" . $system . "' AND aa.TYPE='" . $type . "' AND aa.DEVICE_NAME='" . $device_name . "' GROUP BY aa.DEVICE_SN";
    $sdevice_sn = $pdo->query($str);
    $sdsarr = $sdevice_sn->fetchAll();
    foreach ($sdsarr as $value) {
        echo "$('#device_sn').append('<option value=\'" . $value['DEVICE_SN'] . "\'>" . $value['DEVICE_SN'] . "</opption>');";
    }
    ?>
                $("#device_sn option[value='<?= $device_sn ?>']").attr("selected", true);
                })

                        function check() {
                        if ($("#startdate").val() == '') {
                        alert("請選擇開始日期！");
                                $("#startdate").focus();
                                return false;
                        }
                        if ($("#enddate").val() == '') {
                        alert("請選擇結束日期！");
                                $("#enddate").focus();
                                return false;
                        }
                        var startTime = $("#startdate").val();
                                var start = new Date(startTime.replace("-", "/").replace("-", "/"));
                                var endtime = $("#enddate").val();
                                var end = new Date(endtime.replace("-", "/").replace("-", "/"));
                                if (end < start) {
                        alert("開始時間不能大於結束時間！");
                                $("#enddate").val('');
                                $("#enddate").focus();
                                return false;
                        }
                        var aa = 1056800000;
                                if (end - start > aa) {
                        alert("時間範圍不能超過两周！");
                                $("#endtime").val('');
                                $("#endtime").focus();
                                return false;
                        }
                        if ($("#factory").val() == '') {
                        alert("請選擇廠別！");
                                $("#factory").focus();
                                return false;
                        }
                        if ($("#system").val() == '') {
                        alert("請選擇系統類別！");
                                $("#system").focus();
                                return false;
                        }
                        if ($("#type").val() == '') {
                        alert("請選擇設備類別！");
                                $("#type").focus();
                                return false;
                        }
                        if ($("#device_name").val() == '') {
                        alert("請選擇設備名稱！");
                                $("#device_name").focus();
                                return false;
                        }
                        if ($("#device_sn").val() == '') {
                        alert("請選擇設備SN！");
                                $("#device_sn").focus();
                                return false;
                        }
                        }
            </script>
        </head>
        <body style="overflow: hidden">
            <?php @ require_once 'navbar.php'; ?>
            <div class="main-container" id="main-container">
                <div class="main-container-inner">
                    <a class="menu-toggler" id="menu-toggler" href="#">
                        <span class="menu-text"></span>
                    </a>
                    <?php @ require_once 'sidebar.php'; ?>
                    <div class="main-content" style="overflow-y: scroll;height:600px;">
                        <div class="page-content">
                            <div class="page-header">
                                <h1>
                                    <i class="icon-search"></i> 點檢
                                    <small>
                                        <i class="icon-double-angle-right"></i>
                                        設備抄表
                                    </small>
                                    <small>
                                        <i class="icon-double-angle-right"></i>
                                        數據圖
                                    </small>
                                </h1>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="table-responsive">
                                                <form method="post" action="deviceDataChart.php">
                                                    <div class="form-inline">
                                                        <label>開始日期：</label>
                                                        <div class="form-group" style="width:180px;margin-right: 20px;">
                                                            <input type="text" class="Wdate form-control" onFocus="WdatePicker({dateFmt: 'yyyy-MM-dd', maxDate: '%y-%M-{%d-1}'})" id="startdate" name="startdate" value="<?= $startdate ?>" autocomplete="off">
                                                        </div>
                                                        <label>結束日期：</label>
                                                        <div class="form-group" style="width:180px;margin-right: 20px;">  
                                                            <input type="text" class="Wdate form-control" onFocus="WdatePicker({dateFmt: 'yyyy-MM-dd', maxDate: '%y-%M-{%d-1}'})" id="enddate" name="enddate" value="<?= $enddate ?>" autocomplete="off">
                                                        </div>
                                                        <label>廠別：</label>
                                                        <div class="form-group" style="width:180px;margin-left:28px ;margin-right: 20px;">
                                                            <select id="factory" class="form-control" name="factory" onchange="changeSystem(this.value);changeType($('#system').val()); changeDevice_name($('#type').val()); changeDevice_SN($('#device_name').val());">
                                                                <option></option>
                                                                <?php 
                                    foreach ($userfactoryarr as $key => $value) {
                                        echo "<option value='$value'>$value</option>";  
                                    }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <label>系統類別：</label>
                                                        <div class="form-group" style="width:180px;margin-right: 20px;">
                                                            <select id="system" class="form-control " name="system" onchange="changeType(this.value); changeDevice_name($('#type').val()); changeDevice_SN($('#device_name').val());">
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-inline" style="margin-top: 10px;">
                                                        <label>設備類別：</label>
                                                        <div class="form-group" style="width:180px;margin-right: 20px;">
                                                            <select id="type" class="form-control" name="type" onchange="changeDevice_name(this.value); changeDevice_SN($('#device_name').val());">
                                                            </select>
                                                        </div>
                                                        <label>設備名稱：</label>
                                                        <div class="form-group" style="width:180px;margin-right: 20px;">
                                                            <select id="device_name" class="form-control" name="device_name" onchange="changeDevice_SN(this.value);">
                                                            </select>
                                                        </div>
                                                        <label>設備SN：</label>
                                                        <div class="form-group" style="width:180px;margin-left: 8px;margin-right: 20px;">
                                                            <select id="device_sn" class="form-control" name="device_sn">
                                                            </select>
                                                        </div>
                                                        <button class="btn btn-primary btn-sm" type="submit" onclick="return check()"><i class="icon-search"></i>查詢</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="table-responsive" style="margin-top:10px;">
                                        <table id="table" class=" nowrap table table-striped table-bordered table-hover" cellspacing="0" style="word-break: keep-all;white-space:nowrap;width:100%;text-align: center">
                                            <thead style="background:#f0f0f0">
                                                <tr>
                                                    <?php
                                                    $str = "SELECT * FROM device_information_works aa
                                                                LEFT JOIN type_mapping_works bb ON bb.TYPE=aa.TYPE
                                                                WHERE FLAG !='1' AND DEVICE_SN='" . $device_sn . "'";
                                                    $ty = $pdo->query($str);
                                                    $tyarr = $ty->fetch();
                                                    $nu = $tyarr[$tyarr['MAPPING']] . $tyarr['UNIT'];
                                                    ?>
                                                    <td rowspan="2" style="line-height: 25px"><?= $device_sn . "</br>(" . $nu ?>)</td>
                                                    <td rowspan="2" style="line-height: 50px">標準值</td>
                                                    <td rowspan="2" style="line-height: 50px">單位</td>
                                                    <?php
                                                    for ($i = 0; $i < count($datearr); $i++) {
                                                        echo "<td colspan='4'>" . $datearr[$i] . "</td>";
                                                    }
                                                    ?>
                                                </tr>
                                                <tr>
                                                    <?php
                                                    for ($i = 0; $i < count($datearr); $i++) {
                                                        echo "<td>08:00~14:00</td>";
                                                        echo "<td>14:00~20:00</td>";
                                                        echo "<td>20:00~02:00</td>";
                                                        echo "<td>02:00~08:00</td>";
                                                    }
                                                    ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $str = "SELECT dd.CONTENT,dd.GREAT,dd.TARGET,dd.LESS,dd.UNIT FROM
                                                            (SELECT bb.CONTENT,IF(cc.DEVICE_SFIS IS NULL OR cc.DEVICE_SFIS='','0','1') AS FLAG,bb.GREAT,bb.TARGET,bb.LESS,bb.UNIT FROM device_information_works aa 
                                                                LEFT JOIN data_mapping_works bb ON bb.DEVICE_NAME=aa.DEVICE_NAME
                                                                LEFT JOIN device_mapping_works cc ON cc.DEVICE_SN=aa.DEVICE_SN AND cc.CONTENT=bb.CONTENT
                                                                WHERE aa.FLAG !='1' AND aa.DEVICE_SN='" . $device_sn . "')dd WHERE dd.FLAG='0'";
                                                $content = $pdo->query($str);
                                                $conarr = $content->fetchAll();
                                                foreach ($conarr as $value) {
                                                    echo "<tr>";
                                                    echo "<td>" . $value['CONTENT'] . "</td>";
                                                    if ($value['TARGET'] == 'NA') {
                                                        $target = 'NA';
                                                    } else if ($value['TARGET'] == '~') {
                                                        $target = $value['GREAT'] . "~" . $value['LESS'];
                                                    } else {
                                                        if ($value['GREAT'] == 'NA') {
                                                            $target = $value['LESS'] . $value['TARGET'];
                                                        } else {
                                                            $target = $value['GREAT'] . $value['TARGET'];
                                                        }
                                                    }
                                                    echo "<td>" . $target . "</td>";
                                                    echo "<td>" . $value['UNIT'] . "</td>";
                                                    for ($i = 0; $i < count($datearr); $i++) {
                                                        $str = "SELECT bb.`DATA`,bb.FLAG FROM data_detail_works bb
                                                                LEFT JOIN (SELECT max(IDX) AS IDX FROM data_detail_works WHERE DEVICE_SN='" . $device_sn . "' AND CONTENT='" . $value['CONTENT'] . "' AND DATE='" . $datearr[$i] . "' GROUP BY FLAG)aa
                                                                ON aa.IDX=bb.IDX WHERE aa.IDX IS NOT NULL";
                                                        $data = $pdo->query($str);
                                                        $darr = $data->fetchAll();
                                                        $data0 = '';
                                                        $data1 = '';
                                                        $data2 = '';
                                                        $data3 = '';
                                                        foreach ($darr as $row) {
                                                            if ($row['FLAG'] == '0') {
                                                                $data0 = $row['DATA'];
                                                            }
                                                            if ($row['FLAG'] == '1') {
                                                                $data1 = $row['DATA'];
                                                            }
                                                            if ($row['FLAG'] == '2') {
                                                                $data2 = $row['DATA'];
                                                            }
                                                            if ($row['FLAG'] == '3') {
                                                                $data3 = $row['DATA'];
                                                            }
                                                        }
                                                        echo "<td>" . $data0 . "</td>";
                                                        echo "<td>" . $data1 . "</td>";
                                                        echo "<td>" . $data2 . "</td>";
                                                        echo "<td>" . $data3 . "</td>";
                                                    }
                                                    echo "</tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="table-responsive" style="margin-top:10px;width:100%;">
                                        <?php
                                        $c = 0;
                                        foreach ($conarr as $value) {
                                            if ($value['UNIT'] == 'NA') {
                                                $unit = '';
                                            } else {
                                                $unit = $value['UNIT'];
                                            }
                                            ?>
                                            <div id = "content<?= $c ?>" style = "margin-top: 30px;width: 100%;border:#f1eded 1px solid;height:500px"></div>
                                            <script type="text/javascript">
                                                        Highcharts.chart('content<?= $c ?>', {
                                                        title: {
                                                        text: '<?= $value['CONTENT'] . $unit ?>'
                                                        },
                                                                xAxis: {
                                                                categories: [
        <?php
        for ($i = 0; $i < count($datearr); $i++) {
            ?>
                                                                    '<?= substr($datearr[$i],5,5) ?> 08:00~<?= substr($datearr[$i],5,5) ?> 14:00', '<?= substr($datearr[$i],5,5) ?> 14:00~<?= substr($datearr[$i],5,5) ?> 20:00', '<?= substr($datearr[$i],5,5) ?> 20:00~<?= substr(date('Y-m-d', strtotime($datearr[$i] . "+1 day")),5,5) ?> 02:00', '<?= substr(date('Y-m-d', strtotime($datearr[$i] . "+1 day")),5,5) ?> 02:00~<?= substr(date('Y-m-d', strtotime($datearr[$i] . "+1 day")),5,5) ?> 08:00',
            <?php
        }
        ?>
                                                                ],
                                                                        labels : {

                                                                        rotation: 70

                                                                        }

                                                                },
                                                                yAxis: {
                                                                title: {
                                                                text: ''
                                                                }
                                                                },
                                                                legend: {
                                                                layout: 'vertical',
                                                                        align: 'right',
                                                                        verticalAlign: 'middle'
                                                                },
                                                                plotOptions: {
                                                                line: {
                                                                dataLabels: {
                                                                enabled: true
                                                                },
                                                                        enableMouseTracking: false
                                                                },
                                                                        xAxis: {
                                                                        labels: {
                                                                        style: {
                                                                        color: 'fff',
                                                                                fontSize: '13px',
                                                                        }
                                                                        },
                                                                                crosshair: false
                                                                        },
                                                                },
                                                                series: [
                                                                {
                                                                name: '<?= $value['CONTENT'] ?>', data: [
        <?php
        for ($i = 0; $i < count($datearr); $i++) {
            $str = "SELECT bb.`DATA`,bb.FLAG FROM data_detail_works bb
LEFT JOIN (SELECT max(IDX) AS IDX FROM data_detail_works WHERE DEVICE_SN='" . $device_sn . "' AND CONTENT='" . $value['CONTENT'] . "' AND DATE='" . $datearr[$i] . "' GROUP BY FLAG)aa
ON aa.IDX=bb.IDX WHERE aa.IDX IS NOT NULL";
            $data = $pdo->query($str);
            $darr = $data->fetchAll();
            $data0 = "''";
            $data1 = "''";
            $data2 = "''";
            $data3 = "''";
            foreach ($darr as $row) {
                if ($row['FLAG'] == '0') {
                    $data0 = $row['DATA'];
                }
                if ($row['FLAG'] == '1') {
                    $data1 = $row['DATA'];
                }
                if ($row['FLAG'] == '2') {
                    $data2 = $row['DATA'];
                }
                if ($row['FLAG'] == '3') {
                    $data3 = $row['DATA'];
                }
            }
//            
            ?>
            <?= $data0 ?>,<?= $data1 ?>,<?= $data2 ?>,<?= $data3 ?>,
            <?php
        }
        ?>
                                                                ]},
        <?php
        if ($value['TARGET'] == '~') {
            ?>
                                                                    {name:'標準值:><?= $value['GREAT'] ?>', data:[
            <?php
            for ($i = 0; $i < count($datearr); $i++) {
                ?>
                <?= $value['GREAT'] ?>,<?= $value['GREAT'] ?>,<?= $value['GREAT'] ?>,<?= $value['GREAT'] ?>,
                <?php
            }
            ?>
                                                                    ], color:'green'}, {name:'標準值:<<?= $value['LESS'] ?>', data:[
            <?php
            for ($i = 0; $i < count($datearr); $i++) {
                ?>
                <?= $value['LESS'] ?>,<?= $value['LESS'] ?>,<?= $value['LESS'] ?>,<?= $value['LESS'] ?>,
                <?php
            }
            ?>
                                                                    ], color:'green'},
            <?php
        } else if ($value['TARGET'] == 'NA') {
            
        } else {
            if ($value['GREAT'] == 'NA') {
                $jud = '<';
            } else {
                $jud = '>';
            }
            ?>
                                                                    {name:'標準值:<?= $jud . $value['TARGET'] ?>', data:[
            <?php
            for ($i = 0; $i < count($datearr); $i++) {
                ?>
                <?= $value['TARGET'] ?>,<?= $value['TARGET'] ?>,<?= $value['TARGET'] ?>,<?= $value['TARGET'] ?>,
                <?php
            }
            ?>
                                                                    ], color:'green'}
            <?php
        }
        ?>
                                                                ],
                                                                responsive: {
                                                                rules: [{
                                                                condition: {
                                                                maxWidth: 500
                                                                },
                                                                        chartOptions: {
                                                                        legend: {
                                                                        layout: 'horizontal',
                                                                                align: 'center',
                                                                                verticalAlign: 'bottom'
                                                                        }
                                                                        }
                                                                }]
                                                                },
                                                                credits: {enabled: false},
                                                                exporting: {enabled: false},
                                                        });
                                            </script>
                                            <?php
                                            $c++;
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </body>
    </html>
    <?php
}
?>