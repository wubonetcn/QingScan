{include file='public/head' /}

<?php
$dengjiArr = ['Low', 'Medium', 'High', 'Critical'];
?>
<?php
$searchArr = [
    'action' => $_SERVER['REQUEST_URI'],
    'method' => 'get',
    'inputs' => [
        ['type' => 'text', 'name' => 'search', 'placeholder' => "搜索的内容"],
        ['type' => 'select', 'name' => 'vul_level', 'options' => $vul_level, 'frist_option' => '危险等级'],
        ['type' => 'select', 'name' => 'product_field', 'options' => $product_field, 'frist_option' => '行业'],
        ['type' => 'select', 'name' => 'product_type', 'options' => $product_type, 'frist_option' => '项目类型'],
        ['type' => 'select', 'name' => 'product_cate', 'options' => $product_cate, 'frist_option' => '平台分类'],
        ['type' => 'select', 'name' => 'check_status', 'options' => $check_status_list, 'frist_option' => '审计状态', 'frist_option_value' => -1],
    ],
    'btnArr' => [
        ['text' => '添加', 'ext' => [
            "href" => url('vulnerable/add'),
            "class" => "btn btn-outline-success"
        ]]
    ]]; ?>
{include file='public/search' /}

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
        <table class="table table-bordered table-hover table-striped">
            <thead>
            <tr>
                <th>
                    <label>
                        <input type="checkbox" value="-1" onclick="quanxuan(this)">全选
                    </label>
                </th>
                <th>ID</th>
                <th>名称</th>
                <th>CVE编号</th>
                <th>CNVD编号</th>
                <th>漏洞等级</th>
                <th>行业</th>
                <th>项目类型</th>
                <th>平台分类</th>
                <th>fofa数量</th>
                <th style="width: 200px">操作</th>
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
                    <td><?php echo $value['name'] ?></td>
                    <td><?php echo $value['cve_num'] ?></td>
                    <td><?php echo $value['cnvd_num'] ?></td>
                    <td><?php echo $value['vul_level'] ?></td>
                    <td><?php echo $value['product_field'] ?></td>
                    <td><?php echo $value['product_type'] ?></td>
                    <td><?php echo $value['product_cate'] ?></td>
                    <td><?php echo $value['fofa_max'] ?></td>

                    <td>
                        <a href="<?php echo url('vulnerable/details', ['id' => $value['id']]) ?>"
                           class="btn btn-sm btn-outline-primary">查看漏洞</a>
                        <a href="<?php echo url('vulnerable/edit', ['id' => $value['id']]) ?>"
                           class="btn btn-sm btn-outline-success">编辑</a>
                        <a href="<?php echo url('vulnerable/vulnerable_del', ['id' => $value['id']]) ?>"
                           class="btn btn-sm btn-outline-danger">删除</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
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
            url: "<?php echo url('vulnerable_batch_del')?>",
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