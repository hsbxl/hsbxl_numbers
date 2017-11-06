<?php

namespace Drupal\hsbxl_numbers\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Receipt edit forms.
 *
 * @ingroup hsbxl_numbers
 */
class ReceiptForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\hsbxl_numbers\Entity\Receipt */
    $form = parent::buildForm($form, $form_state);

    $entity = $this->entity;

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = &$this->entity;

    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Receipt.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Receipt.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.receipt.canonical', ['receipt' => $entity->id()]);
  }

}
