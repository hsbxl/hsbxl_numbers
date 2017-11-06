<?php

namespace Drupal\hsbxl_numbers;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Receipt entities.
 *
 * @ingroup hsbxl_numbers
 */
class ReceiptListBuilder extends EntityListBuilder {


  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Receipt ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\hsbxl_numbers\Entity\Receipt */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.receipt.edit_form',
      ['receipt' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
