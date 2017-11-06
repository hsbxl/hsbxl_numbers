<?php

namespace Drupal\hsbxl_numbers;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Receipt entity.
 *
 * @see \Drupal\hsbxl_numbers\Entity\Receipt.
 */
class ReceiptAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\hsbxl_numbers\Entity\ReceiptInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished receipt entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published receipt entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit receipt entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete receipt entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add receipt entities');
  }

}
