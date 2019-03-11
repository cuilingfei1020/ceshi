<?php header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate'); ?>
<?php header('content-Type: text/html; charset=UTF-8'); ?>
<?php @ require_once 'database/database.php'; ?>
<?php @ require_once 'function/checkCookie.php'; ?>
<?php @ require_once 'function/functions.php'; ?>
<?php
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
                    $("#deviceDelayHistory").addClass("active");
                    $("#deviceDelay").addClass("active open");
                    $("#maintain").addClass("active open");
                })
                function btnShow(startdate, enddate, factorystr, systemstr, typestr, factory, system, type, num, device_sn, content, idx, states) {
                    window.parent.location.href = 'deviceMaintainFill.php?startdate=' + startdate + '&enddate=' + enddate + '&factorystr=' + factorystr + '&systemstr=' + systemstr + '&typestr=' + typestr + '&factory=' + factory + '&system=' + system + '&type=' + type + '&num=' + num + '&device_sn=' + device_sn + '&content=' + content + '&idx=' + idx + '&states=' + states;//iframe子頁面跳轉父頁面
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
                                        未結單
                                    </small>
                                </h1>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="table-responsive">

                                                <button type="button" class="btn btn-primary" onclick="window.location.href = 'deviceDelayHistory.php?startdate=<?= $startdate ?>&enddate=<?= $enddate ?>&factorystr=<?= $factorystr ?>&systemstr=<?= $systemstr ?>&typestr=<?= $typestr ?>'">
                                                    <span class="icon-reply" style="font-size:16px;"></span> 返回上一頁
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="table-responsive" style="margin-top:10px;">
                                        <table id="table" class=" nowrap table table-striped table-bordered table-hover" cellspacing="0" style="word-break: keep-all;white-space:nowrap;width:100%;">
                                            <thead style="background: #f0f0f0">
                                                <tr>
                                                    <td>位置</td>
                                                    <td>系統類別</td>
                                                    <td>設備類別</td>
                                                    <td>設備名稱</td>
                                                    <td>設備SN</td>
                                                    <td>保養內容</td>
                                                    <td>本應開始日期</td>
                                                    <td>實際開始日期</td>
                                                    <td>開始人</td>
                                                    <td>保養狀態</td>
                                                    <td>保養結束日期</td>
                                                    <td>結束人</td>
                                                    <td>延遲天數</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $p = $p == '' ? 1 : $p;
                                                if ($num == 'TOTAL') {
                                                    $term = "";
                                                } else {
                                                    $term = " AND aa.`STATES`='" . $num . "'";
                                                }
                                                $str = "SELECT * FROM
                                                            (SELECT FACTORY,FLOOR,AREA,ROOM,SYSTEM,TYPE,DEVICE_NAME,DEVICE_SN,CONTENT,SHOULD_DATE,DATE,OP_START,`STATUS`,END_DATE,OP_END,DELAY_DAY,
                                                                CASE WHEN '0' <=DELAY_DAY AND DELAY_DAY <='3' THEN '0~3'
                                                                    WHEN '3' <DELAY_DAY AND DELAY_DAY <='7' THEN '3~7'
                                                                    WHEN '7' <DELAY_DAY AND DELAY_DAY <='15' THEN '7~15'
                                                                    WHEN '15' <DELAY_DAY AND DELAY_DAY <='30' THEN '15~30'
                                                                    WHEN DELAY_DAY >'30' THEN '>30' END `STATES`,IDX FROM maintain_detail_works
                                                            WHERE SHOULD_DATE BETWEEN '" . $startdate . "' AND '" . $enddate . "' AND DELAY_DAY >='0' AND `STATUS` IN ('OK','NG'))aa
                                                        WHERE aa.FACTORY='" . $factory . "' AND aa.SYSTEM='" . $system . "' AND aa.TYPE='" . $type . "'".$term;
                                                $total = getQueryCount($pdo, $str);
                                                if ($total > 0) {
                                                    $perpage = 13;
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
                                                    $device = $pdo->query($str);
                                                    $devicearr = $device->fetchAll();
                                                    foreach ($devicearr as $value) {
                                                        ?>
                                                        <tr>
                                                            <td><?= $value['FACTORY'] . '_' . $value['FLOOR'] . '_' . $value['AREA'] . '_' . $value['ROOM'] ?></td>
                                                    <td><?= getNamech($pdo, $system) ?></td>
                                                    <td><?= getNamech($pdo, $type) ?></td>
                                                    <td><?= $value['DEVICE_NAME'] ?></td>
                                                    <td><?= $value['DEVICE_SN'] ?></td>
                                                    <td class="mcon"><a href=# title="<?= $value['CONTENT'] ?>"><?= $value['CONTENT'] ?></a></td>
                                                    <td><?= $value['SHOULD_DATE'] ?></td>
                                                    <td><?= $value['DATE'] ?></td>
                                                    <td><?= $value['OP_START'] ?></td>
                                        <td>
                                                    <?php
                                                    if ($value['STATUS'] == 'OK') {
                                                        echo "<button class = 'btn btn-success btn-xs' style='margin-bottom:-10px;margin-top:-13px' onclick=\"btnShow('" . $startdate . "','" . $enddate . "','" . $factorystr . "','" . $systemstr . "','" . $typestr . "','" . $factory . "','" . $system . "','" . $type . "','" . $num . "','" . $value['DEVICE_SN'] . "','" . urlencode($value['CONTENT']) . "','" . $value['IDX'] . "','delayhistory')\">OK</button>";
                                                    } else {
                                                        echo "<button class='btn btn-warning btn-xs' style='margin-bottom:-10px;margin-top:-13px' onclick=\"btnShow('" . $startdate . "','" . $enddate . "','" . $factorystr . "','" . $systemstr . "','" . $typestr . "','" . $factory . "','" . $system . "','" . $type . "','" . $num . "','" . $value['DEVICE_SN'] . "','" . urlencode($value['CONTENT']) . "','" . $value['IDX'] . "','delayhistory')\">NG</button>";
                                                    }
                                                    ?>
                                        </td>
                                                    <td><?= $value['END_DATE'] ?></td>
                                                    <td><?= $value['OP_END'] ?></td>
                                                    <td><?= $value['DELAY_DAY'] ?></td>
                                                    </tr>
                                                    <?php
                                                    $update++;
                                                }
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="no-margin-top" align="center">
                                        <form class="form-inline" method="post" action="deviceDelayHistoryNum.php?startdate=<?= $startdate ?>&enddate=<?= $enddate ?>&factorystr=<?= $factorystr ?>&systemstr=<?= $systemstr ?>&typestr=<?= $typestr ?>&factory=<?= $factory ?>&system=<?= $system ?>&type=<?= $type ?>&num=<?= $num ?>">
                                            <div class="form-group">
                                                <nav aria-label="Page navigation">
                                                    <ul class="pagination">
                                                        <li>
                                                            <a href="deviceDelayHistoryNum.php?p=<?= $firstpage ?>&startdate=<?= $startdate ?>&enddate=<?= $enddate ?>&factorystr=<?= $factorystr ?>&systemstr=<?= $systemstr ?>&typestr=<?= $typestr ?>&factory=<?= $factory ?>&system=<?= $system ?>&type=<?= $type ?>&num=<?= $num ?>">
                                                                <span class="icon-fast-backward"></span> 首頁
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="deviceDelayHistoryNum.php?p=<?= $prepage ?>&startdate=<?= $startdate ?>&enddate=<?= $enddate ?>&factorystr=<?= $factorystr ?>&systemstr=<?= $systemstr ?>&typestr=<?= $typestr ?>&factory=<?= $factory ?>&system=<?= $system ?>&type=<?= $type ?>&num=<?= $num ?>">
                                                                <span class="icon-step-backward"></span> 上一頁
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="deviceDelayHistoryNum.php?p=<?= $nextpage ?>&startdate=<?= $startdate ?>&enddate=<?= $enddate ?>&factorystr=<?= $factorystr ?>&systemstr=<?= $systemstr ?>&typestr=<?= $typestr ?>&factory=<?= $factory ?>&system=<?= $system ?>&type=<?= $type ?>&num=<?= $num ?>">
                                                                下一頁 <span class="icon-step-forward"></span> 
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="deviceDelayHistoryNum.php?p=<?= $lastpage ?>&startdate=<?= $startdate ?>&enddate=<?= $enddate ?>&factorystr=<?= $factorystr ?>&systemstr=<?= $systemstr ?>&typestr=<?= $typestr ?>&factory=<?= $factory ?>&system=<?= $system ?>&type=<?= $type ?>&num=<?= $num ?>">
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
}
?>