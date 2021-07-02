<section class="adding-post__quote tabs__content tabs__content--active">
                <h2 class="visually-hidden">Форма добавления цитаты</h2>
                <form class="adding-post__form form" action="<?= ($get_id) ? "add.php?categories-id={$get_id}": ""?>" method="post">
                  <div class="form__text-inputs-wrapper">
                    <div class="form__text-inputs">
                      <div class="adding-post__input-wrapper form__input-wrapper">
                        <label class="adding-post__label form__label" for="quote-heading">Заголовок <span class="form__input-required">*</span></label>
                        <div class="form__input-section <?=(isset($form_errors["quote-heading"])) ? "form__input-section--error":""?>">
                          <input class="adding-post__input form__input" id="quote-heading" type="text" name="quote-heading" placeholder="Введите заголовок" value="<?= ($filter_form_data['quote-heading'] && $form_errors) ? $filter_form_data['quote-heading'] : '' ?>">
                          <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                          <div class="form__error-text">
                            <h3 class="form__error-title">Данные некорректны</h3>
                            <p class="form__error-desc"><?=$form_errors["quote-heading"]?></p>
                          </div>
                        </div>
                      </div>
                      <div class="adding-post__input-wrapper form__textarea-wrapper">
                        <label class="adding-post__label form__label" for="post-quote">Текст цитаты <span class="form__input-required">*</span></label>
                        <div class="form__input-section <?=(isset($form_errors["post-quote"]) || isset($form_errors["quote-length-err"])) ? "form__input-section--error":""?>">
                          <textarea class="adding-post__textarea adding-post__textarea--quote form__textarea form__input" id="post-quote" name="post-quote" placeholder="Текст цитаты"><?= ($form_errors && $filter_form_data['post-quote']) ? $filter_form_data['post-quote'] : '' ?></textarea>
                          <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                          <div class="form__error-text">
                            <h3 class="form__error-title">Данные некорректны</h3>
                            <p class="form__error-desc"><?=$form_errors["post-quote"] ?></p>
                          </div>
                        </div>
                      </div>
                      <div class="adding-post__textarea-wrapper form__input-wrapper">
                        <label class="adding-post__label form__label" for="quote-author">Автор <span class="form__input-required">*</span></label>
                        <div class="form__input-section <?=(isset($form_errors["quote-author"])) ? "form__input-section--error":""?>">
                          <input class="adding-post__input form__input" id="quote-author" type="text" name="quote-author" placeholder="Автор цитаты" value="<?= ($form_errors && $filter_form_data['quote-author']) ? $filter_form_data['quote-author'] : '' ?>">
                          <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                          <div class="form__error-text">
                            <h3 class="form__error-title">Данные некорректны</h3>
                            <p class="form__error-desc"><?=$form_errors["quote-author"]?></p>
                          </div>
                        </div>
                      </div>
                      <div class="adding-post__input-wrapper form__input-wrapper">
                        <label class="adding-post__label form__label" for="quote-tags">Теги</label>
                        <div class="form__input-section <?=($form_errors["quote-tags"]) ? "form__input-section--error":""?>">
                          <input class="adding-post__input form__input" id="quote-tags" type="text" name="quote-tags" placeholder="Введите теги" value="<?= ($form_errors && $filter_form_data['quote-tags']) ? $filter_form_data['quote-tags'] : '' ?>">
                          <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                          <div class="form__error-text">
                            <h3 class="form__error-title">Данные некорректны</h3>
                            <p class="form__error-desc"><?=$form_errors["quote-tags"]?></p>
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