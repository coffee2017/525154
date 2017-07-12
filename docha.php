<?php
/**
 * Created by PhpStorm.
 * User: coffee
 * Date: 2017/7/8
 * Time: 10:30
 */

require ('./Class/Express.class.php');
header('Content-Type:text/html; charset=utf-8');
//接收数据
$postid=isset($_GET["postid"]) ? $_GET["postid"]:'';
  //过滤非法字符
$postid= preg_replace('/\W/','',$postid);
$active=isset($_GET["active"]) ? $_GET["active"]:'';
$code=isset($_GET["code"]) ? $_GET["code"]:'default';
$num=isset($_GET["num"]) ? $_GET["num"]:'';
$express = new Express();
$result  = $express -> getorder($postid);
$getorderone  = $express -> getorderone($code,$num);
$expname= $express-> expname();
$expressallcom= $express->expressallcom($postid);
$expressimages= $express->expressimages($postid);
$data=$result["data"];
$state=$result["state"];
?>
<?php
if ($active=="com"):?>
    <?php if (empty($expressallcom)) :
        return false;?>
    <?php else:?>
        <?php  foreach ($expressallcom as $key=>$name):
            foreach ($expname as $value):
                if ($value["number"]==$name["comCode"]):?>
                    <li data-code="<?php echo $value["number"];?>" data-num="<?php echo $postid;?>">
                         <span><?php echo $postid;?>    </span>
                        <?php  echo  $value["name"];?>
                    </li>
                <?php endif;
            endforeach;
            endforeach;
            endif; ?>
    <script>
        $(function(){
            $("#rs li").click(function(v) {
                var data={
                    code: $(this).data('code'),
                    num:$(this).data('num')
                };
                //console.log(data);
                $(".result").html('<img src="images/loading_index.gif"/>');
                $.get("docha.php?active=code",data,function (data) {
                    $(".result").html(data);
                });
                $.get("docha.php?active=images",data,function (data) {
                    $(".com-logo").html(data);
                });
            });
        });
    </script>
<?php endif;?>
<?php
if ($active=="images"):?>
    <?php if (empty($expressallcom)) :?>
        <?php if ($code=="default"):?>
            <i></i>
            <img src="https://cdn.kuaidi100.com/images/all/56/default.png" data-code="default">
        <?php else:?>
            <i></i>
            <img src="https://cdn.kuaidi100.com/images/all/56/<?php echo $code;?>.png" data-code="default">
        <?php endif;?>
    <?php else:?>
        <i></i>
        <img src="https://cdn.kuaidi100.com/images/all/56/<?php echo $expressimages;?>.png" data-code="default">
    <?php endif;?>
<?php endif;?>
<?php
if ($active=="code"):?>
    <?php
    $messag=$getorderone["message"];
    $data=$getorderone["data"];
    $state=$getorderone["state"];
    if($messag=="ok"):?>
        <div id="resultHeader" class="select-com relative hidden" style="display: block;">
            <?php foreach ($expname as $value):
                if ($value["number"]==$getorderone["com"]): ?>
                    <span id="companyName" class="hidden" style="display: inline;"> <?php  echo $value["name"];?></span>
                    <span id="order">单号： <?php echo $getorderone["nu"];?></span>
                    <a id="companyUrl" href="<?php echo $value["siteUrl"] ;?>" target="_blank" class="mr10px result-companyurl" rel="nofollow">官网</a>
                    <span id="companyTel" class="ico-tel">电话： <?php  echo $value["contactTel"];?></span>
                <?php endif;
            endforeach;?>
        </div>
        <div id="queryContext" class="hidden relative query-box" style="display: block;">
            <div class="result-top" id="resultTop"><span id="sortSpan" class="col1-down" title="切换排序" onclick="sortToggle();">时间</span><span class="col2">地点和跟踪进度</span></div>
            <table id="queryResult" class="result-info" cellspacing="0"><tbody>
                <?php
                foreach ($data as $key=>$time):?>
                    <?php
                    //判断最新时间
                    if($key==0): ?>
                        <?php if($state==3):?>
                            <tr class="last">
                                <td class="row1"> <?php echo $time["time"];?></td>
                                <td class="status status-check">
                                    <div class="col2">
                                        <span class="step">
                                            <span class="line1"></span>
                                            <span class="line2"></span>
                                        </span>
                                    </div>
                                </td>
                                <td class="context">  <?php echo $time["context"];?></td>
                            </tr>
                        <?php elseif ($state==0):?>
                            <tr class="last">
                                <td class="row1"> <?php echo $time["time"];?></td>
                                <td class="status status-wait">&nbsp;
                                    <div class="col2">
                                        <span class="step">
                                            <span class="line1"></span>
                                            <span class="line2"></span>
                                        </span>
                                    </div>
                                </td>
                                <td class="context">  <?php echo $time["context"];?></td>
                            </tr>
                        <?php endif;?>
                        <?php
                    //判断快递上线时间
                    elseif($key==count($data)-1):?>
                        <tr>
                            <td class="row1"> <?php echo $time["time"];?></td>
                            <td class="status status-first">&nbsp;</td>
                            <td class="context">  <?php echo $time["context"];?></td>
                        </tr>
                    <?php else:?>
                        <tr>
                            <td class="row1"> <?php echo $time["time"];?></td>
                            <td class="status">
                                <div class="col2">
                                    <span class="step">
                                        <span class="line1"></span>
                                        <span class="line2"></span>
                                    </span>
                                </div>
                            </td>
                            <td class="context">  <?php echo $time["context"];?></td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach;?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <h4 class="notfind-icon"><?php echo $messag;?></h4>
        <div>请检查快递单号</div>
    <?php endif;?>
