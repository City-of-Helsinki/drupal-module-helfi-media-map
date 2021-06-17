<?php

namespace Drupal\helfi_media_map\Form;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\media_library\Form\AddFormBase;
use Drupal\media_library\MediaLibraryUiBuilder;
use Drupal\media_library\OpenerResolverInterface;

/**
 * {@inheritDoc}
 */
class HelfiMediaMapAddForm extends AddFormBase {

  /**
   * Constructs a AddFormBase object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\media_library\MediaLibraryUiBuilder $library_ui_builder
   *   The media library UI builder.
   * @param \Drupal\media_library\OpenerResolverInterface $opener_resolver
   *   The opener resolver.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, MediaLibraryUiBuilder $library_ui_builder, OpenerResolverInterface $opener_resolver) {
    parent::__construct($entity_type_manager, $library_ui_builder, $opener_resolver);
  }

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
