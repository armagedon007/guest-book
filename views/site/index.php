<?php 
$js = <<< JS

  success = function(result){
                if(result.status == 'success'){
                    location = '/?route=site/index';
                }
            };

JS;
?>
<script><?= $js ?></script>
<div class="container main-block">
    <div class="row">
        <div class="col-12">
            <div class="row d-flex justify-content-between">
                <h4>Список статей</h4>
                <button class="btn btn-success" data-toggle="modal" data-target="#add-post">Добавить статью</button>
            </div>
            <div class="row form-filter">
              <form action="/?route=site/index" class="row col-12">
                <select name="type" class="form-control col-6">
                  <option value=""<?=$model->type === NULL?' selected="selected"':''?>>Все</option>
                  <?php foreach($model->typeItems as $id=>$item): ?>
                    <option value="<?= $id ?>"<?=$id==$model->type?' selected="selected"':''?>><?= $item ?></option>
                  <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-primary offset-1 col-2">Фильтр</button>
              </form>
            </div>
            <?php if(isset($data['count']) && $data['count']): ?>
              
              <div class="row">Найденно статей: <?= (($data['page']-1)*$data['limit']+1) ?>-<?= min($data['count'], $data['page']*$data['limit']) ?> из <?= $data['count'] ?>.</div>
              <div class="row">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col"><?= $model->textLabel ?></th>
                      <th scope="col"><?= $model->typeLabel ?></th>
                      <th scope="col"><?= $model->photoLabel ?></th>
                      <th scope="col"><?= $model->dateAtLabel ?></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($data['items'] as $i=>$item): ?>
                      <tr>
                        <th scope="row"><?= $i+(($data['page']-1)*$data['limit'])+1 ?></th>
                        <td><div class="text"><?= $item['text'] ?></div></td>
                        <td><?= $model->getItemType($item['type']) ?></td>
                        <td>
                          <div class="post-photo">
                            <?php if( is_file(App::$app->getBasePath() . DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR . $item['photo']) ):  ?>
                            <a href="<?= $item['photo']?>" target="_blank"><img src="<?= $item['photo']?>" alt="..." class="img-thumbnail"></a>
                            <?php else: ?>
                              -
                            <?php endif; ?>
                          </div>
                        </td>
                        <td><?= date('d.m.Y H:i:s', $item['dateAt']) ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
                
                <?= $pagination ?>
              </div>
            <?php else: ?>
              <div class="row">Статей не найденно</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="add-post" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Добавить статью</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="add-post-form" action="/?route=site/addpost" methos="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="post-text"><?= $model->textLabel ?></label>
                <textarea class="form-control" id="post-text" rows="3" name="Post[text]" required></textarea>
            </div>
            <?php foreach($model->typeItems as $key=>$label): ?>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="Post[type]" id="post-type-<?= $key ?>" value="<?= $key?>"<?= key($model->typeItems) == $key ? ' checked="checked"':''?>>
                <label class="form-check-label" for="post-type-<?= $key ?>"><?= $label ?></label>
            </div>
            <?php endforeach; ?>
            <div class="form-group">
                <label for="post-image">&nbsp;</label>
                <input type="file" class="form-control-file" id="post-image" name="photo">
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
        <button id="save-post" type="button" class="btn btn-primary">Сохранить</button>
      </div>
    </div>
  </div>
</div>