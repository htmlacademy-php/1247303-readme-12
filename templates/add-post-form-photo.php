<section class="adding-post__photo tabs__content tabs__content--active">
                <h2 class="visually-hidden">Форма добавления фото</h2>
                <form class="adding-post__form form" action="<?= ($get_id) ? "add.php?categories-id={$get_id}": ""?>" method="post" enctype="multipart/form-data">
                  <div class="form__text-inputs-wrapper">
                    <div class="form__text-inputs">
                      <div class="adding-post__input-wrapper form__input-wrapper">
                        <label class="adding-post__label form__label" for="photo-heading">Заголовок <span class="form__input-required">*</span></label>
                        <div class="form__input-section <?=(isset($form_errors["photo-heading"])) ? "form__input-section--error":""?>">
                          <input class="adding-post__input form__input" id="photo-heading" type="text" name="photo-heading" placeholder="Введите заголовок" value="<?= ($form_errors && $filter_form_data["photo-heading"]) ? $filter_form_data["photo-heading"] : '' ?>">
                          <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                          <div class="form__error-text">
                            <h3 class="form__error-title">Данные некорректны</h3>
                            <p class="form__error-desc"><?=$form_errors["photo-heading"]?></p>
                          </div>
                        </div>
                      </div>
                      <div class="adding-post__input-wrapper form__input-wrapper">
                        <label class="adding-post__label form__label" for="photo-url">Ссылка из интернета</label>
                        <div class="form__input-section <?=(isset($form_errors["photo-url"])) ? "form__input-section--error":""?>">
                          <input class="adding-post__input form__input" id="photo-url" type="text" name="photo-url" placeholder="Введите ссылку" value="<?= ($form_errors && $filter_form_data["photo-url"]) ? $filter_form_data["photo-url"] : '' ?>">
                          <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                          <div class="form__error-text">
                            <h3 class="form__error-title">Данные некорректны</h3>
                            <p class="form__error-desc"><?=$form_errors["photo-url"]?></p>
                          </div>
                        </div>
                      </div>
                      <div class="adding-post__input-wrapper form__input-wrapper">
                        <label class="adding-post__label form__label" for="photo-tags">Теги</label>
                        <div class="form__input-section <?=(isset($form_errors["photo-tags"])) ? "form__input-section--error":""?>">
                          <input class="adding-post__input form__input" id="photo-tags" type="text" name="photo-tags" placeholder="Введите теги" value="<?= ($form_errors && $filter_form_data["photo-tags"]) ? $filter_form_data["photo-tags"] : '' ?>">
                          <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                          <div class="form__error-text">
                            <h3 class="form__error-title">Данные некорректны</h3>
                            <p class="form__error-desc"><?=$form_errors["photo-tags"]?></p>
                          </div>
                        </div>
                      </div>
                      <div class="adding-post__input-wrapper form__input-wrapper">
                        <label for="file-photo" class="adding-post__label form__label">Выберите фото</label>
                        <div class="form__input-section <?=(isset($form_errors["file-photo"])) ? "form__input-section--error":""?>">
                          <input class="adding-post__input form__input" type="file" id="file-photo" name="file-photo">
                          <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                          <div class="form__error-text">
                            <h3 class="form__error-title">Данные некорректны</h3>
                            <p class="form__error-desc"><?=$form_errors["file-photo"]?></p>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php if($form_errors): ?>
                    <div class="form__invalid-block">
                      <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
                      <ul class="form__invalid-list">
                        <?php foreach($form_errors as $key => $value): ?>
                        <li class="form__invalid-item"><?=$value?></li>
                        <?php endforeach ?>
                      </ul>
                    </div>
                    <?php endif ?>
                  </div>
                  <div class="adding-post__buttons">
                    <button class="adding-post__submit button button--main" type="submit" name="submit" value="<?=$get_id?>">Опубликовать</button>
                    <a class="adding-post__close" href="#">Закрыть</a>
                  </div>
                </form>
              </section>