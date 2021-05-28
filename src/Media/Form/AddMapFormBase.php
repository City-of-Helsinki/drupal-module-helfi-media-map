<?php

declare(strict_types=1);

namespace Drupal\helfi_tpr\Media\Form;

use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\helfi_tpr\media\Source\ServiceMap;
use Drupal\media\MediaTypeInterface;
use Drupal\media_library\Form\AddFormBase;

/**
 * Base Add form for embedding external maps.
 */
abstract class AddMapFormBase extends AddFormBase {

  /**
   * Checks whether given media type is valid source.
   *
   * @param \Drupal\media\MediaTypeInterface $mediaType
   *   The media type to check.
   *
   * @return bool
   *   TRUE if valid source.
   */
  abstract protected function isValidSource(MediaTypeInterface $mediaType) : bool;

  /**
   * Checks if given URL is valid.
   *
   * @param string $url
   *   The url.
   *
   * @return bool
   *   TRUE if valid url.
   */
  abstract protected function isValidUrl(string $url) : bool;

  /**
   * {@inheritdoc}
   */
  protected function buildInputElement(array $form, FormStateInterface $form_state) : array {
    $container = [
      '#type' => 'container',
    ];
    $container['map_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Embed URL'),
    ];

    $container['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add'),
      '#button_type' => 'primary',
      '#submit' => ['::addButtonSubmit'],
      '#validate' => ['::validateUrl'],
      '#ajax' => [
        'callback' => '::updateFormCallback',
        'wrapper' => 'media-library-wrapper',
        // @todo Remove when https://www.drupal.org/project/drupal/issues/2504115
        // is fixed.
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
   * Validate callback for 'map_url' form field.
   *
   * @param array $form
   *   The form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state.
   */
  public function validateUrl(array &$form, FormStateInterface $form_state) : void {
    $url = $form_state->getValue('map_url', '');

    if (!$this->isValidUrl($url)) {
      $form_state->setErrorByName('map_url', $this->t('Invalid url.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function getMediaType(FormStateInterface $form_state) : MediaTypeInterface {
    if ($this->mediaType) {
      return $this->mediaType;
    }
    $mediaType = parent::getMediaType($form_state);

    if (!$mediaType->getSource() instanceof ServiceMap) {
      throw new \InvalidArgumentException('You can only add media types which use Service map source plugin.');
    }

    return $mediaType;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() : string {
    return 'service_map_add_form';
  }

}
