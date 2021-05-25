<?php /*a:1:{s:63:"E:\phpstudy_pro\WWW\www.tp6.com\app\admin\view\goods\index.html";i:1619771255;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>商品列表</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/static/admin/lib/layui-v2.5.4/css/layui.css" media="all">
    <link rel="stylesheet" href="/static/admin/css/public.css" media="all">
</head>
<body>
<div class="layuimini-container">
    <div class="layuimini-main">

        <fieldset class="layui-elem-field layuimini-search">
            <legend>搜索信息</legend>
            <div style="margin: 10px 10px 10px 10px">
                <form class="layui-form layui-form-pane" action="<?php echo url('index'); ?>" method="GET">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <label class="layui-form-label">商品名称</label>
                            <div class="layui-input-inline">
                                <input type="text" name="title" autocomplete="off" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-inline">
                            <label class="layui-form-label">发布时间</label>
                            <div class="layui-input-inline" style="width: 280px;">
                                <div class="layui-input-inline" style="width: 280px;">
                                    <input type="text" name="time" class="layui-input" id="test10"
                                           placeholder=" - ">
                                </div>
                            </div>
                        </div>
                        <div class="layui-inline">
                            <button class="layui-btn" lay-submit="" lay-filter="data-search-btn">搜索</button>

                        </div>
                    </div>
                </form>
            </div>
        </fieldset>


        <div class="layuimini-main">
            <a href="<?php echo url('add'); ?>"><button type="button" class="layui-btn add">添 加</button></a>

            <div class="layui-form" style="margin-top: 20px;">
                <table class="layui-table">
                    <colgroup>
                        <col width="40">
                        <col width="320">
                        <col width="80">
                        <col width="130">
                        <col width="70">
                        <col width="200">
                        <col width="200">
                        <col width="200">
                        <col width="100">
                        <col width="85">
                    </colgroup>
                    <thead>
                    <tr>
                        <th>id</th>
                        <th>商品名称</th>
                        <th>排序</th>
                        <th class="text-center">商品图片</th>
                        <th class="text-center">库存</th>
                        <th class="text-center">生产日期</th>
                        <th class="text-center">发布时间</th>
                        <th class="text-center">更新时间</th>
                        <th class="text-center">状 态</th>
                        <th class="text-center">是否推荐</th>
                        <th>操作管理</th>
                    </tr>
                    </thead>
                    <tbody>
                    <!--一级类目循环-->
                    <?php if(is_array($goods['data']) || $goods['data'] instanceof \think\Collection || $goods['data'] instanceof \think\Paginator): $i = 0; $__LIST__ = $goods['data'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <tr>
                        <td><?php echo htmlentities($vo['id']); ?></td>
                        <td><?php echo htmlentities($vo['title']); ?></td>
                        <td>
                            <div class="layui-input-inline layui-text-center">
                                <input type="text" value="<?php echo htmlentities($vo['listorder']); ?>" data-id="<?php echo htmlentities($vo['id']); ?>" class="changegoods layui-input">
                            </div>
                        </td>


                        <td class="show-img">
                            <img  src="<?php echo htmlentities($vo['recommend_image']); ?>" data-src="<?php echo htmlentities($vo['recommend_image']); ?>"  style="width: 50px;height: 50px;" />
                        </td>
                        <td><?php echo htmlentities($vo['stock']); ?></td>
                        <td><?php echo htmlentities($vo['production_time']); ?></td>
                        <td><?php echo htmlentities($vo['create_time']); ?></td>
                        <td><?php echo htmlentities($vo['update_time']); ?></td>

                        <td data-id="<?php echo htmlentities($vo['id']); ?>"><input type="checkbox" <?php if($vo['status'] == 1): ?>checked <?php else: ?><?php endif; ?>  name="status" lay-skin="switch"
                            lay-filter="switchStatus"
                            lay-text="ON|OFF">
                        </td>
                        <td data-id="<?php echo htmlentities($vo['id']); ?>"><input type="checkbox" <?php if($vo['is_index_recommend'] == 1): ?>checked <?php else: ?><?php endif; ?>  name="is_index_recommend" lay-skin="switch"
                            lay-filter="switchStatusrecommend"
                            lay-text="是|否">
                        </td>


                        <td>
                            <a class="layui-btn layui-btn-xs  edit" lay-event="edit">编辑</a>
                            <a class="layui-btn layui-btn-xs layui-btn-danger data-count-delete del-child delete" data-ptype="1"
                               lay-event="delete" data-id="<?php echo htmlentities($vo['id']); ?>">删除</a>
                        </td>
                    </tr>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div id="pages"></div>
    </div>
</div>

<script src="/static/admin/lib/jquery-3.4.1/jquery-3.4.1.min.js" charset="utf-8"></script>
<script src="/static/admin/lib/layui-v2.5.4/layui.js" charset="utf-8"></script>
<script src="/static/admin/js/common.js" charset="utf-8"></script>
<script>


    // 显示图片
    layui.use(['form', 'table', 'laydate','jquery','laypage'], function () {

        var $ = layui.jquery,
            form = layui.form,
            laypage = layui.laypage,
           laydate = layui.laydate;

        //日期时间范围 搜索
        laydate.render({
            elem: '#test10'
            , type: 'datetime'
            , range: true
        });

        laypage.render({ //分页
            elem: 'pages'
            , count: <?php echo htmlentities($goods['total']); ?>  // 新加的内容哦。
            , limit: <?php echo htmlentities($goods['per_page']); ?>
            , theme: '#FFB800'
            //, curr: param['page']
            ,curr: <?php echo htmlentities($goods['current_page']); ?> // 完美解决哦。
            //,hash: 'page' //自定义hash值
            ,jump: function(obj, first){
                //obj包含了当前分页的所有参数，比如：

                //首次不执行
                if(!first){
                    //do something
                    location.href="?page="+obj.curr
                }
            }
        });

        $('.show-img').on('click',function(){
            var imgurl=$(this).find('img').attr('data-src');
            //页面层
            layer.open({
                type: 1,
                shade: 0.8,
                offset: 'auto',
                area: [500 + 'px',550+'px'],
                scrollbar: false,
                title:'图片预览',
                shadeClose: true, //开启遮罩关闭
                end: function (index, layero) {
                    return false;
                },
                content: `<div style="text-align:center"><img src="${imgurl}" /></div>`
            });
        })

        //监听状态 更改状态
        form.on('switch(switchStatus)', function (obj) {
            let id = obj.othis.parent().attr('data-id');
            let status = obj.elem.checked ? 1 : 0;
            $.ajax({
                url: '<?php echo url("goods/status"); ?>?id=' + id + '&status=' + status,
                success: (res) => {
                    if(res.status == 1) {
                        window.location.reload();
                    } else {
                        layer.msg("排序失败");
                    }
                }
            });
            return false;
        });

        // 商品删除逻辑
        $('.delete').on('click', function () {
            let ptype = $(this).attr('data-ptype');
            let id = $(this).attr('data-id');
            layObj.box('是否删除当前商品', () => {
                let   url = '<?php echo url("goods/status"); ?>?id=' + id + "&status=99"
                layObj.get(url, (res) =>{
                    if(res.status == 1) {
                        window.location.reload();
                    } else {
                        layer.msg("删除失败");
                    }
                })
            })
        });

        //监听状态 更改是否推荐
        form.on('switch(switchStatusrecommend)', function (obj) {
            let id = obj.othis.parent().attr('data-id');
            let is_index_recommend = obj.elem.checked ? 1 : 0;
            $.ajax({
                url: '<?php echo url("goods/isindexrecommend"); ?>?id=' + id + '&is_index_recommend=' + is_index_recommend,
                success: (res) => {
                    if(res.status == 1) {
                        window.location.reload();
                    } else {
                        layer.msg("排序失败");
                    }
                }
            });
            return false;
        });

        /**
         * 排序JS段
         */
        $('.changegoods').on('change',function () {
            // console.log(1);
            let id = $(this).attr('data-id');
            let val = $(this).val();
            if(!val){
                return;
            }
            let url = '<?php echo url("goods/listorder"); ?>?id=' + id + '&listorder='+val;
            layObj.get(url,function (res) {
                if(res.status == 1) {
                    window.location.reload();
                } else {
                    layer.msg("排序失败");
                }
            })
        })
    });


</script>
</body>
</html>
