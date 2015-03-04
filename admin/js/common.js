$(document).ready(function() {
    //绑定下拉菜单事件
    $('#domain_id').change(function() {
        var id = $(this).children('option:selected').val(); //这就是selected的值  
        if (id != '') {
            window.location.href = "domains.php?id=" + id; //页面跳转并传参 
        }
    });
})

function delConfirm(str,url)
{
	if(confirm(str))
	{
       window.location.href=url;
	}
}