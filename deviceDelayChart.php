<?php header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate'); ?>
<?php header('content-Type: text/html; charset=UTF-8'); ?>
<?php @ require_once 'database/database.php'; ?>
<?php @ require_once 'function/checkCookie.php'; ?>
<?php @ require_once 'function/functions.php'; ?>
<?php
$factory = isset($factorystr) == '1' ? '' : $factory;
$factorystr = $factory == '' ? $factorystr : implode(',', $factory);
$system = isset($systemstr) == '1' ? '' : $system;
$systemstr = $system == '' ? $systemstr : implode(',', $system);
$type = isset($typestr) == '1' ? '' : $type;
$typestr = $type == '' ? $typestr : implode(',', $type);
$statusarr = array('0' => array('name' => '0≤N≤3(未保養)', 'status' => 'N0~3'), '1' => array('name' => '3&lt;N≤7(未保養)', 'status' => 'N3~7'), '2' => array('name' => '7&lt;N≤15(未保養)', 'status' => 'N7~15'), '3' => array('name' => '15&lt;N≤30(未保養)', 'status' => 'N15~30'), '4' => array('name' => 'N&gt;30(未保養)', 'status' => 'N>30'), '5' => array('name' => '0≤N≤3(保養中)', 'status' => 'D0~3'), '6' => array('name' => '3&lt;N≤7(保養中)', 'status' => 'D3~7'), '7' => array('name' => '7&lt;N≤15(保養中)', 'status' => 'D7~15'), '8' => array('name' => '15&lt;N≤30(保養中)', 'status' => 'D15~30'), '9' => array('name' => 'N&gt;30(保養中)', 'status' => 'D>30'),);
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
            <script src="js/jquery-2.0.3.min.js"></script>
            <script type="text/javascript" src="My97DatePicker/WdatePicker.js"></script>
            <script language="javascript" src="layer/layer.min.js"></script>
            <script src="js/jquery.dataTables.min.js"></script>
            <script src="js/jquery.dataTables.bootstrap.js"></script>
            <script src="js/hightcharts/highcharts.js"></script>
            <script src="js/hightcharts/modules/exporting.js"></script>
            <script src="js/hightcharts/modules/drilldown.js"></script>
            <script src="js/bootstrap.select.js"></script>
            <script src="js/bootstrap-select.js"></script>
            <script src="js/function.js"></script>
            <script>
                        $(document).ready(function () {
                $('#table').DataTable({
                "paging": false,
                        "ordering": false,
                        "info": false,
                        "searching": false,
                        "scrollX": true
                });
                        $("#deviceDelaySummary").addClass("active");
                        $("#deviceDelay").addClass("active open");
                        $("#maintain").addClass("active open");
    <?php
    if (!empty($factorystr)) {
        ?>
                    factory = '<?= $factorystr ?>'.split(",");
                            for (var i = 0; i < factory.length; i++) {
                    $("#factory option[value='" + factory[i] + "']").attr("selected", true);
                    }
        <?php
        $factoryarr = explode(',', $factorystr);
        $sfactory = "'" . implode("','", $factoryarr) . "'";
        $str = "SELECT DISTINCT SYSTEM FROM device_information_works WHERE FLAG !='1' AND RFCARD IN (
SELECT RFCARD FROM card_mapping WHERE DVSCARD IN (
SELECT DVSCARD FROM position_works WHERE FACTORY in (" . $sfactory . ")))";
        $ssystem = $pdo->query($str);
        $systemarr = $ssystem->fetchAll();
        foreach ($systemarr as $value) {
            echo "$('#system').append('<option value=\'" . $value['SYSTEM'] . "\'>" . getNamech($pdo, $value['SYSTEM']) . "</opption>');";
        }
        if (!empty($systemstr)) {
            ?>
                        system = '<?= $systemstr ?>'.split(",");
                                for (var i = 0; i < system.length; i++) {
                        $("#system option[value='" + system[i] + "']").attr("selected", true);
                        }
            <?php
            $systemarr = explode(',', $systemstr);
            $ssystem = "'" . implode("','", $systemarr) . "'";
            $str = "SELECT DISTINCT TYPE FROM device_information_works WHERE FLAG !='1' AND SYSTEM in (" . $ssystem . ") AND RFCARD IN (
SELECT RFCARD FROM card_mapping WHERE DVSCARD IN (
SELECT DVSCARD FROM position_works WHERE FACTORY in (" . $sfactory . ")))";
            $stype = $pdo->query($str);
            $stypearr = $stype->fetchAll();
            foreach ($stypearr as $value) {
                echo "$('#type').append('<option value=\'" . $value['TYPE'] . "\'>" . getNamech($pdo, $value['TYPE']) . "</opption>');";
            }
            ?>
                        type = '<?= $typestr ?>'.split(",");
                                for (var i = 0; i < type.length; i++) {
                        $("#type option[value='" + type[i] + "']").attr("selected", true);
                        }
            <?php
        }
    }
    ?>
                })
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
                                    <i class="icon-briefcase"></i> 設備保養
                                    <small>
                                        <i class="icon-double-angle-right"></i>
                                        保養結單
                                    </small>
                                    <small>
                                        <i class="icon-double-angle-right"></i>
                                        未結單
                                    </small>
                                </h1>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="table-responsive">
                                                <form class="form-inline" method="post" action="deviceDelayChart.php">
                                                    <label>廠別：</label>
                                                    <div class="form-group" style="width:200px;margin-right: 10px;">
                                                        <select id="factory" class="form-control selectpicker " multiple data-done-button="true" name="factory[]" onchange="changeSystem();
                                                                                changeType()">
                                                            <option value="生活區">生活區</option>
                                                            <option value="F0">F0</option>
                                                            <option value="F1">F1</option>
                                                            <option value="F2">F2</option>
                                                            <option value="F1/F2">F1/F2</option>
                                                            <option value="F3">F3</option>
                                                            <option value="F4">F4</option>
                                                            <option value="F3/F4">F3/F4</option>
                                                            <option value="F5">F5</option>
                                                            <option value="F6">F6</option>
                                                            <option value="F7">F7</option>
                                                            <option value="巨碩">巨碩</option>
                                                        </select>
                                                    </div>
                                                    <label>系統類別：</label>
                                                    <div class="form-group" style="width:200px;margin-right: 10px;">
                                                        <select id="system" class="form-control selectpicker " multiple data-done-button="true" name="system[]" onchange="changeType()">
                                                        </select>
                                                    </div>
                                                    <label>設備類別：</label>
                                                    <div class="form-group" style="width:200px;margin-right: 10px;">
                                                        <select id="type" class="form-control selectpicker " multiple data-done-button="true" name="type[]">
                                                        </select>
                                                    </div>
                                                    <button class="btn btn-primary" type="submit">查詢</button>
                                                    <button class="btn btn-info" type="button" onclick="window.location.href = 'deviceDelaySummary.php?factorystr=<?= $factorystr ?>&systemstr=<?= $systemstr ?>&typestr=<?= $typestr ?>'"><span class="icon-table"></span>表格</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="table-responsive" style="margin-top:10px;">
                                        <?php
                                        $str = "SELECT dd.FACTORY,dd.SYSTEM,dd.TYPE,COUNT(DD.CONTENT) AS TOTAL,
                                                    SUM(IF(dd.FLAG ='NOWAY' AND DD.`STATUS`='0~3','1','0')) AS `N0~3`, 
                                                    SUM(IF(dd.FLAG ='NOWAY' AND DD.`STATUS`='3~7','1','0')) AS `N3~7`, 
                                                    SUM(IF(dd.FLAG ='NOWAY' AND DD.`STATUS`='7~15','1','0')) AS `N7~15`, 
                                                    SUM(IF(dd.FLAG ='NOWAY' AND DD.`STATUS`='15~30','1','0')) AS `N15~30`, 
                                                    SUM(IF(dd.FLAG ='NOWAY' AND DD.`STATUS`='>30','1','0')) AS `N>30`, 
                                                    SUM(IF(dd.FLAG ='DOING' AND DD.`STATUS`='0~3','1','0')) AS `D0~3`, 
                                                    SUM(IF(dd.FLAG ='DOING' AND DD.`STATUS`='3~7','1','0')) AS `D3~7`, 
                                                    SUM(IF(dd.FLAG ='DOING' AND DD.`STATUS`='7~15','1','0')) AS `D7~15`, 
                                                    SUM(IF(dd.FLAG ='DOING' AND DD.`STATUS`='15~30','1','0')) AS `D15~30`, 
                                                    SUM(IF(dd.FLAG ='DOING' AND DD.`STATUS`='>30','1','0')) AS `D>30`
                                                FROM
                                                (SELECT cc.FACTORY,cc.SYSTEM,cc.TYPE,cc.DEVICE_NAME,cc.DEVICE_SN,cc.CONTENT,
                                                    CASE WHEN '0' <=cc.DIFF AND cc.DIFF <='3' THEN '0~3'
                                                        WHEN '3' <cc.DIFF AND CC.DIFF <='7' THEN '3~7'
                                                        WHEN '7' <CC.DIFF AND cc.DIFF <='15' THEN '7~15'
                                                        WHEN '15' <CC.DIFF AND cc.DIFF <='30' THEN '15~30'
                                                        WHEN cc.DIFF >'30' THEN '>30' END `STATUS`,CC.FLAG FROM 
                                                            (SELECT bb.FACTORY,bb.SYSTEM,bb.TYPE,bb.DEVICE_NAME,bb.DEVICE_SN,bb.CONTENT,bb.DATE,DATEDIFF(CURDATE(),bb.DATE) AS DIFF,bb.FLAG FROM
                                                                (SELECT aa.FACTORY,aa.SYSTEM,aa.TYPE,aa.DEVICE_NAME,aa.DEVICE_SN,aa.CONTENT,IF(AA.NEXT_DATE IS NOT NULL,AA.NEXT_DATE,AA.DATE) AS DATE,
                                                                     CASE WHEN aa.NEXT_DATE IS NOT NULL AND aa.DATE is NULL THEN 'NOWAY' 
                                                                        WHEN aa.NEXT_DATE IS NULL AND aa.DATE IS NOT NULL THEN 'DOING' END FLAG FROM 
                                                                            (SELECT cc.FACTORY,aa.SYSTEM,aa.TYPE,aa.DEVICE_NAME,aa.DEVICE_SN,dd.CONTENT,ee.NEXT_DATE,ff.DATE FROM device_information_works aa 
                                                                                LEFT JOIN card_mapping bb ON bb.RFCARD=aa.RFCARD
                                                                                LEFT JOIN position_works cc ON cc.DVSCARD=bb.DVSCARD
                                                                                LEFT JOIN maintain_mapping_works dd ON dd.DEVICE_NAME=aa.DEVICE_NAME
                                                                                LEFT JOIN(SELECT aa.DEVICE_SN,aa.CONTENT,NEXT_DATE FROM maintain_detail_works aa
                                                                                            LEFT JOIN(SELECT DEVICE_SN,CONTENT,MAX(IDX) AS IDX FROM maintain_detail_works GROUP BY DEVICE_SN,CONTENT)bb
                                                                                            ON bb.DEVICE_SN=aa.DEVICE_SN AND bb.CONTENT=aa.CONTENT
                                                                                            WHERE bb.IDX=aa.IDX)ee ON ee.DEVICE_SN=aa.DEVICE_SN AND ee.CONTENT=dd.CONTENT
                                                                                LEFT JOIN maintain_detail_works ff ON ff.DEVICE_SN=aa.DEVICE_SN AND ff.CONTENT=dd.CONTENT AND ff.`STATUS`='0'
                                                                            WHERE aa.FLAG !='1' AND bb.FLAG !='1' AND cc.FLAG !='1' AND dd.CONTENT IS NOT NULL)aa)bb WHERE bb.FLAG IS NOT NULL)cc)dd";
                                        if ($factorystr == '' && $systemstr == '' && $typestr == '') {
                                            $str = $str . " WHERE dd.`STATUS` IS NOT NULL";
                                        } else if ($factorystr != '' && $systemstr == '' && $typestr == '') {
                                            $str = $str . " WHERE dd.`STATUS` IS NOT NULL AND dd.FACTORY in (" . get($factorystr) . ")";
                                        } else if ($factorystr != '' && $systemstr != '' && $typestr == '') {
                                            $str = $str . " WHERE dd.`STATUS` IS NOT NULL AND dd.FACTORY in (" . get($factorystr) . ") AND dd.SYSTEM in (" . get($systemstr) . ")";
                                        } else {
                                            $str = $str . " WHERE dd.`STATUS` IS NOT NULL AND dd.FACTORY in (" . get($factorystr) . ") AND dd.SYSTEM in (" . get($systemstr) . ") AND dd.TYPE in (" . get($typestr) . ")";
                                        }
                                        $chartnum = $pdo->query($str);
                                        $cnarr = $chartnum->fetch();
                                        ?>
                                        <script type="text/javascript">
                                                    $(document).ready(function () {
                                            Highcharts.chart('chart', {
                                            chart: {
                                            plotBackgroundColor: null,
                                                    plotBorderWidth: null,
                                                    plotShadow: false,
                                                    type: 'pie'
                                            },
                                                    title: {
                                                    text: ''
                                                    },
                                                    tooltip: {
                                                    pointFormat: '<table style="font-size:12px;width:80px;"><tr><td>{series.name}:<b>{point.percentage:.1f}%</b></td></tr><tr><td>數量:<b>{point.y:1f}</b></td></tr></table>',
                                                            shared: true,
                                                            useHTML: true
                                                    },
                                                    plotOptions: {
                                                    pie: {
                                                    allowPointSelect: true,
                                                            cursor: 'pointer',
                                                            dataLabels: {
                                                            enabled: true,
                                                                    format: '<b style="font-size:14px;">{point.name}: {point.percentage:.1f} %</b>',
                                                                    style: {
                                                                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                                                    }
                                                            },
                                                            showInLegend: true
                                                    }
                                                    },
                                                    series: [{
                                                    name: '未結單',
                                                            colorByPoint: true,
                                                            data: [
    <?php
    foreach ($statusarr as $value) {
        ?>
                                                                {
                                                                name: '<?= $value['name'] ?>',
                                                                        y: <?= $cnarr[$value['status']] ?>,
                                                                        drilldown: '<?= $value['status'] ?>'
                                                                },
        <?php
    }
    ?>
                                                            ]
                                                    }],
                                                    drilldown: {
                                                    series: [
    <?php
    foreach ($statusarr as $value) {
        $str1 = "SELECT DD.FACTORY,COUNT(DD.CONTENT) AS NUM FROM
(SELECT cc.FACTORY,cc.FLOOR,cc.AREA,cc.ROOM,cc.SYSTEM,cc.TYPE,cc.DEVICE_NAME,cc.DEVICE_SN,cc.CONTENT,
    CASE WHEN cc.FLAG='NOWAY' AND '0' <=cc.DIFF AND cc.DIFF <='3' THEN 'N0~3'
        WHEN cc.FLAG='NOWAY' AND '3' <cc.DIFF AND CC.DIFF <='7' THEN 'N3~7'
        WHEN cc.FLAG='NOWAY' AND '7' <CC.DIFF AND cc.DIFF <='15' THEN 'N7~15'
        WHEN cc.FLAG='NOWAY' AND '15' <CC.DIFF AND cc.DIFF <='30' THEN 'N15~30'
        WHEN cc.FLAG='NOWAY' AND cc.DIFF >'30' THEN 'N>30'
        WHEN cc.FLAG='DOING' AND '0' <=cc.DIFF AND cc.DIFF <='3' THEN 'D0~3'
        WHEN cc.FLAG='DOING' AND '3' <cc.DIFF AND CC.DIFF <='7' THEN 'D3~7'
        WHEN cc.FLAG='DOING' AND '7' <CC.DIFF AND cc.DIFF <='15' THEN 'D7~15'
        WHEN cc.FLAG='DOING' AND '15' <CC.DIFF AND cc.DIFF <='30' THEN 'D15~30'
        WHEN cc.FLAG='DOING' AND cc.DIFF >'30' THEN 'D>30' END `STATUS`,cc.DIFF,CC.FLAG FROM 
            (SELECT bb.FACTORY,bb.FLOOR,bb.AREA,bb.ROOM,bb.SYSTEM,bb.TYPE,bb.DEVICE_NAME,bb.DEVICE_SN,bb.CONTENT,bb.DATE,DATEDIFF(CURDATE(),bb.DATE) AS DIFF,bb.FLAG FROM
                (SELECT aa.FACTORY,aa.FLOOR,aa.AREA,aa.ROOM,aa.SYSTEM,aa.TYPE,aa.DEVICE_NAME,aa.DEVICE_SN,aa.CONTENT,IF(AA.NEXT_DATE IS NOT NULL,AA.NEXT_DATE,AA.DATE) AS DATE,
                    CASE WHEN aa.NEXT_DATE IS NOT NULL AND aa.DATE is NULL THEN 'NOWAY' 
                        WHEN aa.NEXT_DATE IS NULL AND aa.DATE IS NOT NULL THEN 'DOING' END FLAG FROM 
                            (SELECT cc.FACTORY,cc.FLOOR,cc.AREA,cc.ROOM,aa.SYSTEM,aa.TYPE,aa.DEVICE_NAME,aa.DEVICE_SN,dd.CONTENT,ee.NEXT_DATE,ff.DATE FROM device_information_works aa 
                                LEFT JOIN card_mapping bb ON bb.RFCARD=aa.RFCARD
                                LEFT JOIN position_works cc ON cc.DVSCARD=bb.DVSCARD
                                LEFT JOIN maintain_mapping_works dd ON dd.DEVICE_NAME=aa.DEVICE_NAME
                                LEFT JOIN(SELECT aa.DEVICE_SN,aa.CONTENT,NEXT_DATE FROM maintain_detail_works aa
                                            LEFT JOIN(SELECT DEVICE_SN,CONTENT,MAX(IDX) AS IDX FROM maintain_detail_works GROUP BY DEVICE_SN,CONTENT)bb
                                            ON bb.DEVICE_SN=aa.DEVICE_SN AND bb.CONTENT=aa.CONTENT
                                        WHERE bb.IDX=aa.IDX)ee ON ee.DEVICE_SN=aa.DEVICE_SN AND ee.CONTENT=dd.CONTENT
                                LEFT JOIN maintain_detail_works ff ON ff.DEVICE_SN=aa.DEVICE_SN AND ff.CONTENT=dd.CONTENT AND ff.`STATUS`='0'
                            WHERE aa.FLAG !='1' AND bb.FLAG !='1' AND cc.FLAG !='1' AND dd.CONTENT IS NOT NULL)aa)bb WHERE bb.FLAG IS NOT NULL)cc)dd";
        if ($factorystr == '' && $systemstr == '' && $typestr == '') {
            $str2 = $str1 . " WHERE dd.`STATUS` ='" . $value['status'] . "' GROUP BY DD.FACTORY";
        } else if ($factorystr != '' && $systemstr == '' && $typestr == '') {
            $str2 = $str1 . " WHERE dd.`STATUS` ='" . $value['status'] . "' AND dd.FACTORY in (" . get($factorystr) . ") GROUP BY DD.FACTORY";
        } else if ($factorystr != '' && $systemstr != '' && $typestr == '') {
            $str2 = $str1 . " WHERE dd.`STATUS` ='" . $value['status'] . "' AND dd.FACTORY in (" . get($factorystr) . ") AND dd.SYSTEM in (" . get($systemstr) . ") GROUP BY DD.FACTORY";
        } else {
            $str2 = $str1 . " WHERE dd.`STATUS` ='" . $value['status'] . "' AND dd.FACTORY in (" . get($factorystr) . ") AND dd.SYSTEM in (" . get($systemstr) . ") AND dd.TYPE in (" . get($typestr) . ") GROUP BY DD.FACTORY";
        }
        $factorynum = $pdo->query($str2);
        $fn = $factorynum->fetchAll();
        ?>
                                                        {
                                                        name: '<?= $value['name'] ?>',
                                                                id: '<?= $value['status'] ?>',
                                                                data: [
        <?php
        foreach ($fn as $row) {
            ?>
                                        ['<?=$row['FACTORY']?>',<?=$row['NUM']?>],
            <?php
        }
        ?>
                                                                ]
                                                        },
        <?php
    }
    ?>]
                                                    },
                                                    legend: {layout: 'vertical', align: 'right', itemStyle: {'fontSize': '14px'}},
                                                    credits: {enabled: false},
                                                    exporting: {enabled: false},
                                            });
                                            })
                                        </script>
                                        <div id="chart" style="width:100%;height:400px;border:#fcfcfc 1px solid;box-shadow: 0 0 15px rgba(0,0,0,0.2);border-radius: 5px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.main-container -->

        </body>
    </html>
    <?php
} else if ($_COOKIE['cdepartment'] == 'IE') {
    echo "<script>window.top.location.href='pilotFrequencyList.php'</script>";
} else {
    echo "<script>window.top.location.href='deviceList_product.php'</script>";
}
?>