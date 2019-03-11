<?php header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate'); ?>
<?php header('content-Type: text/html; charset=UTF-8'); ?>
<?php @ require_once 'database/database.php'; ?>
<?php @ require_once 'function/checkCookie.php'; ?>
<?php @ require_once 'function/functions.php'; ?>
<?php
$userfactorystr = $_COOKIE['cuserfactory'];
$userfactoryarr=explode("','",$userfactorystr);
if ($startdate == '' && $enddate == '') {
    date_default_timezone_set('PRC');
    $startdate = date('Y-m-d', strtotime('- 29 days'));
    $enddate = date('Y-m-d', time());
}
$factory = isset($factorystr) == '1' ? '' : $factory;
$factorystr = $factory == '' ? $factorystr : implode(',', $factory);
$system = isset($systemstr) == '1' ? '' : $system;
$systemstr = $system == '' ? $systemstr : implode(',', $system);
$type = isset($typestr) == '1' ? '' : $type;
$typestr = $type == '' ? $typestr : implode(',', $type);
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

                    $("#deviceDelayHistory").addClass("active");
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

                function check() {
                    if ($("#starttime").val() != '' && $("#endtime").val() == '') {
                        alert("請選擇結束時間！");
                        $("#endtime").focus();
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
                    var aa = 2505600000;
                    if (end - start > aa) {
                        alert("時間範圍不能超過三十天！");
                        $("#endtime").val('');
                        $("#endtime").focus();
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
                                    <i class="icon-briefcase"></i> 設備保養
                                    <small>
                                        <i class="icon-double-angle-right"></i>
                                        保養結單
                                    </small>
                                    <small>
                                        <i class="icon-double-angle-right"></i>
                                        歷史結單
                                    </small>
                                </h1>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="table-responsive">
                                                <form method="post" action="deviceDelayHistory.php">
                                                    <div class="form-inline">
                                                        <label>開始日期：</label>
                                                        <div class="form-group" style="width:200px;margin-right: 20px;">
                                                            <input type="text" class="Wdate form-control" onFocus="WdatePicker({dateFmt: 'yyyy-MM-dd'})" id="startdate" name="startdate" value="<?= $startdate ?>">
                                                        </div>
                                                        <label>結束日期：</label>
                                                        <div class="form-group" style="width:200px;margin-right: 20px;">
                                                            <input type="text" class="Wdate form-control" onFocus="WdatePicker({dateFmt: 'yyyy-MM-dd'})" id="enddate" name="enddate" value="<?= $enddate ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-inline" style="margin-top: 10px;">
                                                        <label>廠別：</label>
                                                        <div class="form-group" style="width:200px;margin-left: 28px;margin-right: 20px;">
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
                                                        <div class="form-group" style="width:200px;margin-right: 20px;">
                                                            <select id="system" class="form-control selectpicker " multiple data-done-button="true" name="system[]" onchange="changeType()">
                                                            </select>
                                                        </div>
                                                        <label>設備類別：</label>
                                                        <div class="form-group" style="width:200px;margin-right: 20px;">
                                                            <select id="type" class="form-control selectpicker " multiple data-done-button="true" name="type[]">
                                                            </select>
                                                        </div>
                                                        <button class="btn btn-primary btn-sm" type="submit" onclick="return check();"><i class="icon-search"></i>查詢</button>
                                                        <button class="btn btn-white" type="button" onclick="window.location.href = 'function/download.php?action=delay&startdate=<?= $startdate ?>&enddate=<?= $enddate ?>&factorystr=<?= $factorystr ?>&systemstr=<?= $systemstr ?>&typestr=<?= $typestr ?>'">
                                                            <span class="icon-download"></span>下載
                                                        </button>
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
                                            <thead style="background: #f0f0f0">
                                                <tr>
                                                    <td rowspan="2" style="line-height: 50px">廠別</td>
                                                    <td rowspan="2" style="line-height: 50px">系統類別</td>
                                                    <td rowspan="2" style="line-height: 50px">設備類別</td>
                                                    <td rowspan="2" style="line-height: 50px">項目總計</td>
                                                    <td colspan="2">0≤N≤3</td>
                                                    <td colspan="2">3&lt;N≤7</td>
                                                    <td colspan="2">7&lt;N≤15</td>
                                                    <td colspan="2">15&lt;N≤30</td>
                                                    <td colspan="2">N&gt;30</td>
                                                <tr>
                                                    <td>數量</td>
                                                    <td>比例</td>
                                                    <td>數量</td>
                                                    <td>比例</td>
                                                    <td>數量</td>
                                                    <td>比例</td>
                                                    <td>數量</td>
                                                    <td>比例</td>
                                                    <td>數量</td>
                                                    <td>比例</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $p = $p == '' ? 1 : $p;
                                                $str = "SELECT aa.FACTORY,aa.SYSTEM,aa.TYPE,count(aa.CONTENT) AS TOTAL,
                                                            SUM(IF(aa.`STATUS`='0~3','1','0')) AS `0~3`, 
                                                            SUM(IF(aa.`STATUS`='3~7','1','0')) AS `3~7`, 
                                                            SUM(IF(aa.`STATUS`='7~15','1','0')) AS `7~15`, 
                                                            SUM(IF(aa.`STATUS`='15~30','1','0')) AS `15~30`, 
                                                            SUM(IF(aa.`STATUS`='>30','1','0')) AS `>30` FROM
                                                                (SELECT FACTORY,SYSTEM,TYPE,DEVICE_SN,CONTENT,DELAY_DAY,
                                                                        CASE WHEN '0' <=DELAY_DAY AND DELAY_DAY <='3' THEN '0~3'
                                                                            WHEN '3' <DELAY_DAY AND DELAY_DAY <='7' THEN '3~7'
                                                                            WHEN '7' <DELAY_DAY AND DELAY_DAY <='15' THEN '7~15'
                                                                            WHEN '15' <DELAY_DAY AND DELAY_DAY <='30' THEN '15~30'
                                                                            WHEN DELAY_DAY >'30' THEN '>30' END `STATUS` FROM maintain_detail_works
                                                        WHERE SHOULD_DATE BETWEEN '" . $startdate . "' AND '" . $enddate . "' AND DELAY_DAY >='0' AND `STATUS` IN ('OK','NG'))aa";
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
                                                    $perpage = 12;
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
                                                        echo "<td><a href=\"deviceDelayHistoryNum.php?startdate=" . $startdate . "&enddate=" . $enddate . "&factorystr=" . $factorystr . "&systemstr=" . $systemstr . "&typestr=" . $typestr . "&factory=" . $value['FACTORY'] . "&system=" . $value['SYSTEM'] . "&type=" . $value['TYPE'] . "&num=TOTAL\">" . $value['TOTAL'] . "</a></td>";
                                                        echo $value['0~3'] == '0' ? "<td>" . $value['0~3'] . "</td>" : "<td><a href=\"deviceDelayHistoryNum.php?startdate=" . $startdate . "&enddate=" . $enddate . "&factorystr=" . $factorystr . "&systemstr=" . $systemstr . "&typestr=" . $typestr . "&factory=" . $value['FACTORY'] . "&system=" . $value['SYSTEM'] . "&type=" . $value['TYPE'] . "&num=0~3\">" . $value['0~3'] . "</a></td>";
                                                        echo "<td>" . sprintf("%.2f", $value['0~3'] / $value['TOTAL'] * 100) . "%</td>";
                                                        echo $value['3~7'] == '0' ? "<td>" . $value['3~7'] . "</td>" : "<td><a href=\"deviceDelayHistoryNum.php?startdate=" . $startdate . "&enddate=" . $enddate . "&factorystr=" . $factorystr . "&systemstr=" . $systemstr . "&typestr=" . $typestr . "&factory=" . $value['FACTORY'] . "&system=" . $value['SYSTEM'] . "&type=" . $value['TYPE'] . "&num=3~7\">" . $value['3~7'] . "</a></td>";
                                                        echo "<td>" . sprintf("%.2f", $value['3~7'] / $value['TOTAL'] * 100) . "%</td>";
                                                        echo $value['7~15'] == '0' ? "<td>" . $value['7~15'] . "</td>" : "<td><a href=\"deviceDelayHistoryNum.php?startdate=" . $startdate . "&enddate=" . $enddate . "&factorystr=" . $factorystr . "&systemstr=" . $systemstr . "&typestr=" . $typestr . "&factory=" . $value['FACTORY'] . "&system=" . $value['SYSTEM'] . "&type=" . $value['TYPE'] . "&num=7~15\">" . $value['7~15'] . "</a></td>";
                                                        echo "<td>" . sprintf("%.2f", $value['7~15'] / $value['TOTAL'] * 100) . "%</td>";
                                                        echo $value['15~30'] == '0' ? "<td>" . $value['15~30'] . "</td>" : "<td><a href=\"deviceDelayHistoryNum.php?startdate=" . $startdate . "&enddate=" . $enddate . "&factorystr=" . $factorystr . "&systemstr=" . $systemstr . "&typestr=" . $typestr . "&factory=" . $value['FACTORY'] . "&system=" . $value['SYSTEM'] . "&type=" . $value['TYPE'] . "&num=15~30\">" . $value['15~30'] . "</a></td>";
                                                        echo "<td>" . sprintf("%.2f", $value['15~30'] / $value['TOTAL'] * 100) . "%</td>";
                                                        echo $value['>30'] == '0' ? "<td>" . $value['>30'] . "</td>" : "<td><a href=\"deviceDelayHistoryNum.php?startdate=" . $startdate . "&enddate=" . $enddate . "&factorystr=" . $factorystr . "&systemstr=" . $systemstr . "&typestr=" . $typestr . "&factory=" . $value['FACTORY'] . "&system=" . $value['SYSTEM'] . "&type=" . $value['TYPE'] . "&num=>30\">" . $value['>30'] . "</a></td>";
                                                        echo "<td>" . sprintf("%.2f", $value['>30'] / $value['TOTAL'] * 100) . "%</td>";
                                                        echo "</tr>";
                                                        $update++;
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="no-margin-top" align="center">
                                        <form class="form-inline" method="post" action="deviceDelayHistory.php?startdate=<?= $startdate ?>&enddate=<?= $enddate ?>&factorystr=<?= $factorystr ?>&systemstr=<?= $systemstr ?>&typestr=<?= $typestr ?>">
                                            <div class="form-group">
                                                <nav aria-label="Page navigation">
                                                    <ul class="pagination">
                                                        <li>
                                                            <a href="deviceDelayHistory.php?p=<?= $firstpage ?>&startdate=<?= $startdate ?>&enddate=<?= $enddate ?>&factorystr=<?= $factorystr ?>&systemstr=<?= $systemstr ?>&typestr=<?= $typestr ?>">
                                                                <span class="icon-fast-backward"></span> 首頁
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="deviceDelayHistory.php?p=<?= $prepage ?>&startdate=<?= $startdate ?>&enddate=<?= $enddate ?>&factorystr=<?= $factorystr ?>&systemstr=<?= $systemstr ?>&typestr=<?= $typestr ?>">
                                                                <span class="icon-step-backward"></span> 上一頁
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="deviceDelayHistory.php?p=<?= $nextpage ?>&startdate=<?= $startdate ?>&enddate=<?= $enddate ?>&factorystr=<?= $factorystr ?>&systemstr=<?= $systemstr ?>&typestr=<?= $typestr ?>">
                                                                下一頁 <span class="icon-step-forward"></span> 
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="deviceDelayHistory.php?p=<?= $lastpage ?>&startdate=<?= $startdate ?>&enddate=<?= $enddate ?>&factorystr=<?= $factorystr ?>&systemstr=<?= $systemstr ?>&typestr=<?= $typestr ?>">
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