<?php endif;?>
<?php
if ($active=="firstcode"):?>
    <?php
    $messag=$result["message"];
    if($messag=="ok"):?>
        <div id="resultHeader" class="select-com relative hidden" style="display: block;">
            <?php foreach ($expname as $value):
                if ($value["number"]==$result["com"]): ?>
                <span id="companyName"  style="display: inline;"> <?php  echo $value["name"];?></span>
                 <span id="order">单号： <?php echo $result["nu"];?></span>
                <a id="companyUrl" href="<?php echo $value["siteUrl"] ;?>" target="_blank" class="mr10px result-companyurl" rel="nofollow">官网</a>
                <span id="companyTel" class="ico-tel">电话： <?php  echo $value["contactTel"];?></span>
                <?php endif;
             endforeach;?>
        </div>
        <div id="queryContext" class="hidden relative query-box" style="display: block;">
            <div class="result-top" id="resultTop">
                <span id="sortSpan" class="col1-down"  onclick="sortToggle();">时间</span>
                <span class="col2">地点和跟踪进度</span>
            </div>
            <table id="queryResult" class="result-info" cellspacing="0">
                <tbody>
                <?php
                foreach ($data as $key=>$time):?>
                    <?php
                    //判断最新时间
                    if($key==0): ?>
                        <?php if($state==3):?>
                            <tr class="last">
                                <td class="row1"> <?php echo $time["time"];?></td>
                                <td class="status status-check">
                                    <div class="col2">
                                    <span class="step">
                                        <span class="line1"></span>
                                        <span class="line2"></span>
                                    </span>
                                    </div>
                                </td>
                                <td class="context">  <?php echo $time["context"];?></td>
                            </tr>
                        <?php elseif ($state==0):?>
                        <tr class="last">
                            <td class="row1"> <?php echo $time["time"];?></td>
                            <td class="status status-wait">&nbsp;
                                <div class="col2">
                                    <span class="step">
                                        <span class="line1"></span>
                                        <span class="line2"></span>
                                    </span>
                                </div>
                            </td>
                            <td class="context">  <?php echo $time["context"];?></td>
                        </tr>
                        <?php endif;?>
                        <?php
                    //判断快递上线时间
                        elseif($key==count($data)-1):?>
                            <tr>
                                <td class="row1"> <?php echo $time["time"];?></td>
                                <td class="status status-first">&nbsp;</td>
                                <td class="context">  <?php echo $time["context"];?></td>
                            </tr>
                        <?php else:?>
                            <tr>
                                <td class="row1"> <?php echo $time["time"];?></td>
                                <!-- <td class="row1"> <?php echo asort($time);?></td>!-->
                                <td class="status">
                                    <div class="col2">
                                    <span class="step">
                                        <span class="line1"></span>
                                        <span class="line2"></span>
                                    </span>
                                    </div>
                                </td>
                                <td class="context">  <?php echo $time["context"];?></td>
                            </tr>
                    <?php endif; ?>
                <?php endforeach;?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <h4 class="notfind-icon"><?php echo $messag;?></h4>
        <div>请检查快递单号</div>
    <?php endif;?>
<?php endif;?>