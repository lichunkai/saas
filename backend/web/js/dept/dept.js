layui.config({
	base : "js/"
}).use(['form','layer','jquery','laypage','tree'],function(){
	var form = layui.form,
		layer = parent.layer === undefined ? layui.layer : parent.layer,
		laypage = layui.laypage;
		//$ = layui.jquery;
    //默认加载全部员工
    getdata({"_csrf-backend":$('meta[name=csrf-token]').attr('content')});
    var setting = {
        data: {
            simpleData: {
                enable: true
            }
        },
        callback: {
            onClick: onClick
        }
    };

    $.ajax({
        type:'post',
        data:{"_csrf-backend":$('meta[name=csrf-token]').attr('content')},
        url: '/index.php?r=dept/getlist',
        dataType: "json",
        success:function(result){
            if(result.code == 200){
                //执行加载数据的方法
                var zNodes = result.data;
                $.fn.zTree.init($("#depttree"), setting, zNodes);
            }
        }
    });

    //选中
    function onClick(event, treeId, treeNode, clickFlag)
    {
        $('input[name="tree_id"]').val(treeNode.id);
        var searchdata = {"id": treeNode.id,"_csrf-backend":$('meta[name=csrf-token]').attr('content')};
        getdata(searchdata);

    }
    //添加部门
    $("#adddept").click(function () {
        var p_id = $('input[name="tree_id"]').val();
        if(p_id){
            layer.open({
                type: 1,
                skin: 'layui-layer-demo', //样式类名
                closeBtn: 0, //不显示关闭按钮
                anim: 2,
                shadeClose: true, //开启遮罩关闭
                content: $("#layer-box").html()
            });
        }
    });

    //数据查询
    function getdata(searchdata) {
        $.ajax({
            type:'post',
            data:searchdata,
            url: '/index.php?r=dept/getemployee',
            dataType: "json",
            success:function(result){
                if(result.code == 200){
                    //执行加载数据的方法
                    usersList(result.data);
                }
            }
        });
    }

    //组装数据及分页
    function usersList(usersData){
        //渲染数据
        function renderDate(data,curr){
            var dataHtml = '';
            currData = usersData.concat().splice(curr*nums-nums, nums);
            if(currData.length != 0){
                for(var i=0;i<currData.length;i++){
                    dataHtml += '<tr>'
                        // +  '<td><input type="checkbox" name="checked" lay-skin="primary" lay-filter="choose"></td>'
                        +  '<td>'+currData[i].employee_no+'</td>'
                        +  '<td>'+currData[i].employee_name+'</td>'
                        +  '<td>'+currData[i].employee_phone+'</td>'
                        +  '<td>'+(currData[i].employee_sex===1 ? "男": (currData[i].employee_sex===2 ? "女" : "未知"))+'</td>'
                        +  '<td>'+(currData[i].employee_status===1 ? "正常" : "禁用")+'</td>'
                        +  '<td>'+currData[i].dept_name+'</td>'
                        +  '<td>'+currData[i].post_name+'</td>'
                        +  '<td>'
                        +    '<a class="layui-btn layui-btn-xs users_edit"><i class="iconfont icon-edit"></i> 编辑</a>'
                        +    '<a class="layui-btn layui-btn-danger layui-btn-xs users_del" data-id="'+data[i].employee_id+'"><i class="layui-icon">&#xe640;</i> 删除</a>'
                        +  '</td>'
                        +'</tr>';
                }
            }else{
                dataHtml = '<tr><td colspan="8">暂无数据</td></tr>';
            }
            return dataHtml;
        }

        //分页
        var nums = 10; //每页出现的数据量
        laypage.render({
            cont : "page",
            pages : Math.ceil(usersData.length/nums),
            jump : function(obj){
                $(".users_content").html(renderDate(usersData,obj.curr));
                // $('.users_list thead input[type="checkbox"]').prop("checked",false);
                form.render();
            }
        })
    }
        
})