<?php

namespace Drupal\zurb_components\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Base for formatter with view mode.
 */
abstract class ViewModeBaseFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return array(
      // Implement default settings.
      'settings' => [],
    ) + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $bundles = $this->getFieldSetting('handler_settings')['target_bundles'];
    $target_type = $this->getFieldSettings()['target_type'];

    foreach ($bundles as $bundle) {
      $elements['settings'][$bundle] = [
        '#type' => 'fieldset',
        '#title' => t('Config for @bundle', ['@bundle' => $bundle]),
      ];

      $elements['settings'][$bundle]['view_mode'] = [
        '#title' => $this->t('View mode'),
        '#type' => 'select',
        '#options' => $this->getViewModes($target_type, $bundle, $form, $form_state),
        '#default_value' => $this->getSetting('settings')[$bundle]['view_mode'],
        '#description' => t('Text that will be shown inside the field until a value is entered. This hint is usually a sample value or a brief description of the expected format.'),
      ];
      $elements['settings'][$bundle]['title'] = [
        '#title' => $this->t('Title target field'),
        '#type' => 'select',
        '#default_value' => $this->getSetting('settings')[$bundle]['title'],
        '#options' => $this->getFields($target_type, $bundle, $form, $form_state),
      ];
    }

    return array(
      // Implement settings form.
      'settings' => $elements['settings'],
    ) + parent::settingsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getFormatterSettings($bundle, $key) {
    return $this->getSetting('settings')[$bundle][$key];
  }

  /**
   * {@inheritdoc}
   */
  protected function getViewModes($type, $bundle, array $form, FormStateInterface $form_state) {
    $viewModes = \Drupal::entityTypeManager()->getStorage('entity_view_display')->loadByProperties(['bundle' => $bundle, 'targetEntityType' => $type]);
    $options = [];
    foreach (array_keys($viewModes) as $key) {
      $key = explode('.', $key);
      $key = array_pop($key);
      $options[$key] = $key;
    }
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  protected function getFields($type, $bundle, array $form, FormStateInterface $form_state) {
    $options = [];
    $entityFieldManager = \Drupal::service('entity_field.manager');
    $fields = $entityFieldManager->getFieldDefinitions($type, $bundle);

    foreach ($fields as $field) {
      $id = explode('.', $field->getConfig($bundle)->id());
      $id = array_pop($id);
      $options[$id] = $field->getLabel();
    }

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    $mode = $this->getSetting('view_mode');
    $summary[] = 'View mode: ' . $mode;
    return $summary;
  }

}
