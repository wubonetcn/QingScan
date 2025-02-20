{include file='public/head' /}

<?php
    $searchArr = [
        'action' => $_SERVER['REQUEST_URI'],
        'method' => 'get',
        'inputs' => [
            ['type' => 'text', 'name' => 'search', 'placeholder' =>'search'],
            ['type' => 'select', 'name' => 'plugin_id', 'options' => $pluginList, 'frist_option' => '插件列表'],
            ['type' => 'select', 'name' => 'app_id', 'options' => $projectList, 'frist_option' => '项目列表'],
        ],
    ];
?>
{include file='public/search' /}


<div class="col-md-12 ">
    <div class="row tuchu">
        <div class="col-md-12 ">
            <form class="row g-3">
                <div class="col-auto">
                    <a href="javascript:;" onclick="batch_del()"
                       class="btn btn-outline-success">批量删除</a>
                </div>
            </form>
            <table class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                    <th width="100">
                        <label>
                            <input type="checkbox" value="-1" onclick="quanxuan(this)">全选
                        </label>
                    </th>
                    <th>ID</th>
                    <th>所属项目</th>
                    <th>插件名称</th>
                    <th>审核状态</th>
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
                        <td><?php echo $value['app_name'] ?></td>
                        <td><?php echo $value['name'] ?></td>
                        <td>
                            <select class="changCheckStatus form-select" data-id="<?php echo $value['id'] ?>">
                                <option value="0" <?php echo $value['check_status'] == 0 ? 'selected' : ''; ?> >未审核
                                </option>
                                <option value="1" <?php echo $value['check_status'] == 1 ? 'selected' : ''; ?> >有效漏洞
                                </option>
                                <option value="2" <?php echo $value['check_status'] == 2 ? 'selected' : ''; ?> >无效漏洞
                                </option>
                            </select>
                        </td>
                        <td>
                            <a href="<?php echo url('details',['id'=>$value['id']])?>"
                               class="btn btn-sm btn-outline-success">查看详情</a>

                            <a href="<?php echo url('del', ['id' => $value['id']]) ?>"
                               class="btn btn-sm btn-outline-danger">删除</a>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
    <input type="hidden" id="to_examine_url" value="<?php echo url('to_examine/plugin_result') ?>">

    {include file='public/to_examine' /}
    {include file='public/fenye' /}
</div>
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