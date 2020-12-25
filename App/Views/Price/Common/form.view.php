<?php /** @var $data array */ ?>
<?php /** @var \App\Core\AAuthenticator $auth */ ?>

<tr>
    <th>Druh poplatku</th>
    <th>Cena</th>
    <th>Pridaj</th>
</tr>
<tr>
<td>
    <input type="text" class="form-control outlineButton" id="idName" name="name" placeholder="Názov poplatku" value="<?= is_null($data) ? "" : $data['data']['name'] ?>">
    <?php if (!is_null($data) && isset($data['errors']['name'])) {
        foreach ($data['errors']['name'] as $e) { ?>
            <div class="alert alert-danger" role="alert">
                <?= $e ?>
            </div>
        <?php }
    }?>
</td>
<td>
    <input type="text" class="form-control outlineButton" id="idPrice" name="price" placeholder="Cena" value="<?= is_null($data) ? "" : $data['data']['price'] ?>">
    <?php if (!is_null($data) && isset($data['errors']['price'])) {
        foreach ($data['errors']['price'] as $e) { ?>
            <div class="alert alert-danger" role="alert">
                <?= $e ?>
            </div>
        <?php }
    }?>
</td>
