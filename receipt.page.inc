<?php

/**
 * @file
 * Contains receipt.page.inc.
 *
 * Page callback for Receipt entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Receipt templates.
 *
 * Default template: receipt.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_receipt(array &$variables) {
  // Fetch Receipt Entity Object.
  $receipt = $variables['elements']['#receipt'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
