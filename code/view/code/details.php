{include file='public/head' /}
<?php
$dengjiArr = ['Low', 'Medium', 'High', 'Critical'];
$dengjiArrColor = ['Low' => 'secondary', 'Medium' => 'primary', 'High' => 'warning text-dark', 'Critical' => 'danger'];
?>

<div class="col-md-12 ">
    <div class="row tuchu">
        <div class="col-md-12" style="margin-bottom: 10px;"><h2 class="text-center">基本信息</h2>
            <hr>
        </div>

        <div class="col-md-4">
            <h5 style="align-content: center"><span style="color:#888">id:</span> <?php echo $info['id'] ?></h5></div>
        <div class="col-md-4">
            <h5><span style="color:#888">名称: </span><?php echo $info['name'] ?></h5></div>
        <div class="col-md-4">
            <h5><span style="color:#888">项目描述: </span><?php echo $info['desc'] ?></h5></div>
        <div class="col-md-4">
            <h5><span style="color:#888">ssh_url: </span><?php echo $info['ssh_url'] ?></h5></div>
        <div class="col-md-4">
            <h5><span style="color:#888">创建时间: </span><?php echo $info['create_time'] ?></h5></div>
        <div class="col-md-4">
            <h5><span style="color:#888">是否删除:</span> <?php echo $info['is_delete'] ?></h5></div>
        <div class="col-md-4">
            <h5><span style="color:#888">拉取方式:</span> <?php echo $info['pulling_mode'] ?></h5></div>
        <div class="col-md-4">
            <h5><span style="color:#888">所属用户:</span> <?php echo $info['user_id'] ?></h5></div>
        <div class="col-md-4">
            <h5><span style="color:#888">star:</span> <?php echo $info['star'] ?></h5></div>
        <div class="col-md-4">
            <h5><span style="color:#888">密码:</span> <?php echo $info['password'] ?></h5></div>
    </div>
    <div class="row tuchu">

        <div class="col-md-12" style="margin-bottom: 10px;"><h2 class="text-center">工具扫描动态</h2>
            <hr>
        </div>
        <div class="col-md-4">
            <h5><span style="color:#888">Fortify:</span> <?php echo $info['scan_time'] ?></h5>
        </div>
        <div class="col-md-4">
            <h5><span style="color:#888">SonarQube:</span> <?php echo $info['sonar_scan_time'] ?></h5>
        </div>
        <div class="col-md-4">
            <h5><span style="color:#888">kunlun_scan_time:</span> <?php echo $info['kunlun_scan_time'] ?></h5>
        </div>
        <div class="col-md-4">
            <h5><span style="color:#888">SonarQube:</span> <?php echo $info['semgrep_scan_time'] ?></h5>
        </div>
        <div class="col-md-4">
            <h5><span style="color:#888">composer组件:</span> <?php echo $info['composer_scan_time'] ?></h5>
        </div>
        <div class="col-md-4">
            <h5><span style="color:#888">java组件:</span> <?php echo $info['java_scan_time'] ?></h5>
        </div>
        <div class="col-md-4">
            <h5><span style="color:#888">Python组件:</span> <?php echo $info['python_scan_time'] ?></h5>
        </div>
        <div class="col-md-4">
            <h5><span style="color:#888">河马WebShell:</span> <?php echo $info['webshell_scan_time'] ?></h5>
        </div>
    </div>
</div>


