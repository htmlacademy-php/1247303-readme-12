<section class="adding-post__link tabs__content tabs__content--active">
                <h2 class="visually-hidden">Форма добавления ссылки</h2>
                <form class="adding-post__form form" action="<?= ($get_id) ? "add.php?categories-id={$get_id}" : ""?>" method="post">
                  <div class="form__text-inputs-wrapper">
                    <div class="form__text-inputs">
                      <div class="adding-post__input-wrapper form__input-wrapper">
                        <label class="adding-post__label form__label" for="title">Заголовок <span class="form__input-required">*</span></label>
                        <div class="form__input-section <?=(isset($form_errors["title"])) ? "form__input-section--error" : ""?>">
                          <input class="adding-post__input form__input" id="title" type="text" name="title" placeholder="Введите заголовок" value="<?= ($form_errors && $filter_form_data["title"]) ? $filter_form_data["title"] : '' ?>">
                          <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                          <div class="form__error-text">
                            <h3 class="form__error-title">Данные некорректны</h3>
                            <p class="form__error-desc"><?=$form_errors["title"]?></p>
                          </div>
                        </div>
                      </div>
                      <div class="adding-post__textarea-wrapper form__input-wrapper">
                        <label class="adding-post__label form__label" for="site_path">Ссылка <span class="form__input-required">*</span></label>
                        <div class="form__input-section <?=(isset($form_errors["site_path"])) ? "form__input-section--error" : ""?>">
                          <input class="adding-post__input form__input" id="site_path" type="text" name="site_path" value="<?= ($form_errors && $filter_form_data["site_path"]) ? $filter_form_data["site_path"] : '' ?>">
                          <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                          <div class="form__error-text">
                            <h3 class="form__error-title">Данные некорректны</h3>
                            <p class="form__error-desc"><?=$form_errors["site_path"]?></p>
                          </div>
                        </div>
                      </div>
                      <div class="adding-post__input-wrapper form__input-wrapper">
                        <label class="adding-post__label form__label" for="link-tags">Теги</label>
                        <div class="form__input-section <?=(isset($form_errors["link-tags"])) ? "form__input-section--error" : ""?>">
                          <input class="adding-post__input form__input" id="link-tags" type="text" name="link-tags" placeholder="Введите теги" value="<?= ($form_errors && $filter_form_data["link-tags"]) ? $filter_form_data["link-tags"] : '' ?>">
                          <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                          <div class="form__error-text">
                            <h3 class="form__error-title">Данные некорректны</h3>
                            <p class="form__error-desc"><?=$form_errors["link-tags"]?></p>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php if ($form_errors): ?>
                    <div class="form__invalid-block">
                      <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
                      <ul class="form__invalid-list">
                        <?php foreach ($form_errors as $key => $value): ?>
                        <li class="form__invalid-item"><?=$value?></li>
                        <?php endforeach ?>
                      </ul>
                    </div>
                    <?php endif ?>
                  </div>
                  <div class="adding-post__buttons">
                    <button class="adding-post__submit button button--main" type="submit" name="type_id" value="<?=$get_id?>">Опубликовать</button>
                    <a class="adding-post__close" href="index.php">Закрыть</a>
                  </div>
                </form>
              </section>