<?php header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate'); ?>
<?php header('content-Type: text/html; charset=UTF-8'); ?>
<?php @ require_once 'database/database.php'; ?>
<?php @ require_once 'function/checkCookie.php'; ?>
<?php @ require_once 'function/functions.php'; ?>
<?php
date_default_timezone_set('PRC');
$nowdate = date('Y-m-d');
if ($states == 'summary') {
    $url = "deviceMaintainNUM.php?factorystr=" . $factorystr . "&systemstr=" . $systemstr . "&typestr=" . $typestr . "&factory=" . $factory . "&system=" . $system . "&type=" . $type . "&num=" . $num;
} else if ($states == 'list') {
    $url = "deviceMaintainList.php?startdate=" . $startdate . "&enddate=" . $enddate . "&factorystr=" . $factorystr . "&systemstr=" . $systemstr . "&typestr=" . $typestr . "&status=" . $num;
} else if ($states == 'history') {
    $url = "deviceMaintainHistory.php?startdate=" . $startdate . "&enddate=" . $enddate . "&factorystr=" . $factorystr . "&systemstr=" . $systemstr . "&typestr=" . $typestr;
} else if ($states == 'device') {
    $url = "deviceList.php?startdate=" . $startdate . "&enddate=" . $enddate . "&factorystr=" . $factorystr . "&systemstr=" . $systemstr . "&typestr=" . $typestr;
} else if ($states == 'delay') {
    $url = "deviceDelayNum.php?factorystr=" . $factorystr . "&systemstr=" . $systemstr . "&typestr=" . $typestr . "&factory=" . $factory . "&system=" . $system . "&type=" . $type . "&num=" . $num;
}
if (!isset($content)) {
    $con = "";
} else {
    $con = " AND CONTENT='" . $content . "'";
}
//保養數據
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
            <link rel="stylesheet" type="text/css" href="css/img.css" />
            <link rel="stylesheet" type="text/css" href="css/lightbox.css" />
            <script src="js/jquery-2.0.3.min.js"></script>
            <script type="text/javascript" src="My97DatePicker/WdatePicker.js"></script>
            <script language="javascript" src="layer/layer.min.js"></script>
            <script src="js/jquery.dataTables.min.js"></script>
            <script src="js/jquery.dataTables.bootstrap.js"></script>
            <script src="js/bootstrap.select.js"></script>
            <script src="js/function.js"></script>
            <script language="javascript" src="js/imgUp.js"></script>
            <script language="javascript" src="js/lightbox-2.6.min.js"></script>
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
                    $("#status option[value='<?= $status ?>']").attr("selected", true);

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
                                    <i class="icon-briefcase"></i> 保養
                                    <small>
                                        <i class="icon-double-angle-right"></i>
                                        設備保養
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
                                                <button type="button" class="btn btn-primary" onclick="window.location.href = '<?= $url ?>'">
                                                    <span class="icon-reply" style="font-size:16px;"></span> 返回上一頁
                                                </button>
                                                <!--                                                <button type="button" class="btn btn-info" id="log">
                                                                                                    <span class="icon-edit">保養日誌</span>
                                                                                                </button>
                                                                                                <button id="down" class="btn btn-white" type="button" onclick="window.location.href = 'function/download.php?action=log&flag=maintain&idx=<?= $darr['IDX'] ?>'">
                                                                                                    <span class="icon-download"></span>下載
                                                                                                </button>-->
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
                                                    <td>位置</td>
                                                    <td>系統類別</td>
                                                    <td>設備類別</td>
                                                    <td>設備名稱</td>
                                                    <td>設備SN</td>
                                                    <td>保養項目</td>
                                                    <td>日期</td>
                                                    <td>延遲原因</td>
                                                    <td>課長工號</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $p = $p == '' ? 1 : $p;
                                                $str = "SELECT * FROM maintain_delay_works WHERE DEVICE_SN='" . $device_sn . "'" . $con . " ORDER BY CREAT_DATE";
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
                                                        $position = $value['FACTORY'] . "_" . $value['FLOOR'] . "_" . $value['AREA'] . "_" . $value['ROOM'];
                                                        ?>
                                                        <tr>
                                                            <td><?= $position ?></td>
                                                            <td><?= $value['SYSTEM'] ?></td>
                                                            <td><?= $value['TYPE'] ?></td>
                                                            <td><?= $value['DEVICE_NAME'] ?></td>
                                                            <td><?= $value['DEVICE_SN'] ?></td>
                                                            <td><?= $value['CONTENT'] ?></td>
                                                            <td><?= $value['CREAT_DATE'] ?></td>
                                                            <td><?= $value['DELAY_REASON'] ?></td>
                                                            <td><?= $value['DELAY_ID'] ?></td>
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
                                        <form class="form-inline" method="post" action="deviceDelayDetail.php?startdate=<?= $startdate ?>&enddate=<?= $enddate ?>&factorystr=<?= $factorystr ?>&systemstr=<?= $systemstr ?>&typestr=<?= $typestr ?>&factory=<?= $factory ?>&system=<?= $system ?>&type=<?= $type ?>&num=<?= $num ?>&device_sn=<?=$device_sn?>&content=<?=$content?>&states=<?= $states ?>">
                                            <div class="form-group">
                                                <nav aria-label="Page navigation">
                                                    <ul class="pagination">
                                                        <li>
                                                            <a href="deviceDelayDetail.php?p=<?= $firstpage ?>&startdate=<?= $startdate ?>&enddate=<?= $enddate ?>&factorystr=<?= $factorystr ?>&systemstr=<?= $systemstr ?>&typestr=<?= $typestr ?>&factory=<?= $factory ?>&system=<?= $system ?>&type=<?= $type ?>&num=<?= $num ?>&device_sn=<?=$device_sn?>&content=<?=$content?>&states=<?= $states ?>">
                                                                <span class="icon-fast-backward"></span> 首頁
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="deviceDelayDetail.php?p=<?= $prepage ?>&startdate=<?= $startdate ?>&enddate=<?= $enddate ?>&factorystr=<?= $factorystr ?>&systemstr=<?= $systemstr ?>&typestr=<?= $typestr ?>&factory=<?= $factory ?>&system=<?= $system ?>&type=<?= $type ?>&num=<?= $num ?>&device_sn=<?=$device_sn?>&content=<?=$content?>&states=<?= $states ?>">
                                                                <span class="icon-step-backward"></span> 上一頁
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="deviceDelayDetail.php?p=<?= $nextpage ?>&startdate=<?= $startdate ?>&enddate=<?= $enddate ?>&factorystr=<?= $factorystr ?>&systemstr=<?= $systemstr ?>&typestr=<?= $typestr ?>&factory=<?= $factory ?>&system=<?= $system ?>&type=<?= $type ?>&num=<?= $num ?>&device_sn=<?=$device_sn?>&content=<?=$content?>&states=<?= $states ?>">
                                                                下一頁 <span class="icon-step-forward"></span> 
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="deviceDelayDetail.php?p=<?= $lastpage ?>&startdate=<?= $startdate ?>&enddate=<?= $enddate ?>&factorystr=<?= $factorystr ?>&systemstr=<?= $systemstr ?>&typestr=<?= $typestr ?>&factory=<?= $factory ?>&system=<?= $system ?>&type=<?= $type ?>&num=<?= $num ?>&device_sn=<?=$device_sn?>&content=<?=$content?>&states=<?= $states ?>">
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