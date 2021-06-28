<section class="adding-post__text tabs__content tabs__content--active">
                <h2 class="visually-hidden">Форма добавления текста</h2>
                <form class="adding-post__form form" action="<?= ($get_id) ? "add.php?categories-id={$get_id}": ""?>" method="post">
                  <div class="form__text-inputs-wrapper">
                    <div class="form__text-inputs">
                      <div class="adding-post__input-wrapper form__input-wrapper">
                        <label class="adding-post__label form__label" for="text-heading">Заголовок <span class="form__input-required">*</span></label>
                        <div class="form__input-section <?=(isset($form_errors["text-heading"])) ? "form__input-section--error":""?>">
                          <input class="adding-post__input form__input" id="text-heading" type="text" name="text-heading" placeholder="Введите заголовок" value="<?= ($filter_form_data['text-heading'] && $form_errors) ? $filter_form_data['text-heading'] : '' ?>">
                          <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                          <div class="form__error-text">
                            <h3 class="form__error-title">Данные некорректны</h3>
                            <p class="form__error-desc"><?=$form_errors["text-heading"]?></p>
                          </div>
                        </div>
                      </div>
                      <div class="adding-post__textarea-wrapper form__textarea-wrapper">
                        <label class="adding-post__label form__label" for="post-text">Текст поста <span class="form__input-required">*</span></label>
                        <div class="form__input-section <?=(isset($form_errors["post-text"])) ? "form__input-section--error":""?>">
                          <textarea class="adding-post__textarea form__textarea form__input" id="post-text" name="post-text" placeholder="Введите текст публикации" ><?= ($filter_form_data['post-text'] && $form_errors) ? $filter_form_data['post-text'] : '' ?></textarea>
                          <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                          <div class="form__error-text">
                            <h3 class="form__error-title">Данные некорректны</h3>
                            <p class="form__error-desc"><?=$form_errors["post-text"]?></p>
                          </div>
                        </div>
                      </div>
                      <div class="adding-post__input-wrapper form__input-wrapper">
                        <label class="adding-post__label form__label" for="text-tags">Теги</label>
                        <div class="form__input-section <?=(isset($form_errors["text-tags"])) ? "form__input-section--error":""?>">
                          <input class="adding-post__input form__input" id="text-tags" type="text" name="text-tags" placeholder="Введите теги" value="<?= ($filter_form_data['text-tags'] && $form_errors) ? $filter_form_data['text-tags'] : '' ?>">
                          <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                          <div class="form__error-text ">
                            <h3 class="form__error-title">Данные некорректны</h3>
                            <p class="form__error-desc"><?=$form_errors["text-tags"]?></p>
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