<?php

namespace Drupal\zurb_components\Plugin\Field\FieldFormatter;

use Drupal\Component\Utility\Html;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'tab_reference_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "zurb_components_tabs",
 *   label = @Translation("Tab reference formatter"),
 *   field_types = {
 *     "entity_reference_revisions",
 *     "entity_reference"
 *   }
 * )
 */
class TabReferenceFormatter extends AccordionReferenceFormatter {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = parent::viewElements($items, $langcode);
    // unset($elements[0]['#attributes']['data-accordion']);
    // $elements[0]['#attributes']['data-responsive-accordion-tabs'] = 'tabs small-accordion medium-tabs large-tabs';
    return $elements;
  }

  /**
   * Generate the output appropriate for one field item.
   *
   * @param \Drupal\Core\Field\FieldItemInterface $item
   *   One field item.
   *
   * @return string
   *   The textual output generated.
   */
  protected function viewValue(FieldItemInterface $item) {
    // The text value has no text format assigned to it, so the user input
    // should equal the output, including newlines.
    return nl2br(Html::escape($item->value));
  }

}
