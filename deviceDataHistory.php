<?php header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate'); ?>
<?php header('content-Type: text/html; charset=UTF-8'); ?>
<?php @ require_once 'database/database.php'; ?>
<?php @ require_once 'function/checkCookie.php'; ?>
<?php @ require_once 'function/functions.php'; ?>
<?php
$userfactorystr = $_COOKIE['cuserfactory'];
$userfactoryarr=explode("','",$userfactorystr);
$datearr = datearr($startdate, $enddate); //时间数组
$factory = isset($factorystr) == '1' ? '' : $factory;
$factorystr = $factory == '' ? $factorystr : implode(',', $factory);
$system = isset($systemstr) == '1' ? '' : $system;
$systemstr = $system == '' ? $systemstr : implode(',', $system);
$type = isset($typestr) == '1' ? '' : $type;
$typestr = $type == '' ? $typestr : implode(',', $type);

$str = "SELECT * FROM device_information_works aa
        LEFT JOIN card_mapping bb ON bb.RFCARD=aa.RFCARD
        LEFT JOIN position_works cc ON cc.DVSCARD=bb.DVSCARD
        LEFT JOIN type_mapping_works dd ON dd.TYPE=aa.TYPE
        WHERE aa.FLAG !='1' AND bb.FLAG !='1' AND cc.FLAG !='1' AND cc.FACTORY in (" . get($factorystr) . ") AND aa.DEVICE_NAME='" . $device_name . "'";
$ds = $pdo->query($str);
$dsarr = $ds->fetchAll();
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
            <script src="js/bootstrap-select.js"></script>
            <script src="js/function.js"></script>
            <script>
                $(document).ready(function () {
                    $('#table').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "searching": false,
                        "scrollX": true,
                        fixedColumns: {//关键是这里了，需要第一列不滚动就设置1
                            leftColumns: 1
                        },
                    });

                    $("#deviceDataHistory").addClass("active");
                    $("#deviceData").addClass("active open");
                    $("#pilot").addClass("active open");
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
        $str = "SELECT DISTINCT SYSTEM FROM device_information_works aa
LEFT JOIN card_mapping bb ON bb.RFCARD=aa.RFCARD
LEFT JOIN position_works cc ON cc.DVSCARD=bb.DVSCARD
WHERE aa.FLAG !='1' AND bb.FLAG !='1' AND cc.FLAG !='1' AND cc.FACTORY in (" . $sfactory . ")";
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
            $str = "SELECT DISTINCT TYPE FROM device_information_works aa
