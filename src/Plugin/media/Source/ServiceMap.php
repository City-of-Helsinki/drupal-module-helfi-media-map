<?php

declare(strict_types = 1);

namespace Drupal\helfi_tpr\media\Source;

use Drupal\media\MediaInterface;
use Drupal\media\MediaSourceBase;

/**
 * Service map entity media source.
 *
 * @MediaSource(
 *   id = "service_map",
 *   label = @Translation("Service Map"),
 *   allowed_field_types = {"string", "string_long", "link"},
 *   description = @Translation("Provides business logic and metadata for Service map."),
 *   forms = {
 *     "media_library_add" = "\Drupal\helfi_tpr\Media\Form\ServiceMapForm"
 *   }
 * )
 */
final class ServiceMap extends MediaSourceBase {

  /**
   * {@inheritdoc}
   */
  public function getMetadataAttributes() : array {
    return [
      'map' => $this->t('Background map'),
      'level' => $this->t('List of services'),
      'city' => $this->t('City'),
      'transit' => $this->t('Show public transport stops (Zoom in the map to see the stops)'),
      'units' => $this->t('Show locations'),
      'service_node' => $this->t('The nodes on map'),
      'q' => $this->t('The search query'),
      'search_language' => $this->t('The search language'),
    ];
  }

  public function getMetadata(MediaInterface $media, $attribute_name) {
    // @todo
    return parent::getMetadata($media, $attribute_name);
  }

}
