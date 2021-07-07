<section class="adding-post__video tabs__content tabs__content--active">
                <h2 class="visually-hidden">Форма добавления видео</h2>
                <form class="adding-post__form form" action="<?= ($get_id) ? "add.php?categories-id={$get_id}": ""?>" method="post" enctype="multipart/form-data">
                  <div class="form__text-inputs-wrapper">
                    <div class="form__text-inputs">
                      <div class="adding-post__input-wrapper form__input-wrapper">
                        <label class="adding-post__label form__label" for="video-heading">Заголовок <span class="form__input-required">*</span></label>
                        <div class="form__input-section <?=(isset($form_errors["video-heading"])) ? "form__input-section--error":""?>">
                          <input class="adding-post__input form__input" id="video-heading" type="text" name="video-heading" placeholder="Введите заголовок" value="<?= ($form_errors && $filter_form_data["video-heading"]) ? $filter_form_data["video-heading"] : '' ?>">
                          <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                          <div class="form__error-text">
                            <h3 class="form__error-title">Данные некорректны</h3>
                            <p class="form__error-desc"><?=$form_errors["video-heading"]?></p>
                          </div>
                        </div>
                      </div>
                      <div class="adding-post__input-wrapper form__input-wrapper">
                        <label class="adding-post__label form__label" for="video-url">Ссылка youtube <span class="form__input-required">*</span></label>
                        <div class="form__input-section <?=(isset($form_errors["video-url"])) ? "form__input-section--error":""?>">
                          <input class="adding-post__input form__input" id="video-url" type="text" name="video-url" placeholder="Введите ссылку" value="<?= ($form_errors && $filter_form_data["video-url"]) ? $filter_form_data["video-url"] : '' ?>">
                          <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                          <div class="form__error-text">
                            <h3 class="form__error-title">Данные некорректны</h3>
                            <p class="form__error-desc"><?=$form_errors["video-url"]?></p>
                          </div>
                        </div>
                      </div>
                      <div class="adding-post__input-wrapper form__input-wrapper">
                        <label class="adding-post__label form__label" for="video-tags">Теги</label>
                        <div class="form__input-section <?=(isset($form_errors["video-tags"])) ? "form__input-section--error":""?>">
                          <input class="adding-post__input form__input" id="video-tags" type="text" name="video-tags" placeholder="Введите теги" value="<?= ($form_errors && $filter_form_data["video-tags"]) ? $filter_form_data["video-tags"] : '' ?>">
                          <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                          <div class="form__error-text">
                            <h3 class="form__error-title">Данные некорректны</h3>
                            <p class="form__error-desc"><?=$form_errors["video-tags"]?></p>
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