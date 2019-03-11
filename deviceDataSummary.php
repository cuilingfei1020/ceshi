<?php header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate'); ?>
<?php header('content-Type: text/html; charset=UTF-8'); ?>
<?php @ require_once 'database/database.php'; ?>
<?php @ require_once 'function/checkCookie.php'; ?>
<?php @ require_once 'function/functions.php'; ?>
<?php
$userfactorystr = $_COOKIE['cuserfactory'];
$userfactoryarr=explode("','",$userfactorystr);
$factory = isset($factorystr) == '1' ? '' : $factory;
$factorystr = $factory == '' ? $factorystr : implode(',', $factory);
$system = isset($systemstr) == '1' ? '' : $system;
$systemstr = $system == '' ? $systemstr : implode(',', $system);
$type = isset($typestr) == '1' ? '' : $type;
$typestr = $type == '' ? $typestr : implode(',', $type);
if (empty($startdate)) {
    $startdate = date('Y-m-d', strtotime('-7 days'));
    $enddate = date('Y-m-d', strtotime('-1 days'));
}
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
                            leftColumns: 4
                        },
                    });

                    $("#deviceDataSummary").addClass("active");
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

                function check() {
                    if ($("#startdate").val() != '' && $("#enddate").val() == '') {
                        alert("請選擇結束時間！");
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
                    var aa = 518400000;
                    if (end - start > aa) {
                        alert("時間範圍不能超過七天！");
                        $("#endtime").val('');
                        $("#endtime").focus();
                        return false;
                    }
                }

                function openDevicesn(date, factory, system, type, day, status, name, unit) {
                    $("#pop").remove();
                    var e = event || window.event;
                    var scrollX = document.documentElement.scrollLeft || document.body.scrollLeft;
                    var scrollY = document.documentElement.scrollTop || document.body.scrollTop;
                    var x = e.pageX || e.clientX + scrollX;
                    var y = e.pageY || e.clientY + scrollY;
                    $.ajax({
                        type: "POST",
                        async: false,
                        url: "function/getDevice_sn.php?flag=data",
                        data: {date: date, factory: factory, system: system, type: type, day: day, status: status},
                        success: function (data) {
                            var arr = data.split(",");
                            var Html = "<div id='pop' onmouseout=\"closeDevicesn()\" style='width:300px;max-height:120px;overflow: auto;text-align: center;'><table class='table-bordered' style='word-break: keep-all;white-space:nowrap;background: #93bddc;width:100%'>\n\
                                            <thead><tr><td>設備SN</td><td>" + name + "</br>(" + unit + ")</td></tr></thead>\n\
                                            <tbody>";
                            for (var i = 0; i < arr.length; i += 2) {
                                Html += "<tr><td>" + arr[i] + "</td><td>" + arr[i + 1] + "</td></tr>";
                            }
                            Html += "</tbody></table></div>";
                            $("body").append(Html);
                            $("#pop").css({'display': 'block', 'position': 'fixed', 'top': y + 'px', 'left': x - 300 + 'px', 'float': 'right'});
                        }
                    });
                }
                function closeDevicesn() {
                    var div = document.getElementById("pop");
                    var x = event.clientX;
                    var y = event.clientY;
                    var divx1 = div.offsetLeft;
                    var divy1 = div.offsetTop;
                    var divx2 = div.offsetLeft + div.offsetWidth;
                    var divy2 = div.offsetTop + div.offsetHeight;
                    if (x < divx1 || x > divx2 || y < divy1 || y > divy2) {
                        $("#pop").remove();
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
                                        匯總
                                    </small>
                                </h1>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="table-responsive">
                                                <form method="post" action="deviceDataSummary.php">
                                                    <div class="form-inline">
                                                        <label>開始日期：</label>
                                                        <div class="form-group" style="width:200px;margin-right: 20px;">
                                                            <input type="text" class="Wdate form-control" onFocus="WdatePicker({dateFmt: 'yyyy-MM-dd', maxDate: '%y-%M-{%d-1}'})" id="startdate" name="startdate" value="<?= $startdate ?>">
                                                        </div>
                                                        <label>結束日期：</label>
                                                        <div class="form-group" style="width:200px;margin-right: 20px;">  
                                                            <input type="text" class="Wdate form-control" onFocus="WdatePicker({dateFmt: 'yyyy-MM-dd', maxDate: '%y-%M-{%d-1}'})" id="enddate" name="enddate" value="<?= $enddate ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-inline" style="margin-top: 10px;">
                                                        <label>廠別：</label>
                                                        <div class="form-group" style="width:200px;margin-left:28px;margin-right: 20px;">
                                                            <select id="factory" class="form-control selectpicker " multiple data-done-button="true" name="factory[]" onchange="changeSystem();
                                                                    changeType()">
                                                                  <?php 
                                    foreach ($userfactoryarr as $key => $value) {
                                        echo "<option value='$value'>$value</option>";  
                                    }
                                                                ?>
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
                                                        <button class="btn btn-primary btn-sm" type="submit" onclick="return check()"><i class="icon-search"></i>查詢</button>
                                                        <!--<button class="btn btn-white" type="button" onclick="window.location.href = 'deviceDataChart.php?factorystr=<?= $factorystr ?>&systemstr=<?= $systemstr ?>&typestr=<?= $typestr ?>'"><span class="icon-bar-chart"></span>圖形</button>-->
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="table-responsive" style="margin-top:10px">
                                        <table id="table" class=" nowrap table table-striped table-bordered table-hover" cellspacing="0" style="word-break: keep-all;white-space:nowrap;width:100%;text-align: center">
                                            <thead style="background: #f0f0f0">
                                                <tr>
                                                    <td rowspan="3" style="line-height: 90px;">廠別</td>
                                                    <td rowspan="3" style="line-height: 90px;">系統類別</td>
                                                    <td rowspan="3" style="line-height: 90px;">設備類別</td>
                                                    <td rowspan="3" style="line-height: 90px">總計</td>
                                                    <?php
                                                    for ($i = 0; $i < count($datearr); $i++) {

                                                        echo "<td colspan = '6'>" . $datearr[$i] . "</td>";
                                                    }
                                                    ?>
                                                </tr>
                                                <tr>
                                                    <?php
                                                    for ($i = 0; $i < count($datearr); $i++) {
                                                        echo "<td colspan = '3'>白班</td>";
                                                        echo "<td colspan = '3'>夜班</td>";
                                                    }
                                                    ?>
                                                </tr>
                                                <tr>
                                                    <?php
                                                    for ($i = 0; $i < count($datearr); $i++) {
                                                        echo "<td>開機</td>";
                                                        echo "<td>待機</td>";
                                                        echo "<td>故障</td>";
                                                        echo "<td>開機</td>";
                                                        echo "<td>待機</td>";
                                                        echo "<td>故障</td>";
                                                    }
                                                    ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $p = $p == '' ? 1 : $p;
                                                $str = "SELECT aa.FACTORY,aa.SYSTEM,aa.TYPE,count(aa.DEVICE_SN) AS TOTAL FROM
                                                            (SELECT cc.FACTORY,aa.SYSTEM,aa.TYPE,aa.DEVICE_SN FROM device_information_works aa 
                                                                LEFT JOIN card_mapping bb ON bb.RFCARD=aa.RFCARD
                                                                LEFT JOIN position_works cc ON cc.DVSCARD=bb.DVSCARD
                                                                WHERE aa.DATA_METHOD in ('1','2') AND aa.FLAG <>'1' AND bb.FLAG <>'1' AND cc.FLAG <>'1' AND cc.FACTORY <>'')aa";
                                                if ($factorystr == '' && $systemstr == '' && $typestr == '') {
                                                    $str = $str . " WHERE aa.FACTORY in ('" . $userfactorystr . "') GROUP BY aa.FACTORY,aa.SYSTEM,aa.TYPE";
                                                } else if ($factorystr != '' && $systemstr == '' && $typestr == '') {
                                                    $str = $str . " WHERE aa.FACTORY in (" . get($factorystr) . ") GROUP BY aa.FACTORY,aa.SYSTEM,aa.TYPE";
                                                } else if ($factorystr != '' && $systemstr != '' && $typestr == '') {
                                                    $str = $str . " WHERE aa.FACTORY in (" . get($factorystr) . ") AND aa.SYSTEM in (" . get($systemstr) . ") GROUP BY aa.FACTORY,aa.SYSTEM,aa.TYPE";
                                                } else {
                                                    $str = $str . " WHERE aa.FACTORY in (" . get($factorystr) . ") AND aa.SYSTEM in (" . get($systemstr) . ") AND aa.TYPE in (" . get($typestr) . ") GROUP BY aa.FACTORY,aa.SYSTEM,aa.TYPE";
                                                }
                                                $total = getQueryCount($pdo, $str);
                                                if ($total > 0) {
                                                    $perpage = 11;
                                                    $lastpage = ceil($total / $perpage);
                                                    $firstpage = $lastpage > 0 ? 1 : 0;
                                                    $nowpage = $p == "" ? $firstpage : $p;
                                                    $nowpage = $nowpage <= $firstpage ? $firstpage : $nowpage;
                                                    $nowpage = $nowpage >= $lastpage ? $lastpage : $nowpage;
                                                    $prepage = $nowpage <= $firstpage ? 1 : $nowpage - 1;
                                                    $nextpage = $nowpage >= $lastpage ? $lastpage : $nowpage + 1;
                                                    $p = $p <= $firstpage ? $firstpage : $p;
                                                    $p = $p >= $lastpage ? $lastpage : $p;
                                                    $start = $nowpage * $perpage - $perpage;
                                                    $str = $str . " LIMIT " . $start . "," . $perpage;
                                                    $user = $pdo->query($str);
                                                    $userarr = $user->fetchAll();
                                                    $update = 0;
                                                    foreach ($userarr as $value) {
                                                        echo "<tr>";
                                                        echo "<td>" . $value['FACTORY'] . "</td>";
                                                        echo "<td>" . getNamech($pdo, $value['SYSTEM']) . "</td>";
                                                        echo "<td>" . getNamech($pdo, $value['TYPE']) . "</td>";
                                                        echo "<td>" . $value['TOTAL'] . "</td>";

                                                        for ($i = 0; $i < count($datearr); $i++) {
                                                            $str = "SELECT sum(if(ee.DSTATUS='dkai','1','0')) AS 'dkai',sum(if(ee.DSTATUS='ddai','1','0')) AS 'ddai',sum(if(ee.DSTATUS='dgu','1','0')) AS 'dgu',
                                                                        sum(if(ee.NSTATUS='nkai','1','0')) AS 'nkai',sum(if(ee.NSTATUS='ndai','1','0')) AS 'ndai',sum(if(ee.NSTATUS='ngu','1','0')) AS 'ngu',ee.`NAME`,ee.`UNIT` FROM
                                                                      (SELECT cc.FACTORY,aa.SYSTEM,aa.TYPE,aa.DEVICE_SN,dd.DSTATUS,dd.NSTATUS,ee.`NAME`,ee.`UNIT` FROM device_information_works aa 
                                                                            LEFT JOIN card_mapping bb ON bb.RFCARD=aa.RFCARD
                                                                            LEFT JOIN position_works cc ON cc.DVSCARD=bb.DVSCARD
                                                                            LEFT JOIN (SELECT dd.DEVICE_SN,
                                                                                        CASE WHEN sum(IF(dd.DEVICE_STATUS='ddai','1','0'))>0 THEN 'ddai'
                                                                                            WHEN sum(IF(dd.DEVICE_STATUS='dgu','1','0'))>0 THEN 'dgu'
                                                                                            WHEN sum(IF(dd.DEVICE_STATUS='dkai','1','0'))=2 THEN 'dkai' END DSTATUS,
                                                                                        CASE WHEN sum(IF(dd.DEVICE_STATUS='ndai','1','0'))>0 THEN 'ndai'
                                                                                            WHEN sum(IF(dd.DEVICE_STATUS='ngu','1','0'))>0 THEN 'ngu'
                                                                                            WHEN sum(IF(dd.DEVICE_STATUS='nkai','1','0'))=2 THEN 'nkai' END NSTATUS
                                                                                    FROM(SELECT cc.DEVICE_SN,
                                                                                            CASE WHEN cc.FLAG ='D' AND cc.DEVICE_STATUS='開機' THEN 'dkai'
                                                                                                WHEN cc.FLAG ='D' AND cc.DEVICE_STATUS='故障' THEN 'dgu'
                                                                                                WHEN cc.FLAG ='D' AND cc.DEVICE_STATUS='待機' THEN 'ddai'
                                                                                                WHEN cc.FLAG ='T' AND cc.DEVICE_STATUS='開機' THEN 'nkai'
                                                                                                WHEN cc.FLAG ='T' AND cc.DEVICE_STATUS='故障' THEN 'ngu'
                                                                                                WHEN cc.FLAG ='T' AND cc.DEVICE_STATUS='待機' THEN 'ndai' END DEVICE_STATUS
                                                                                        FROM
                                                                                       (SELECT aa.DEVICE_SN,aa.DEVICE_STATUS,CASE WHEN aa.FLAG in ('0','1') THEN 'D' WHEN aa.FLAG in ('2','3') THEN 'T' END FLAG 
                                                                                            FROM data_detail_works aa
                                                                                            LEFT JOIN(SELECT DATE,DEVICE_SN,max(IDX) as IDX,FLAG FROM data_detail_works GROUP BY DATE,DEVICE_SN,FLAG)bb
                                                                                            ON bb.DATE=aa.DATE
                                                                                            where aa.`DATE`='" . $datearr[$i] . "' and bb.IDX=aa.IDX ORDER BY aa.DEVICE_SN,aa.FLAG
                                                                                    )cc)dd GROUP BY dd.DEVICE_SN)dd ON dd.DEVICE_SN=aa.DEVICE_SN
                                                                                    LEFT JOIN type_mapping_works ee ON ee.TYPE=aa.TYPE
                                                                            WHERE aa.DATA_METHOD in ('1','2') AND aa.FLAG <>'1' AND bb.FLAG <>'1' AND cc.FLAG <>'1' AND cc.FACTORY <>''
                                                                       AND cc.FACTORY='" . $value['FACTORY'] . "' AND aa.SYSTEM='" . $value['SYSTEM'] . "' AND aa.TYPE='" . $value['TYPE'] . "')ee";
                                                            $device_status = $pdo->query($str);
                                                            $dsarr = $device_status->fetch();
                                                            if ($dsarr['dkai'] == '0') {
                                                                echo "<td>" . $dsarr['dkai'] . "</td>";
                                                            } else {
//                                                                echo "<td style='cursor:pointer' onmouseover=\"openDevicesn('" . $datearr[$i] . "','" . $value['FACTORY'] . "','" . $value['SYSTEM'] . "','" . $value['TYPE'] . "','D','dkai')\" onclick=\"window.location.href='deviceDataNUM.php?startdate=" . $startdate . "&enddate=" . $enddate . "&factorystr=" . $factorystr . "&systemstr=" . $systemstr . "&typestr=" . $typestr . "&date=" . $datearr[$i] . "&factory=" . $value['FACTORY'] . "&system=" . $value['SYSTEM'] . "&type=" . $value['TYPE'] . "&day=D&status=dkai'\" onmouseout=\"closeDevicesn()\">" . $dsarr['dkai'] . "</td>";
                                                                echo "<td style='cursor:pointer' onmouseover=\"openDevicesn('" . $datearr[$i] . "','" . $value['FACTORY'] . "','" . $value['SYSTEM'] . "','" . $value['TYPE'] . "','D','dkai','" . $dsarr['NAME'] . "','" . $dsarr['UNIT'] . "')\" onmouseout=\"closeDevicesn()\">" . $dsarr['dkai'] . "</td>";
                                                            }
                                                            if ($dsarr['ddai'] == '0') {
                                                                echo "<td>" . $dsarr['ddai'] . "</td>";
                                                            } else {
                                                                echo "<td style='cursor:pointer' onmouseover=\"openDevicesn('" . $datearr[$i] . "','" . $value['FACTORY'] . "','" . $value['SYSTEM'] . "','" . $value['TYPE'] . "','D','ddai','" . $dsarr['NAME'] . "','" . $dsarr['UNIT'] . "')\" onmouseout=\"closeDevicesn()\">" . $dsarr['ddai'] . "</td>";
                                                            }
                                                            if ($dsarr['dgu'] == '0') {
                                                                echo "<td>" . $dsarr['dgu'] . "</td>";
                                                            } else {
                                                                echo "<td style='cursor:pointer' onmouseover=\"openDevicesn('" . $datearr[$i] . "','" . $value['FACTORY'] . "','" . $value['SYSTEM'] . "','" . $value['TYPE'] . "','D','dgu','" . $dsarr['NAME'] . "','" . $dsarr['UNIT'] . "')\" onmouseout=\"closeDevicesn()\">" . $dsarr['dgu'] . "</td>";
                                                            }
                                                            if ($dsarr['nkai'] == '0') {
                                                                echo "<td>" . $dsarr['nkai'] . "</td>";
                                                            } else {
                                                                echo "<td style='cursor:pointer' onmouseover=\"openDevicesn('" . $datearr[$i] . "','" . $value['FACTORY'] . "','" . $value['SYSTEM'] . "','" . $value['TYPE'] . "','N','nkai','" . $dsarr['NAME'] . "','" . $dsarr['UNIT'] . "')\" onmouseout=\"closeDevicesn()\">" . $dsarr['nkai'] . "</td>";
                                                            }
                                                            if ($dsarr['ndai'] == '0') {
                                                                echo "<td>" . $dsarr['ndai'] . "</td>";
                                                            } else {
                                                                echo "<td style='cursor:pointer' onmouseover=\"openDevicesn('" . $datearr[$i] . "','" . $value['FACTORY'] . "','" . $value['SYSTEM'] . "','" . $value['TYPE'] . "','N','ndai','" . $dsarr['NAME'] . "','" . $dsarr['UNIT'] . "')\" onmouseout=\"closeDevicesn(this)\">" . $dsarr['ndai'] . "</td>";
                                                            }
                                                            if ($dsarr['ngu'] == '0') {
                                                                echo "<td>" . $dsarr['ngu'] . "</td>";
                                                            } else {
                                                                echo "<td style='cursor:pointer' onmouseover=\"openDevicesn('" . $datearr[$i] . "','" . $value['FACTORY'] . "','" . $value['SYSTEM'] . "','" . $value['TYPE'] . "','N','ngu','" . $dsarr['NAME'] . "','" . $dsarr['UNIT'] . "')\" onmouseout=\"closeDevicesn(this)\">" . $dsarr['ngu'] . "</td>";
                                                            }
                                                        }
                                                        $update++;
                                                    }
                                                    echo "</tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="no-margin-top" align="center">
                                        <form class="form-inline" method="post" action="deviceDataSummary.php?startdate=<?= $startdate ?>&enddate=<?= $enddate ?>&factorystr=<?= $factorystr ?>&systemstr=<?= $systemstr ?>&typestr=<?= $typestr ?>" style="margin-bottom: -20px">
                                            <div class="form-group">
                                                <nav aria-label="Page navigation">
                                                    <ul class="pagination">
                                                        <li>
                                                            <a href="deviceDataSummary.php?p=<?= $firstpage ?>&startdate=<?= $startdate ?>&enddate=<?= $enddate ?>&factorystr=<?= $factorystr ?>&systemstr=<?= $systemstr ?>&typestr=<?= $typestr ?>">
                                                                <span class="icon-fast-backward"></span> 首頁
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="deviceDataSummary.php?p=<?= $prepage ?>&startdate=<?= $startdate ?>&enddate=<?= $enddate ?>&factorystr=<?= $factorystr ?>&systemstr=<?= $systemstr ?>&typestr=<?= $typestr ?>">
                                                                <span class="icon-step-backward"></span> 上一頁
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="deviceDataSummary.php?p=<?= $nextpage ?>&startdate=<?= $startdate ?>&enddate=<?= $enddate ?>&factorystr=<?= $factorystr ?>&systemstr=<?= $systemstr ?>&typestr=<?= $typestr ?>">
                                                                下一頁 <span class="icon-step-forward"></span> 
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="deviceDataSummary.php?p=<?= $lastpage ?>&startdate=<?= $startdate ?>&enddate=<?= $enddate ?>&factorystr=<?= $factorystr ?>&systemstr=<?= $systemstr ?>&typestr=<?= $typestr ?>">
                                                                末頁 <span class="icon-fast-forward"></span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </nav>
                                            </div>
                                            <span style="font-size:16px;font-weight: bold;">當前頁:<?= $nowpage ?>/<?= $lastpage ?>:最後一頁</span>
                                            <input type="text" name="p" class="form-control" style="width: 50px;margin-left: 50px;">
                                            <button type="submit" class="btn btn-primary btn-sm" onclick="if (new RegExp('^[0-9]{1,}$').test(this.form.p.value))
                                                        this.form.submit();">跳轉</button>
                                        </form>
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
    echo "<script>window.top.location.href='pilotList_product.php'</script>";
}
?>