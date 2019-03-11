<?php header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate'); ?>
<?php header('content-Type: text/html; charset=UTF-8'); ?>
<?php @ require_once 'database/database.php'; ?>
<?php @ require_once 'function/checkCookie.php'; ?>
<?php @ require_once 'function/functions.php'; ?>
<?php
if ($status == 'dkai' || $status == 'nkai') {
    $sta = '開機';
} else if ($status == 'ddai' || $status == 'ndai') {
    $sta = '待機';
} else if ($status == 'dgu' || $status == 'ngu') {
    $sta = '故障';
}
if ($_COOKIE['cdepartment'] == 'works') {
    ?>
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <meta http-equiv="pragma" content="no-cache"/>
            <meta http-equiv="Cache-Control" content="no-cache, no-store"/>
            <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
            <title>Device Data Summary</title>
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
                    $("#deviceDataSummary").addClass("active");
                    $("#deviceData").addClass("active open");
                    $("#pilot").addClass("active open");
                })
                function statusData(factorystr, systemstr, typestr, factory, system, type, num, device_name, device_sn) {
                    $.layer({
                        type: 2,
                        title: '設備保養狀態',
                        maxmin: true,
                        shadeClose: true,
                        area: ['1100px', '500px'],
                        offset: ['80px', ''],
                        shift: '',
                        iframe: {src: "pop/deviceDataStatus.php?factorystr=" + factorystr + "&systemstr=" + systemstr + "&typestr=" + typestr + "&factory=" + factory + "&system=" + system + "&type=" + type + "&num=" + num + "&device_name=" + device_name + "&device_sn=" + device_sn + "&states=summary"}
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
                                                <button type="button" class="btn btn-primary" onclick="window.location.href = 'deviceDataSummary.php?startdate=<?= $startdate ?>&enddate=<?= $enddate ?>&factorystr=<?= $factorystr ?>&systemstr=<?= $systemstr ?>&typestr=<?= $typestr ?>'">
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
                                        <table id="table" class=" nowrap table table-striped table-bordered table-hover" cellspacing="0" style="word-break: keep-all;white-space:nowrap;width:100%;text-align: center">
                                            <thead style="background: #f0f0f0">
                                                <tr>
                                                    <td>日期</td>
                                                    <td>位置</td>
                                                    <td>系統類別</td>
                                                    <td>設備類別</td>
                                                    <td>設備名稱</td>
                                                    <td>設備SN</td>
                                                    <td>品牌</td>
                                                    <td>設備狀態</td>
                                                    <td>抄錶狀態</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $p = $p == '' ? 1 : $p;
                                                $str = "SELECT ee.FACTORY,ee.FLOOR,ee.AREA,ee.ROOM,ee.SYSTEM,ee.TYPE,ee.DEVICE_NAME,ee.DEVICE_SN,ee.BRAND FROM
                                                            (SELECT cc.FACTORY,cc.FLOOR,cc.AREA,cc.ROOM,aa.SYSTEM,aa.TYPE,aa.DEVICE_NAME,aa.DEVICE_SN,aa.BRAND,dd.DSTATUS,dd.NSTATUS FROM device_information_works aa 
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
                                                                                     FROM(
                                                                                        SELECT aa.DEVICE_SN,aa.DEVICE_STATUS,CASE WHEN aa.FLAG in ('0','1') THEN 'D' WHEN aa.FLAG in ('2','3') THEN 'T' END FLAG 
                                                                                        FROM data_detail_works aa
                                                                                        LEFT JOIN(SELECT DATE,DEVICE_SN,max(IDX) as IDX,FLAG FROM data_detail_works GROUP BY DATE,DEVICE_SN,FLAG)bb
                                                                                        ON bb.DATE=aa.DATE
                                                                                        where aa.`DATE`='" . $date . "' and bb.IDX=aa.IDX ORDER BY aa.DEVICE_SN,aa.FLAG)cc
                                                                                    )dd GROUP BY dd.DEVICE_SN)dd ON dd.DEVICE_SN=aa.DEVICE_SN
                                                                    WHERE aa.DATA_METHOD in ('1','2') AND aa.FLAG <>'1' AND bb.FLAG <>'1' AND cc.FLAG <>'1' AND cc.FACTORY <>''
                                                        AND cc.FACTORY='" . $factory . "' AND aa.SYSTEM='" . $system . "' AND aa.TYPE='" . $type . "')ee";
                                                if ($day == 'D') {
                                                    $str = $str . " WHERE ee.DSTATUS='" . $status . "'";
                                                } else {
                                                    $str = $str . " WHERE ee.NSTATUS='" . $status . "'";
                                                }
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
                                                            <td><?= $date ?></td>
                                                            <td><?= $value['FACTORY'] . '_' . $value['FLOOR'] . '_' . $value['AREA'] . '_' . $value['ROOM'] ?></td>
                                                            <td><?= getNamech($pdo, $system) ?></td>
                                                            <td><?= getNamech($pdo, $type) ?></td>
                                                            <td> <?= $value['DEVICE_NAME'] ?></td>
                                                            <td><?= $value['DEVICE_SN'] ?></td>
                                                            <td><?= $value['BRAND'] ?></td>
                                                            <td><?= $sta ?></td>
                                                            <td><a href="#" onclick="statusData('<?= $factorystr ?>', '<?= $systemstr ?>', '<?= $typestr ?>', '<?= $factory ?>', '<?= $system ?>', '<?= $type ?>', '<?= $num ?>', '<?= $value['DEVICE_NAME'] ?>', '<?= $value['DEVICE_SN'] ?>')">
                                                                    <?= statusDataWorks($pdo, $date, $value['DEVICE_NAME'], $value['DEVICE_SN']) ?>
                                                                </a>
                                                            </td>
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
                                        <form class="form-inline" method="post" action="deviceDataNUM.php?factorystr=<?= $factorystr ?>&systemstr=<?= $systemstr ?>&typestr=<?= $typestr ?>&factory=<?= $factory ?>&system=<?= $system ?>&type=<?= $type ?>&num=<?= $num ?>">
                                            <div class="form-group">
                                                <nav aria-label="Page navigation">
                                                    <ul class="pagination">
                                                        <li>
                                                            <a href="deviceDataNUM.php?p=<?= $firstpage ?>&factorystr=<?= $factorystr ?>&systemstr=<?= $systemstr ?>&typestr=<?= $typestr ?>&factory=<?= $factory ?>&system=<?= $system ?>&type=<?= $type ?>&num=<?= $num ?>">
                                                                <span class="icon-fast-backward"></span> 首頁
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="deviceDataNUM.php?p=<?= $prepage ?>&factorystr=<?= $factorystr ?>&systemstr=<?= $systemstr ?>&typestr=<?= $typestr ?>&factory=<?= $factory ?>&system=<?= $system ?>&type=<?= $type ?>&num=<?= $num ?>">
                                                                <span class="icon-step-backward"></span> 上一頁
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="deviceDataNUM.php?p=<?= $nextpage ?>&factorystr=<?= $factorystr ?>&systemstr=<?= $systemstr ?>&typestr=<?= $typestr ?>&factory=<?= $factory ?>&system=<?= $system ?>&type=<?= $type ?>&num=<?= $num ?>">
                                                                下一頁 <span class="icon-step-forward"></span> 
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="deviceDataNUM.php?p=<?= $lastpage ?>&factorystr=<?= $factorystr ?>&systemstr=<?= $systemstr ?>&typestr=<?= $typestr ?>&factory=<?= $factory ?>&system=<?= $system ?>&type=<?= $type ?>&num=<?= $num ?>">
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