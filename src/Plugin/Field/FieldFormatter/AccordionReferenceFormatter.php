<?php

namespace Drupal\zurb_components\Plugin\Field\FieldFormatter;

use Drupal\Component\Utility\Html;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'tab_reference_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "zurb_components_accordion",
 *   label = @Translation("Accordion reference formatter"),
 *   field_types = {
 *     "entity_reference_revisions",
 *     "entity_reference"
 *   }
 * )
 */
class AccordionReferenceFormatter extends ViewModeBaseFormatter {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    $elements[0] = [
      '#theme' => 'container',
      '#attributes' => [
        'class' => ['accordion'],
      ],
      '#children' => [],
    ];

    foreach ($items as $delta => $item) {
      $view_mode = $this->getFormatterSettings($item->entity->bundle(), 'view_mode');
      $title = $item->entity->get($this->getFormatterSettings($item->entity->bundle(), 'title'));

      $elements[0]['#children'][] = [
        'title' => [
          '#theme' => 'container',
          '#attributes' => [
            'class' => ['accordion-item', ($delta == 0) ? 'is-active' : ''],
            'data-accordion-item' => 'data-accordion-item',
          ],
          '#children' => [
            '#markup' => '<a href="#" class="accordion-title"> ' . $title->value . '</a>',
            'content' => [
              '#theme' => 'container',
              '#attributes' => [
                'class' => ['accordion-content'],
                'data-tab-content' => 'data-tab-content',
              ],
              '#children' => [
                'content' => entity_view($item->entity, $view_mode),
              ],
            ],
          ],
        ],
      ];
    }
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
