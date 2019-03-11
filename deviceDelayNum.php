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
                    $("#deviceDelaySummary").addClass("active");
                    $("#deviceDelay").addClass("active open");
                    $("#maintain").addClass("active open");
                })

                function statusMaintain(factorystr, systemstr, typestr, factory, system, type, num, device_name, device_sn) {
                    $.layer({
                        type: 2,
                        title: '設備保養狀態',
                        maxmin: true,
                        shadeClose: true,
                        area: ['1100px', '500px'],
                        offset: ['80px', ''],
                        shift: '',
                        iframe: {src: "pop/deviceMaintainStatus.php?factorystr=" + factorystr + "&systemstr=" + systemstr + "&typestr=" + typestr + "&factory=" + factory + "&system=" + system + "&type=" + type + "&num=" + num + "&device_name=" + device_name + "&device_sn=" + device_sn + "&states=delay"},
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
                                        未結單
                                    </small>
                                </h1>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="table-responsive">

                                                <button type="button" class="btn btn-primary" onclick="window.location.href = 'deviceDelaySummary.php?factorystr=<?= $factorystr ?>&systemstr=<?= $systemstr ?>&typestr=<?= $typestr ?>'">
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
                                                    <td>位置</td>
                                                    <td>系統類別</td>
                                                    <td>設備類別</td>
                                                    <td>設備名稱</td>
                                                    <td>設備SN</td>
                                                    <td>保養狀態</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $p = $p == '' ? 1 : $p;
                                                if ($num == 'TOTAL') {
                                                    $term = "";
                                                } else {
                                                    $term = " AND dd.`STATUS`='" . $num . "'";
                                                }
                                                $str = "SELECT * FROM
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
                                                                        WHERE aa.FLAG !='1' AND bb.FLAG !='1' AND cc.FLAG !='1' AND dd.CONTENT IS NOT NULL)aa)bb WHERE bb.FLAG IS NOT NULL)cc)dd
                                                            WHERE dd.`STATUS` IS NOT NULL AND dd.FACTORY='" . $factory . "' AND dd.SYSTEM='" . $system . "' AND dd.TYPE='" . $type . "'" . $term." GROUP BY dd.DEVICE_SN";
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
                                                            <td><nobr><?= $value['FACTORY'] . '_' . $value['FLOOR'] . '_' . $value['AREA'] . '_' . $value['ROOM'] ?></nobr></td>
                                                    <td><nobr><?= getNamech($pdo, $system) ?></nobr></td>
                                                    <td><nobr><?= getNamech($pdo, $type) ?></nobr></td>
                                                    <td><nobr><?= $value['DEVICE_NAME'] ?></nobr></td>
                                                    <td><nobr><?= $value['DEVICE_SN'] ?></nobr></td>
                                                    <td><a href="#" onclick="statusMaintain('<?= $factorystr ?>', '<?= $systemstr ?>', '<?= $typestr ?>', '<?= $factory ?>', '<?= $system ?>', '<?= $type ?>', '<?= $num ?>', '<?= $value['DEVICE_NAME'] ?>', '<?= $value['DEVICE_SN'] ?>')">
                                                            <?= statusMaintainWorks($pdo, $value['DEVICE_NAME'], $value['DEVICE_SN']) ?>
                                                        </a>
                                                    </td>
                                                    <script>
                                                        function changeColor(id) {
                                                            var color = "#FF0000|#FF7256|#FF4040";
                                                            color = color.split("|");
                                                            document.getElementById(id).style.background = color[parseInt(Math.random() * color.length)];
                                                        }
                                                        setInterval("changeColor('<?= $value['DEVICE_SN'] ?>')", 100);
                                                    </script>
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
                                        <form class="form-inline" method="post" action="deviceDelayNum.php?factorystr=<?= $factorystr ?>&systemstr=<?= $systemstr ?>&typestr=<?= $typestr ?>&factory=<?= $factory ?>&system=<?= $system ?>&type=<?= $type ?>&num=<?= $num ?>">
                                            <div class="form-group">
                                                <nav aria-label="Page navigation">
                                                    <ul class="pagination">
                                                        <li>
                                                            <a href="deviceDelayNum.php?p=<?= $firstpage ?>&factorystr=<?= $factorystr ?>&systemstr=<?= $systemstr ?>&typestr=<?= $typestr ?>&factory=<?= $factory ?>&system=<?= $system ?>&type=<?= $type ?>&num=<?= $num ?>">
                                                                <span class="icon-fast-backward"></span> 首頁
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="deviceDelayNum.php?p=<?= $prepage ?>&factorystr=<?= $factorystr ?>&systemstr=<?= $systemstr ?>&typestr=<?= $typestr ?>&factory=<?= $factory ?>&system=<?= $system ?>&type=<?= $type ?>&num=<?= $num ?>">
                                                                <span class="icon-step-backward"></span> 上一頁
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="deviceDelayNum.php?p=<?= $nextpage ?>&factorystr=<?= $factorystr ?>&systemstr=<?= $systemstr ?>&typestr=<?= $typestr ?>&factory=<?= $factory ?>&system=<?= $system ?>&type=<?= $type ?>&num=<?= $num ?>">
                                                                下一頁 <span class="icon-step-forward"></span> 
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="deviceDelayNum.php?p=<?= $lastpage ?>&factorystr=<?= $factorystr ?>&systemstr=<?= $systemstr ?>&typestr=<?= $typestr ?>&factory=<?= $factory ?>&system=<?= $system ?>&type=<?= $type ?>&num=<?= $num ?>">
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