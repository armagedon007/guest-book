<?php if($data['count'] > $data['limit']): ?>
    <div class="">
        <nav aria-label="...">
        <ul class="pagination  justify-content-center">
        
            <?php if($data['page'] == 1): ?>
            <li class="page-item disabled">
                <span class="page-link">&laquo;</span>
                <span class="page-link sr-only">Назад</span>
            </li>
            <?php else: ?>
            <li class="page-item">
                <a class="page-link" href="?page=1<?=$url?"&$url":''?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                    <span class="sr-only">Назад</span>
                </a>
            </li>
            <?php endif; ?>
            
            <?php for($i=1;$i<=ceil($data['count']/$data['limit']);$i++): ?>
                <?php if($data['page']==$i): ?>
                <li class="page-item active">
                    <span class="page-link">
                        <?= $i ?>
                        <span class="sr-only">(текущаяя)</span>
                    </span>
                </li>
                <?php else: ?>
                    <li class="page-item"><a class="page-link" href="/?page=<?=$i?><?=$url?"&$url":''?>"><?= $i ?></a></li>
                <?php endif; ?>
            <?php endfor; ?>
           
            
            <?php if($data['page'] == ($last =floor($data['count']/$data['limit']))): ?>
            <li class="page-item disabled">
                <span class="page-link">&raquo;</span>
                <span class="page-link sr-only">Вперед</span>
            </li>
            <?php else: ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?=$last?><?=$url?"&$url":''?>" aria-label="Next">
                    <span class="true">&raquo;</span>
                    <span class="sr-only">Вперед</span>
                </a>
            </li>
            <?php endif; ?>
            
        </ul>
        </nav>
    </div>
<?php endif; ?>