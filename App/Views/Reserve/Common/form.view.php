 <p class="card-text">
                    <label for="idName" class="font-weight-bold">Názov</label>
                    <input type="text" class="form-control outlineButton colorBlack" id="idName" name="name" placeholder="Názov knihy" value="<?= is_null($data) || !isset($data['data']) ? "" : $data['data']['name'] ?>">
                </p>
                <?php if (!is_null($data) && isset($data['errors']['name'])) {
                    foreach ($data['errors']['name'] as $e) { ?>
                        <div class="alert alert-danger" role="alert">
                            <?= $e ?>
                        </div>
                    <?php }
                }?>

                <p class="card-text">
                    <label for="idAuthorName" class="font-weight-bold">Meno autora</label>
                    <input type="text" class="form-control outlineButton colorBlack" name="author_name" id="idAuthorName" placeholder="Meno autora" value="<?= is_null($data) || !isset($data['data']) ? "" : $data['data']['author_name'] ?>">
                </p>
                <?php if (!is_null($data) && isset($data['errors']['author_name'])) {
                    foreach ($data['errors']['author_name'] as $e) { ?>
                        <div class="alert alert-danger" role="alert">
                            <?= $e ?>
                        </div>
                    <?php }
                }?>
                <p class="card-text">
                    <label for="idAuthorSurname" class="font-weight-bold">Priezvisko autora</label>
                    <input type="text" class="form-control outlineButton colorBlack" name="author_surname" id="idAuthorSurname" placeholder="Priezvisko autora" value="<?= is_null($data) || !isset($data['data']) ? "" : $data['data']['author_surname'] ?>">
                </p>
                <?php if (!is_null($data) && isset($data['errors']['author_surname'])) {
                    foreach ($data['errors']['author_surname'] as $e) { ?>
                        <div class="alert alert-danger" role="alert">
                            <?= $e ?>
                        </div>
                    <?php }
                }?>

                <p class="card-text">
                    <label for="idReleaseYear" class="font-weight-bold">Rok vydania</label>
                    <input type="text" class="form-control outlineButton colorBlack" name="release_year" id="idReleaseYear" placeholder="Rok vydania" value="<?= is_null($data) || !isset($data['data']) ? "" : $data['data']['release_year'] ?>">
                </p>
                <?php if (!is_null($data) && isset($data['errors']['release_year'])) {
                    foreach ($data['errors']['release_year'] as $e) { ?>
                        <div class="alert alert-danger" role="alert">
                            <?= $e ?>
                        </div>
                    <?php }
                }?>

                <p class="card-text">
                    <label for="idInfo" class="font-weight-bold">Informácie o knihe</label>
                    <textarea class="form-control outlineButton colorBlack" name="info" id="idInfo" cols="50" rows="10" placeholder="Sem napíšte informácie o knihe"><?= is_null($data) || !isset($data['data']) ? "" : $data['data']['info']; ?></textarea>
                </p>
                <?php if (!is_null($data) && isset($data['errors']['info'])) {
                    foreach ($data['errors']['info'] as $e) { ?>
                        <div class="alert alert-danger" role="alert">
                            <?= $e ?>
                        </div>
                    <?php }
                }?>

                <p class="card-text">
                    <label for="idGenre" class="font-weight-bold">Žáner</label>
                    <select class="form-control outlineButton colorBlack" name="genre" id="idGenre">
                        <?php foreach ($data['genres'] as $genre) {?>
                            <?php /** @var $genre \App\Models\Genre */ ?>
                            <option value="<?= $genre->getGenreId(); ?>" <?= is_null($data) || !isset($data['data']) || $data['data']['genre'] != $genre->getGenreId() ? "" : "selected" ?>  > <?= $genre->getName(); ?></option>
                        <?php } ?>
                    </select>
                </p>
                <?php if (!is_null($data) && isset($data['errors']['genre'])) {
                    foreach ($data['errors']['genre'] as $e) { ?>
                        <div class="alert alert-danger" role="alert">
                            <?= $e ?>
                        </div>
                    <?php }
                }?>