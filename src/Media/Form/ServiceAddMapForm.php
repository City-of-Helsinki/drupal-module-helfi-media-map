<?php

declare(strict_types=1);

namespace Drupal\helfi_tpr\Media\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\helfi_tpr\media\Source\ServiceMap;
use Drupal\media\MediaTypeInterface;

/**
 * Provides a media_library form for service map.
 */
final class ServiceAddMapForm extends AddMapFormBase {

  /**
   * {@inheritdoc}
   */
  protected function isValidSource(MediaTypeInterface $mediaType): bool {
    return $mediaType instanceof ServiceMap;
  }

  /**
   * {@inheritdoc}
   */
  public function validateUrl(
    array &$form,
    FormStateInterface $form_state
  ): void {
  }

}
