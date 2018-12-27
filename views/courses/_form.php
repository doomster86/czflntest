<form method="post" action="">
    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
    <div class="row">
        <div class="col-xs-6">
            <input type="number" name="modules_count" class="form-control" min="1" value="" placeholder="Кількість тижнів" required>
        </div>
        <div class="col-xs-6">
            <button type="submit" class="btn btn-success">Створити РНП</button>
        </div>

    </div>
</form>