<?php

namespace Drupal\date_month_select\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\datetime\Plugin\Field\FieldWidget\DateTimeWidgetBase;

/**
 * Plugin implementation of the 'date_month_select' widget.
 *
 * @FieldWidget(
 *   id = "date_month_select",
 *   label = @Translation("Select list month"),
 *   field_types = {
 *     "datetime"
 *   }
 * )
 */
class DateTimeMonthSelectWidget extends DateTimeWidgetBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'date_order' => 'MY',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element = parent::formElement($items, $delta, $element, $form, $form_state);

    // Wrap all of the select elements with a fieldset.
    $element['#theme_wrappers'][] = 'fieldset';

    $date_order = $this->getSetting('date_order');

    // Set up the date part order array.
    switch ($date_order) {
      case 'YM':
        $date_part_order = ['year', 'month'];
        break;

      case 'MY':
        $date_part_order = ['month', 'year'];
        break;
    }

    $element['value'] = [
      '#type' => 'datelist',
      '#date_part_order' => $date_part_order,
    ] + $element['value'];

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element = parent::settingsForm($form, $form_state);

    $element['date_order'] = [
      '#type' => 'select',
      '#title' => t('Date part order'),
      '#default_value' => $this->getSetting('date_order'),
      '#options' => ['MY' => t('Month/Year'), 'YM' => t('Year/Month')],
    ];

    if ($this->getFieldSetting('datetime_type') !== 'datetime') {
      $element['time_type'] = [
        '#type' => 'hidden',
        '#value' => 'none',
      ];
    }

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];

    $summary[] = t('Date part order: @order', ['@order' => $this->getSetting('date_order')]);

    return $summary;
  }

}
