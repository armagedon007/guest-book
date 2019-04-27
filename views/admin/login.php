<div class="row justify-content-md-center form-login">
    <div class="col-6">
        <form id="login-form" action="/?route=admin/login" method="POST">
        <div class="form-group">
            <label for="exampleInputPassword1">Пароль</label>
            <input name="User[password]" type="password" class="form-control<?= $error ? ' is-invalid':'' ?>" id="exampleInputPassword1" placeholder="Пароль" required>
            <?php if($error):?>
            <div class="invalid-feedback">
                <?=$error ?>
            </div>
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary">Вход</button>
        </form>
    </div>
</div>