LEFT JOIN card_mapping bb ON bb.RFCARD=aa.RFCARD
LEFT JOIN position_works cc ON cc.DVSCARD=bb.DVSCARD
WHERE aa.FLAG !='1' AND bb.FLAG !='1' AND cc.FLAG !='1' AND cc.FACTORY in (" . $sfactory . ") AND aa.SYSTEM in (" . $ssystem . ")";
            $stype = $pdo->query($str);
            $stypearr = $stype->fetchAll();
            foreach ($stypearr as $value) {
                echo "$('#type').append('<option value=\'" . $value['TYPE'] . "\'>" . getNamech($pdo, $value['TYPE']) . "</opption>');";
            }

            if (!empty($typestr)) {
                ?>
                                type = '<?= $typestr ?>'.split(",");
                                for (var i = 0; i < type.length; i++) {
                                    $("#type option[value='" + type[i] + "']").attr("selected", true);
                                }
                <?php
                $typearr = explode(',', $typestr);
                $stype = "'" . implode("','", $typearr) . "'";
                $str = "SELECT DISTINCT DEVICE_NAME FROM device_information_works aa
LEFT JOIN card_mapping bb ON bb.RFCARD=aa.RFCARD
LEFT JOIN position_works cc ON cc.DVSCARD=bb.DVSCARD
WHERE aa.FLAG !='1' AND bb.FLAG !='1' AND cc.FLAG !='1' AND cc.FACTORY in (" . $sfactory . ") AND aa.SYSTEM in (" . $ssystem . ") AND aa.TYPE in (" . $stype . ")";
                $sdn = $pdo->query($str);
                $sdnarr = $sdn->fetchAll();
                echo "$('#device_name').append('<option></opption>');";
                foreach ($sdnarr as $value) {
                    echo "$('#device_name').append('<option value=\'" . $value['DEVICE_NAME'] . "\'>" . $value['DEVICE_NAME'] . "</opption>');";
                }
                ?>
                                $("#device_name option[value='<?= $device_name ?>']").attr("selected", true);
                <?php
            }
        }
    }
    ?>
                })

                function check() {
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
                    var aa = 1056800000;
                    if (end - start > aa) {
                        alert("時間範圍不能超過两周！");
                        $("#endtime").val('');
                        $("#endtime").focus();
                        return false;
                    }
                    if ($("#factory").val() == null) {
                        alert("請選擇廠別！");
                        $("#factory").focus();
                        return false;
                    }
                    if ($("#system").val() == null) {
                        alert("請選擇系統類別！");
                        $("#system").focus();
                        return false;
                    }
                    if ($("#type").val() == null) {
                        alert("請選擇設備類別！");
                        $("#type").focus();
                        return false;
                    }
                    if ($("#device_name").val() == null) {
                        alert("請選擇設備名稱！");
                        $("#device_name").focus();
                        return false;
                    }
                }
                function statusMain(startdate, enddate, factorystr, systemstr, typestr, device_sn) {
                    $.layer({
                        type: 2,
                        title: '設備點檢狀態',
                        maxmin: true,
                        shadeClose: true,
                        area: ['1000px', '480px'],
                        offset: ['100px', ''],
                        shift: '',
                        iframe: {src: "pop/deviceMaintainDetail.php?startdate=" + startdate + "&enddate=" + enddate + "&factorystr=" + factorystr + "&systemstr=" + systemstr + "&typestr=" + typestr + "&device_sn=" + device_sn + "&states=history"}
                    });
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
                                    <i class="icon-briefcase"></i> 保養
                                    <small>
                                        <i class="icon-double-angle-right"></i>
                                        設備保養
                                    </small>
                                    <small>
                                        <i class="icon-double-angle-right"></i>
                                        歷史查詢
                                    </small>
                                </h1>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="table-responsive">
                                                <form method="post" action="deviceDataHistory.php">
                                                    <div class="form-inline">
                                                        <label>開始日期：</label>
                                                        <div class="form-group" style="width:200px;margin-right: 20px;">
                                                            <input type="text" class="Wdate form-control" onFocus="WdatePicker({dateFmt: 'yyyy-MM-dd', maxDate: '%y-%M-{%d-1}'})" id="startdate" name="startdate" value="<?= $startdate ?>">
                                                        </div>
                                                        <label>結束日期：</label>
                                                        <div class="form-group" style="width:200px;margin-right: 20px;">
                                                            <input type="text" class="Wdate form-control" onFocus="WdatePicker({dateFmt: 'yyyy-MM-dd', maxDate: '%y-%M-{%d-1}'})" id="enddate" name="enddate" value="<?= $enddate ?>">
                                                        </div>
                                                        <label>廠別：</label>
                                                        <div class="form-group" style="width:200px;margin-left: 28px;margin-right: 20px;">
                                                            <select id="factory" class="form-control selectpicker " multiple data-done-button="true" name="factory[]" onchange="changeSystem();
                                                                    changeType();
                                                                    changeDevice_name();">
                                                                 <?php 
                                    foreach ($userfactoryarr as $key => $value) {
                                        echo "<option value='$value'>$value</option>";  
                                    }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-inline" style="margin-top: 10px;">
                                                        <label>系統類別：</label>
                                                        <div class="form-group" style="width:200px;margin-right: 20px;">
                                                            <select id="system" class="form-control selectpicker " multiple data-done-button="true" name="system[]" onchange="changeType();
                                                                    changeDevice_name();">
                                                            </select>
                                                        </div>
                                                        <label>設備類別：</label>
                                                        <div class="form-group" style="width:200px;margin-right: 20px;">
                                                            <select id="type" class="form-control selectpicker " multiple data-done-button="true" name="type[]" onchange="changeDevice_name();">
                                                            </select>
                                                        </div>
                                                        <label>設備名称：</label>
                                                        <div class="form-group" style="width:200px;margin-right: 20px;">
                                                            <select id="device_name" class="form-control selectpicker " data-done-button="true" name="device_name">
                                                            </select>
                                                        </div>
                                                        <button class="btn btn-primary btn-sm" type="submit" onclick="return check();"><i class="icon-search"></i>查詢</button>
    <!--                                                        <button class="btn btn-white" type="button" onclick="window.location.href = 'function/download.php?action=maintain&startdate=<?= $startdate ?>&enddate=<?= $enddate ?>&factorystr=<?= $factorystr ?>&systemstr=<?= $systemstr ?>&typestr=<?= $typestr ?>'">
                                                            <span class="icon-download"></span>下載
                                                        </button>-->
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
                                        <?php
                                        if (!empty($device_name)) {
                                            ?>
                                            <table id="table" class=" nowrap table table-striped table-bordered table-hover" cellspacing="0" style="word-break: keep-all;white-space:nowrap;width:100%;text-align: center;">
                                                <thead style="background:#f0f0f0;">
                                                    <tr>
                                                        <td rowspan="2" style="line-height: 50px">日期</td>
                                                        <?php
                                                        for ($i = 0; $i < count($datearr); $i++) {
                                                            $num = 4 * count($dsarr);
                                                            echo "<td colspan='" . (4 * count($dsarr)) . "'>" . $datearr[$i] . "</td>";
                                                        }
                                                        ?>
                                                    </tr>
                                                    <tr>
                                                        <?php
                                                        for ($i = 0; $i < count($datearr); $i++) {
                                                            foreach ($dsarr as $value) {
                                                                echo "<td>08:00~14:00</td>";
                                                                echo "<td>14:00~20:00</td>";
                                                                echo "<td>20:00~02:00</td>";
                                                                echo "<td>02:00~08:00</td>";
                                                            }
                                                        }
                                                        ?>
                                                    </tr>
                                                    <tr>
                                                        <td style="min-width: 200px">SN<br><?= $dsarr['0'][NAME] . '(' . $dsarr['0'][UNIT] ?>)</td>
                                                            <?php
                                                            for ($i = 0; $i < count($datearr); $i++) {
                                                                foreach ($dsarr as $value) {
                                                                    echo "<td colspan='4'>" . $value['DEVICE_SN'] . "<br>" . $value[$value['MAPPING']] . "</td>";
                                                                }
                                                            }
                                                            ?>
                                                    </tr>
                                                    <tr>
                                                        <td>抄錶狀態</td>
                                                        <?php
                                                        for ($i = 0; $i < count($datearr); $i++) {
                                                            foreach ($dsarr as $value) {
                                                                echo "<td>".statusDataWorks($pdo,$datearr[$i],'0',$value['DEVICE_SN'])."</td>";
                                                                echo "<td>".statusDataWorks($pdo,$datearr[$i],'1',$value['DEVICE_SN'])."</td>";
                                                                echo "<td>".statusDataWorks($pdo,$datearr[$i],'2',$value['DEVICE_SN'])."</td>";
                                                                echo "<td>".statusDataWorks($pdo,$datearr[$i],'3',$value['DEVICE_SN'])."</td>";
                                                            }
                                                        }
                                                        ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $str = "SELECT * FROM data_mapping_works WHERE DEVICE_NAME='" . $device_name . "' AND FLAG !='1'";
                                                    $dn = $pdo->query($str);
                                                    $dnarr = $dn->fetchAll();
                                                    foreach ($dnarr as $row) {
                                                        echo "<tr>";
                                                        echo "<td>" . $row['CONTENT'] . "</td>";
                                                        for ($i = 0; $i < count($datearr); $i++) {
                                                            foreach ($dsarr as $value) {
                                                                $str = "SELECT bb.`DATA`,bb.FLAG FROM data_detail_works bb
                                                                            LEFT JOIN (SELECT max(IDX) AS IDX FROM data_detail_works WHERE DEVICE_SN='" . $value['DEVICE_SN'] . "' AND CONTENT='" . $row['CONTENT'] . "' AND DATE='" . $datearr[$i] . "' GROUP BY FLAG)aa
                                                                            ON aa.IDX=bb.IDX WHERE aa.IDX IS NOT NULL";
                                                                $data = $pdo->query($str);
                                                                $darr = $data->fetchAll();
                                                                $data0 = '';
                                                                $data1 = '';
                                                                $data2 = '';
                                                                $data3 = '';
                                                                foreach ($darr as $drow) {
                                                                    if ($drow['FLAG'] == '0') {
                                                                        $data0 = $drow['DATA'];
                                                                    }
                                                                    if ($drow['FLAG'] == '1') {
                                                                        $data1 = $drow['DATA'];
                                                                    }
                                                                    if ($drow['FLAG'] == '2') {
                                                                        $data2 = $drow['DATA'];
                                                                    }
                                                                    if ($drow['FLAG'] == '3') {
                                                                        $data3 = $drow['DATA'];
                                                                    }
                                                                }
                                                                echo "<td>" . $data0 . "</td>";
                                                                echo "<td>" . $data1 . "</td>";
                                                                echo "<td>" . $data2 . "</td>";
                                                                echo "<td>" . $data3 . "</td>";
                                                            }
                                                        }
                                                        echo "</tr>";
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                            <?php
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