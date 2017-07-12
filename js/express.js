$(document).ready(function () {
    $("#doactive").submit(function () {
        data = $('#doactive').serializeArray();
        $(".result").html('<img src="images/loading_index.gif"/>');
        $.get("docha.php?active=firstcode",data,function (data) {
            $(".result").html(data);
        });
        $.get("docha.php?active=images",data,function (data) {
            $(".com-logo").html(data);
        });
        return false;
    });
});
$(function(){
    $("#postid").bind('input propertychange',function(){
        data = $('#doactive').serializeArray();
        $.get("docha.php?active=com",data,function (data) {
            $(".on_changes").html(data);
        });
        $.get("docha.php?active=images",data,function (data) {
            $(".com-logo").html(data);
        });
        $("#rs").slideDown('fast');
    });
    $("#postid").blur(function () {
        $("#rs").slideUp('slow');
    })
});
$(function () {
    $("#selectComBtn").click(function () {
        $("#comList").slideToggle();
        $(".result").css("display","none")
    })
});
$(function(){
    $(".com-list li a").click(function() {
        var data={
            code: $(this).data('code'),
            num:$("#postid").val()
        };
        //console.log(data);
        $("#comList").css("display","none");
        $(".result").css("display","block");
        $(".result").html('<img src="images/loading_index.gif"/>');
        $.get("docha.php?active=code",data,function (data) {
            $(".result").html(data);
        });
        $.get("docha.php?active=images",data,function (data) {
            $(".com-logo").html(data);
        });
    });
});