<div class="row tuchu_margin">
    <div class="col-auto  tuchu_col">
        <h4 class="text-center">
            Fortify
        </h4>
        <table class="table table-bordered table-hover table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>漏洞类型</th>
                <th>危险等级</th>
                <th>污染来源</th>
                <th>执行位置</th>
                <th>所属项目</th>
                <th>创建时间</th>
                <th>状态</th>
            </tr>
            </thead>
            <?php foreach ($fortify as $value) {
                $value['Source'] = json_decode($value['Source'],true);
                $value['Primary'] = json_decode($value['Primary'],true);
                ?>
                <tr>
                    <td><?php echo $value['id'] ?></td>
                    <td><?php echo $value['Category'] ?></td>
                    <td>
                        <span class="badge rounded-pill bg-<?php echo $dengjiArrColor[$value['Friority']] ?>"><?php echo $value['Friority'] ?></span>
                    </td>
                    <td title="<?php echo htmlentities($value['Source']['Snippet'] ?? '') ?>">
                        <a href="<?php echo isset($projectArr[$value['code_id']]) ? $projectArr[$value['code_id']]['ssh_url'] : '' ?>/-/blob/master/<?php echo $value['Source']['FilePath'] ?? '' ?>"
                           target="_blank">
                            <?php echo $value['Source']['FileName'] ?? '' ?>
                        </a>
                    </td>
                    <td title="<?php echo htmlentities($value['Primary']['Snippet']) ?>">
                        <a href="<?php echo isset($projectArr[$value['code_id']]) ? $projectArr[$value['code_id']]['ssh_url'] : '' ?>/-/blob/master/<?php echo $value['Primary']['FilePath'] ?>"
                           target="_blank">
                            <?php echo $value['Primary']['FileName'] ?>
                        </a>
                    </td>
                    <td><a href="<?php echo U('code_check/bug_list', ['code_id' => $value['code_id']]) ?>">
                            <?php echo isset($projectArr[$value['code_id']]) ? $projectArr[$value['code_id']]['name'] : '' ?></a>
                    </td>
                    <td><?php echo $value['create_time'] ?></td>
                    <td><select class="changCheckStatus form-select" data-id="<?php echo $value['id'] ?>">
                            <option value="0" <?php echo $value['check_status'] == 0 ? 'selected' : ''; ?> >未审核</option>
                            <option value="1" <?php echo $value['check_status'] == 1 ? 'selected' : ''; ?> >有效漏洞
                            </option>
                            <option value="2" <?php echo $value['check_status'] == 2 ? 'selected' : ''; ?> >无效漏洞
                            </option>
                        </select></td>
                </tr>
            <?php } ?>
            <?php if (empty($fortify)) { ?>
                <tr>
                    <td colspan="8" class="text-center"><?php echo getScanStatus($info['id'], 'fortifyScan', 2); ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <div class="col-auto  tuchu_col">
        <h4 class="text-center">
            SemGrep
        </h4>
        <table class="table table-bordered table-hover table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>漏洞类型</th>
                <th>危险等级</th>
                <th>污染来源</th>
                <th>代码行号</th>
                <th>所属项目</th>
                <th>创建时间</th>
                <th>状态</th>
            </tr>
            </thead>
            <?php foreach ($semgrep as $value) {
                $project = getCodeInfo($value['code_id']);
                ?>
                <tr>
                    <td><?php echo $value['id'] ?></td>
                    <td><?php echo str_replace('data.tools.semgrep.', "", $value['check_id']) ?></td>
                    <td><?php echo $value['extra_severity'] ?></td>
                    <td>
                        <?php
                        $path = preg_replace("/\/data\/codeCheck\/[a-zA-Z0-9]*\//", "", $value['path']);
                        $url = getGitAddr($project['name'], $project['ssh_url'], $value['path'], $value['end_line']);
                        ?>
                        <a title="<?php echo htmlentities($value['extra_lines']) ?>" href="<?php echo $url ?>"
                           target="_blank"><?php echo $path ?>
                        </a>
                    </td>
                    <td>{$value['end_line']}</td>
                    <td><?php echo isset($projectArr[$value['code_id']]) ? $projectArr[$value['code_id']]['name'] : '' ?></td>
                    <td><?php echo $value['create_time'] ?></td>
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
                </tr>
            <?php } ?>
            <?php if (empty($semgrep)) { ?>
                <tr>
                    <td colspan="8" class="text-center"><?php echo getScanStatus($info['id'], 'crawlergoScan'); ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <div class="col-auto  tuchu_col">
        <h4 class="text-center">
            河马(WebShell)
        </h4>
        <table class="table table-bordered table-hover table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>类型</th>
                <th>文件路径</th>
                <th>扫描时间</th>
                <th>状态</th>
            </tr>
            </thead>
            <?php foreach ($hema as $value) { ?>
                <tr>
                    <td><?php echo $value['id'] ?></td>
                    <td><?php echo $value['type'] ?></td>
                    <td><?php echo str_replace('/data/codeCheck/', '', $value['filename']) ?></td>
                    <td><?php echo $value['create_time']; ?></td>
                    <td><select class="changCheckStatus form-select" data-id="<?php echo $value['id'] ?>">
                            <option value="0" <?php echo $value['check_status'] == 0 ? 'selected' : ''; ?> >未审核
                            </option>
                            <option value="1" <?php echo $value['check_status'] == 1 ? 'selected' : ''; ?> >有效漏洞
                            </option>
                            <option value="2" <?php echo $value['check_status'] == 2 ? 'selected' : ''; ?> >无效漏洞
                            </option>
                        </select>
                    </td>
                </tr>
            <?php } ?>
            <?php if (empty($hema)) { ?>
                <tr>
                    <td colspan="7"
                        class="text-center"><?php echo getScanStatus($info['id'], 'code_webshell_scan', 2); ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>

    <div class="col-auto  tuchu_col">
        <h4 class="text-center">
            JAVA
        </h4>
        <table class="table table-bordered table-hover table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>项目名称</th>
                <th>modelVersion</th>
                <th>groupId</th>
                <th>artifactId</th>
                <th>version</th>
                <th>时间</th>
            </tr>
            </thead>
            <?php foreach ($java as $value) { ?>
                <tr>
                    <td><?php echo $value['id'] ?></td>
                    <td><?php echo $value['code_id'] ?></td>
                    <td><?php echo $value['modelVersion'] ?></td>
                    <td><?php echo $value['groupId'] ?></td>
                    <td><?php echo $value['artifactId'] ?></td>
                    <td><?php echo $value['version'] ?></td>
                    <td><?php echo $value['create_time'] ?></td>
                </tr>
            <?php } ?>
            <?php if (empty($java)) { ?>
                <tr>
                    <td colspan="8" class="text-center"><?php echo getScanStatus($info['id'], 'code_java', 2); ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>


    <div class="col-auto  tuchu_col">
        <h4 class="text-center">
            python依赖
        </h4>
        <table class="table table-bordered table-hover table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>项目名称</th>
                <th>依赖库</th>
                <th>时间</th>
            </tr>
            </thead>
            <?php foreach ($python as $value) { ?>
                <tr>
                    <td><?php echo $value['id'] ?></td>
                    <td><?php echo $value['code_id'] ?></td>
                    <td><?php echo $value['name'] ?></td>
                    <td><?php echo $value['create_time'] ?></td>
                </tr>
            <?php } ?>
            <?php if (empty($python)) { ?>
                <tr>
                    <td colspan="8" class="text-center"><?php echo getScanStatus($info['id'], 'code_python', 2); ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>

    <div class="col-auto  tuchu_col">
        <h4 class="text-center">
            PHP依赖(Composer)
        </h4>
        <table class="table table-bordered table-hover table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>项目名称</th>
                <th>name</th>
                <th>version</th>
                <th>source</th>
                <th>require</th>
                <th>时间</th>
            </tr>
            </thead>
            <?php foreach ($php as $value) {
                ?>
                <tr>
                    <td><?php echo $value['id'] ?></td>
                    <td><?php echo $value['code_id'] ?></td>
                    <td><?php echo $value['name'] ?></td>
                    <td><?php echo $value['version'] ?></td>
                    <td><pre><?php echo $value['source'] ?></pre></td>
                    <td><pre><?php echo $value['require'] ?></pre></td>
                    <td><?php echo $value['create_time'] ?></td>
                </tr>
            <?php } ?>
            <?php if (empty($php)) { ?>
                <tr>
                    <td colspan="8" class="text-center"><?php echo getScanStatus($info['id'], 'code_php', 2); ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>

</div>
{include file='public/footer' /}
