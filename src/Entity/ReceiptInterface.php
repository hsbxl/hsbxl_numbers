<?php

namespace Drupal\hsbxl_numbers\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Receipt entities.
 *
 * @ingroup hsbxl_numbers
 */
interface ReceiptInterface extends  ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Receipt name.
   *
   * @return string
   *   Name of the Receipt.
   */
  public function getName();

  /**
   * Sets the Receipt name.
   *
   * @param string $name
   *   The Receipt name.
   *
   * @return \Drupal\hsbxl_numbers\Entity\ReceiptInterface
   *   The called Receipt entity.
   */
  public function setName($name);

  /**
   * Gets the Receipt creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Receipt.
   */
  public function getCreatedTime();

  /**
   * Sets the Receipt creation timestamp.
   *
   * @param int $timestamp
   *   The Receipt creation timestamp.
   *
   * @return \Drupal\hsbxl_numbers\Entity\ReceiptInterface
   *   The called Receipt entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Receipt published status indicator.
   *
   * Unpublished Receipt are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Receipt is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Receipt.
   *
   * @param bool $published
   *   TRUE to set this Receipt to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\hsbxl_numbers\Entity\ReceiptInterface
   *   The called Receipt entity.
   */
  public function setPublished($published);

}
