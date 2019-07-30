<?php //print_r($val)?>
<head>
    <title>正弘环太湖集团</title>
</head>

<body oncontextmenu="return false" onselectstart="return false" ondragstart="return false" >   　　
<startprint1>
    <div style="width: 822px;height: auto;padding: 10px;position:relative;margin:0 auto">
        <div style="color: red;position: absolute;right: 30px;top:25px;"><?php echo $data->bianhao?> </div>
    <?php echo $data->htnr?>
    <!--合同-->
    <?php if($val['type']==2){?>
  <div id="box1" onmouseover ='Move_obj("box1")' >
        <div id="bar1" style="">
            <img src="<?echo Yii::$app->params['img_host'].'/htimg/ht.png'?>" />
        </div>
  </div>
    <?php }?>
    </div>
</startprint1>

</body>

<div style="float: right;width: 50px;height: 20px;margin:20px;">
    <input id="btnPrint" type="button" value="立即打印" onclick=preview(1) width=""  height="" />
</div>
<script>
    function preview(oper)
    {
        if (oper < 10)
        {
            bdhtml=window.document.body.innerHTML;//获取当前页的html代码
            sprnstr="<startprint"+oper+">";//设置打印开始区域
            eprnstr="</startprint"+oper+">";//设置打印结束区域
            prnhtml=bdhtml.substring(bdhtml.indexOf(sprnstr)+18); //从开始代码向后取html
            prnhtmlprnhtml=prnhtml.substring(0,prnhtml.indexOf(eprnstr));//从结束代码向前取html
            window.document.body.innerHTML=prnhtmlprnhtml;
            window.print();
            window.document.body.innerHTML=bdhtml;
        } else {
            window.print();
        }
    }
    var drag_ = false
    var D = new Function('obj', 'return document.getElementById(obj);')
    var oevent = new Function('e', 'if (!e) e = window.event;return e')
    function Move_obj(obj) {
        var x, y;
        D(obj).onmousedown = function (e) {
            drag_ = true;
            with (this) {
                style.position = "absolute";
                var temp1 = offsetLeft; var temp2 = offsetTop;
                x = oevent(e).clientX; y = oevent(e).clientY;
                document.onmousemove = function (e) {
                    if (!drag_) return false;
                    with (this) {
                        style.left = temp1 + oevent(e).clientX - x + "px";
                        style.top = temp2 + oevent(e).clientY - y + "px";
                    }
                }
            }
            document.onmouseup = new Function("drag_=false");
        }
    }
</script>
