<?php /*a:1:{s:64:"D:\phpstudy_pro\WWW\www.tp6com\app\admin\view\category\edit.html";i:1620095548;}*/ ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>添加商品分类</title>
    <link rel="stylesheet" href="/static/admin/lib/layui-v2.5.4/css/layui.css" media="all">
    <link rel="stylesheet" href="/static/admin/css/public.css" media="all">
</head>
<body>
<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
    <legend>商品分类管理</legend>
</fieldset>

<form class="layui-form" action="">
    <input type="hidden" name="id" value="<?php echo htmlentities($category['id']); ?>">
    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label" style="width: 200px;">父级分类</label>
            <div class="layui-input-inline">
                <select name="pid" id="classif"></select>
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label" style="width: 200px;">商品分类</label>
        <div class="layui-input-inline">
            <input type="text" name="name" lay-verify="name" autocomplete="off" value="<?php echo htmlentities($category['name']); ?>" placeholder="请输入标分类名称"
                   class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label" style="width: 200px;"></label>
        <div class="layui-input-inline">
            <button class="layui-btn layui-btn-normal" lay-submit="" lay-filter="demo1">立即提交</button>
        </div>
    </div>
</form>
<script src="/static/admin/lib/layui-v2.5.4/layui.js" charset="utf-8"></script>
<script src="/static/admin/lib/jquery-3.4.1/jquery-3.4.1.min.js" charset="utf-8"></script>
<script src="/static/admin/js/common.js" charset="utf-8"></script>
<script>
    layui.use(['form','laypage'], function () {
        var form = layui.form;
        function _classif(res=[]) {
            // res 分类数据 先期模拟
            let temps = '<option value="0">-| 顶级菜单</option>';
            // 显示
            var data = <?php echo $categorys; ?>

            let toTrees = toTree(data);
            for (let item of toTrees) {
                temps += `<optgroup  data-id="${item["id"]}">`;
                temps += `<option  data-id="${item['id']}" value="${item['id']}">-| ${item["name"]}</option>`
                if (item['children'] && item['children'].length > 0) {
                    for (let child of item['children']) {
                        temps += `<option  data-id="${child['id']}" value="${child['id']}"> &nbsp;&nbsp;&nbsp;--| ${child["name"]} </option>`
                    }
                }
                temps += `</optgroup>`;
            }
            $('#classif').html(temps)
            form.render('select');
        }


        function queryClassif() { // 请求分类 后端接口
            let url = '';
            layObj.get(url,function (res) {
                console.log(res)
            }); // 封装的ajax
            _classif()
        }
        queryClassif(); // 获取后端分类数据

        //监听提交 修改分类
        form.on('submit(demo1)', function (data) {
            console.log(data.field, '最终的提交信息')
            data = data.field;
            // let url = '';
            // layObj.post(url,data,function (res) {
            //
            // });
            // console.log(data);
            $.ajax({
                type:"POST",
                data:data,
                url: '/admin/category/update',
                success(res){
                    // todo
                    if(res.status == 1) {
                        layer.msg("修改成功", function() {
                            window.location="<?php echo url('index'); ?>";
                        });
                    } else {
                        layer.msg(res.message);
                        return false;
                    }
                },
            })
            return false;
        });
    })
</script>
</body>
</html>
