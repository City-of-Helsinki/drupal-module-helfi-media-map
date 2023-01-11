<?php

declare(strict_types = 1);

namespace Drupal\helfi_media_map\Form;

use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\media_library\Form\AddFormBase;
use Drupal\helfi_media_map\Plugin\media\Source\Map;
use League\Uri\Http;

/**
 * {@inheritDoc}
 */
class HelfiMediaMapAddForm extends AddFormBase {

  /**
   * {@inheritDoc}
   */
  protected function buildInputElement(array $form, FormStateInterface $form_state) {
    $container = [
      '#type' => 'container',
    ];
    $container['helfi_media_map_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Map embed URL'),
      '#description' => $this->t('Enter the map embed URL from @kartta or @palvelukartta.', [
        '@kartta' => Link::fromTextAndUrl('https://kartta.hel.fi/', Url::fromUri('https://kartta.hel.fi/', ['attributes' => ['target' => '_blank']]))->toString(),
        '@palvelukartta' => Link::fromTextAndUrl('https://palvelukartta.hel.fi/fi/', Url::fromUri('https://palvelukartta.hel.fi/fi/', ['attributes' => ['target' => '_blank']]))->toString(),
      ]),
      '#maxlength' => 2048,
    ];

    $container['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add'),
      '#button_type' => 'primary',
      '#submit' => ['::addButtonSubmit'],
      '#ajax' => [
        'callback' => '::updateFormCallback',
        'wrapper' => 'media-library-wrapper',
        // @todo Remove when https://www.drupal.org/project/drupal/issues/2504115 is fixed.
        'url' => Url::fromRoute('media_library.ui'),
        'options' => [
          'query' => $this->getMediaLibraryState($form_state)->all() + [
            FormBuilderInterface::AJAX_FORM_REQUEST => TRUE,
          ],
        ],
      ],
    ];

    $form['container'] = $container;

    return $form;
  }

  /**
   * Validates the map url.
   *
   * @param array $form
   *   The form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state.
   *
   * @return void|TRUE
   */
  public function validateMapUrl(array &$form, FormStateInterface $form_state) {
    $url = $form_state->getValue('helfi_media_map_url');
    $host = Http::createFromString($url)->getHost();

    if (!in_array($host, Map::VALID_URLS)) {
      $form_state->setErrorByName('url', $this->t('Given host @host is not valid, must be one of: @domains', [
        '@host' => $host,
        '@domains' => implode(', ', Map::VALID_URLS),
      ]));
    }
  }

  /**
   * {@inheritDoc}
   */
  public function addButtonSubmit(array $form, FormStateInterface $form_state) {
    $this->processInputValues([$form_state->getValue('helfi_media_map_url')], $form, $form_state);
  }

  /**
   * {@inheritDoc}
   */
  public function getFormId() {
    return 'helfi_media_map_add_form';
  }

}
