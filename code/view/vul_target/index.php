{include file='public/head' /}

<?php
$searchArr = [
    'action' => url('code/bug_list'),
    'method' => 'get',
    'inputs' => [
        ['type' => 'text', 'name' => 'search', 'placeholder' => "搜索"],
    ],
    'btnArr' => [
        ['text' => '添加', 'ext' => [
            "href" => url('add'),
            "class" => "btn btn-outline-success"
        ]
        ]
    ]
];
?>
{include file='public/search' /}


<div class="row tuchu">
    <div class="col-md-12">
        <form class="row g-3" id="frmUpload" action="<?php echo url('vul_target/batch_import') ?>" method="post"
              enctype="multipart/form-data">
            <div class="col-auto">
                <input type="file" class="form-control form-control" name="file" accept=".xls,.csv" required/>
            </div>
            <div class="col-auto">
                <input type="submit" class="btn btn-outline-info" value="批量添加项目">
            </div>
            <div class="col-auto">
                <a href="<?php echo url('vul_target/downloaAppTemplate') ?>"
                   class="btn btn-outline-success">下载模板</a>
            </div>
        </form>
    </div>
</div>

<div class="row tuchu">
    <div class="col-md-12">
        <form class="row g-3" id="frmUpload" action="" method="post"
              enctype="multipart/form-data">
            <div class="col-auto">
                <a href="javascript:;" onclick="batch_del()"
                   class="btn btn-outline-success">批量删除</a>
            </div>
        </form>
    </div>
</div>

<div class="row tuchu">
    <div class="col-md-12 ">
        <?php if (!empty($list)) { ?>
            <table class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                    <th>
                        <label>
                            <input type="checkbox" value="-1" onclick="quanxuan(this)">全选
                        </label>
                    </th>
                    <th>ID</th>
                    <th>地址</th>
                    <th>IP</th>
                    <th>端口</th>
                    <th>fofa关键字</th>
                    <th>创建时间</th>
                    <th>审计状态</th>
                    <th>缺陷名称</th>
                    <th>操作</th>
                </tr>
                </thead>
                <?php foreach ($list as $value) { ?>
                    <tr>
                        <td>
                            <label>
                                <input type="checkbox" class="ids" name="ids[]" value="<?php echo $value['id'] ?>">
                            </label>
                        </td>
                        <td><?php echo $value['id'] ?></td>
                        <td><?php echo $value['addr'] ?></td>
                        <td><?php echo $value['ip'] ?></td>
                        <td><?php echo $value['port'] ?></td>
                        <td><?php echo $value['query'] ?></td>
                        <td><?php echo $value['create_time'] ?></td>
                        <td><?php echo $value['is_vul'] ?></td>
                        <td><?php echo $value['vul_id'] ?></td>
                        <td>
                            <?php if ($value['content']) { ?>
                                <a class="btn btn-outline-info">POC验证</a>
                            <?php } ?>
                            <a href="<?php echo url('del', ['id' => $value['id']]) ?>"
                               class="btn btn-sm btn-outline-danger">删除</a>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        <?php } else {
            echo "<h3 class='text-center'>列表没有数据</h3>";
        } ?>
    </div>
</div>
{include file='public/fenye' /}
{include file='public/footer' /}


<script>
    function quanxuan(obj){
        var child = $('.table').find('.ids');
        child.each(function(index, item){
            if (obj.checked) {
                item.checked = true
            } else {
                item.checked = false
            }
        })
    }

    function batch_del(){
        var child = $('.table').find('.ids');
        var ids = ''
        child.each(function(index, item){
            if (item.value != -1 && item.checked) {
                if (ids == '') {
                    ids = item.value
                } else {
                    ids = ids+','+item.value
                }
            }
        })

        $.ajax({
            type: "post",
            url: "<?php echo url('batch_del')?>",
            data: {ids: ids},
            dataType: "json",
            success: function (data) {
                alert(data.msg)
                if (data.code == 1) {
                    window.setTimeout(function () {
                        location.reload();
                    }, 2000)
                }
            }
        });
    }
</script>