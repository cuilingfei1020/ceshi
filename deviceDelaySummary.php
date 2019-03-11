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
if ($_COOKIE['cdepartment'] == 'works') {
    ?>
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <meta http-equiv="pragma" content="no-cache"/>
            <meta http-equiv="Cache-Control" content="no-cache, no-store"/>
            <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
            <title>Device Maintian Delay</title>
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
                                                <form class="form-inline" method="post" action="deviceDelaySummary.php">
                                                    <label>廠別：</label>
                                                    <div class="form-group" style="width:200px;margin-right: 10px;">
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
                                                    <button class="btn btn-primary btn-sm" type="submit"><i class="icon-search"></i>查詢</button>
                                                    <button class="btn btn-info btn-sm" type="button" onclick="window.location.href = 'deviceDelayChart.php?factorystr=<?= $factorystr ?>&systemstr=<?= $systemstr ?>&typestr=<?= $typestr ?>'"><span class="icon-bar-chart"></span>圖形</button>
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
                                                    <td colspan="5">未保養</td>
                                                    <td colspan="5">保養中</td>
                                                </tr>
                                                <tr>
                                                    <td>0≤N≤3</td>
                                                    <td>3&lt;N≤7</td>
                                                    <td>7&lt;N≤15</td>
                                                    <td>15&lt;N≤30</td>
                                                    <td>N&gt;30</td>
                                                    <td>0≤N≤3</td>
                                                    <td>3&lt;N≤7</td>
                                                    <td>7&lt;N≤15</td>
                                                    <td>15&lt;N≤30</td>
                                                    <td>N&gt;30</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $p = $p == '' ? 1 : $p;
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
                                                    $str = $str . " WHERE dd.`STATUS` IS NOT NULL AND dd.FACTORY in ('" . $userfactorystr . "') GROUP BY dd.FACTORY,dd.SYSTEM,dd.TYPE";
                                                } else if ($factorystr != '' && $systemstr == '' && $typestr == '') {
                                                    $str = $str . " WHERE dd.`STATUS` IS NOT NULL AND dd.FACTORY in (" . get($factorystr) . ") GROUP BY dd.FACTORY,dd.SYSTEM,dd.TYPE";
                                                } else if ($factorystr != '' && $systemstr != '' && $typestr == '') {
                                                    $str = $str . " WHERE dd.`STATUS` IS NOT NULL AND dd.FACTORY in (" . get($factorystr) . ") AND dd.SYSTEM in (" . get($systemstr) . ") GROUP BY dd.FACTORY,dd.SYSTEM,dd.TYPE";
                                                } else {
                                                    $str = $str . " WHERE dd.`STATUS` IS NOT NULL AND dd.FACTORY in (" . get($factorystr) . ") AND dd.SYSTEM in (" . get($systemstr) . ") AND dd.TYPE in (" . get($typestr) . ") GROUP BY dd.FACTORY,dd.SYSTEM,dd.TYPE";
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
                                                        echo "<td><a href=\"deviceDelayNum.php?factorystr=" . $factorystr . "&systemstr=" . $systemstr . "&typestr=" . $typestr . "&factory=" . $value['FACTORY'] . "&system=" . $value['SYSTEM'] . "&type=" . $value['TYPE'] . "&num=TOTAL\">" . $value['TOTAL'] . "</a></td>";
                                                        echo $value['N0~3'] == '0' ? "<td>" . $value['N0~3'] . "</td>" : "<td><a href=\"deviceDelayNum.php?factorystr=" . $factorystr . "&systemstr=" . $systemstr . "&typestr=" . $typestr . "&factory=" . $value['FACTORY'] . "&system=" . $value['SYSTEM'] . "&type=" . $value['TYPE'] . "&num=N0~3\">" . $value['N0~3'] . "</a></td>";
                                                        echo $value['N3~7'] == '0' ? "<td>" . $value['N3~7'] . "</td>" : "<td><a href=\"deviceDelayNum.php?factorystr=" . $factorystr . "&systemstr=" . $systemstr . "&typestr=" . $typestr . "&factory=" . $value['FACTORY'] . "&system=" . $value['SYSTEM'] . "&type=" . $value['TYPE'] . "&num=N3~7\">" . $value['N3~7'] . "</a></td>";
                                                        echo $value['N7~15'] == '0' ? "<td>" . $value['N7~15'] . "</td>" : "<td><a href=\"deviceDelayNum.php?factorystr=" . $factorystr . "&systemstr=" . $systemstr . "&typestr=" . $typestr . "&factory=" . $value['FACTORY'] . "&system=" . $value['SYSTEM'] . "&type=" . $value['TYPE'] . "&num=N7~15\">" . $value['N7~15'] . "</a></td>";
                                                        echo $value['N15~30'] == '0' ? "<td>" . $value['N15~30'] . "</td>" : "<td><a href=\"deviceDelayNum.php?factorystr=" . $factorystr . "&systemstr=" . $systemstr . "&typestr=" . $typestr . "&factory=" . $value['FACTORY'] . "&system=" . $value['SYSTEM'] . "&type=" . $value['TYPE'] . "&num=N15~30\">" . $value['N15~30'] . "</a></td>";
                                                        echo $value['N>30'] == '0' ? "<td>" . $value['N>30'] . "</td>" : "<td><a href=\"deviceDelayNum.php?factorystr=" . $factorystr . "&systemstr=" . $systemstr . "&typestr=" . $typestr . "&factory=" . $value['FACTORY'] . "&system=" . $value['SYSTEM'] . "&type=" . $value['TYPE'] . "&num=N>30\">" . $value['N>30'] . "</a></td>";
                                                        echo $value['D0~3'] == '0' ? "<td>" . $value['D0~3'] . "</td>" : "<td><a href=\"deviceDelayNum.php?factorystr=" . $factorystr . "&systemstr=" . $systemstr . "&typestr=" . $typestr . "&factory=" . $value['FACTORY'] . "&system=" . $value['SYSTEM'] . "&type=" . $value['TYPE'] . "&num=D0~3\">" . $value['D0~3'] . "</a></td>";
                                                        echo $value['D3~7'] == '0' ? "<td>" . $value['D3~7'] . "</td>" : "<td><a href=\"deviceDelayNum.php?factorystr=" . $factorystr . "&systemstr=" . $systemstr . "&typestr=" . $typestr . "&factory=" . $value['FACTORY'] . "&system=" . $value['SYSTEM'] . "&type=" . $value['TYPE'] . "&num=D3~7\">" . $value['D3~7'] . "</a></td>";
                                                        echo $value['D7~15'] == '0' ? "<td>" . $value['D7~15'] . "</td>" : "<td><a href=\"deviceDelayNum.php?factorystr=" . $factorystr . "&systemstr=" . $systemstr . "&typestr=" . $typestr . "&factory=" . $value['FACTORY'] . "&system=" . $value['SYSTEM'] . "&type=" . $value['TYPE'] . "&num=D7~15\">" . $value['D7~15'] . "</a></td>";
                                                        echo $value['D15~30'] == '0' ? "<td>" . $value['D15~30'] . "</td>" : "<td><a href=\"deviceDelayNum.php?factorystr=" . $factorystr . "&systemstr=" . $systemstr . "&typestr=" . $typestr . "&factory=" . $value['FACTORY'] . "&system=" . $value['SYSTEM'] . "&type=" . $value['TYPE'] . "&num=D15~30\">" . $value['D15~30'] . "</a></td>";
                                                        echo $value['D>30'] == '0' ? "<td>" . $value['D>30'] . "</td>" : "<td><a href=\"deviceDelayNum.php?factorystr=" . $factorystr . "&systemstr=" . $systemstr . "&typestr=" . $typestr . "&factory=" . $value['FACTORY'] . "&system=" . $value['SYSTEM'] . "&type=" . $value['TYPE'] . "&num=D>30\">" . $value['D>30'] . "</a></td>";
                                                        echo "</tr>";
                                                        $update++;
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="no-margin-top" align="center">

                                        <form class="form-inline" method="post" action="deviceDelaySummary.php?factorystr=<?= $factorystr ?>&systemstr=<?= $systemstr ?>&typestr=<?= $typestr ?>">
                                            <div class="form-group">
                                                <nav aria-label="Page navigation">
                                                    <ul class="pagination">
                                                        <li>
                                                            <a href="deviceDelaySummary.php?p=<?= $firstpage ?>&factorystr=<?= $factorystr ?>&systemstr=<?= $systemstr ?>&typestr=<?= $typestr ?>">
                                                                <span class="icon-fast-backward"></span> 首頁
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="deviceDelaySummary.php?p=<?= $prepage ?>&factorystr=<?= $factorystr ?>&systemstr=<?= $systemstr ?>&typestr=<?= $typestr ?>">
                                                                <span class="icon-step-backward"></span> 上一頁
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="deviceDelaySummary.php?p=<?= $nextpage ?>&factorystr=<?= $factorystr ?>&systemstr=<?= $systemstr ?>&typestr=<?= $typestr ?>">
                                                                下一頁 <span class="icon-step-forward"></span> 
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="deviceDelaySummary.php?p=<?= $lastpage ?>&factorystr=<?= $factorystr ?>&systemstr=<?= $systemstr ?>&typestr=<?= $typestr ?>">
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
            </div>
        </body>
    </html>
    <?php
}
?>