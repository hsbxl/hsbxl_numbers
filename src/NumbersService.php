<?php

namespace Drupal\hsbxl_numbers;

use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\hsbxl_members\MembershipService;
use Drupal\taxonomy\Entity\Term;
use Drupal\Component\Utility\Html;
use Drupal\hsbxl_members\Entity\Membership;
use Drupal\simplified_bookkeeping\BookkeepingService;
use Drupal\simplified_bookkeeping\Entity\BookingEntity;

/**
 * Class NumbersService.
 */
class NumbersService {
  protected $year;
  protected $month;
  protected $entity_query;

  public function __construct(QueryFactory $entity_query, EntityManagerInterface $entityManager, BookkeepingService $bookkeeping, MembershipService $membership) {
    $this->entity_query = $entity_query;
    $this->entityManager = $entityManager;
    $this->bookkeeping = $bookkeeping;
    $this->membership = $membership;
  }


  public function setYear($year) {
    $this->year = $year;
  }

  public function setMonth($month) {
    $months = range(1, 12);
    if(in_array($month, $months)) {
      $this->month = $month;
    }
  }

  public function getYear() {
    return $this->year;
  }

  public function getMonth() {
    return $this->month;
  }


  public function getDateStart() {
    $date = new DrupalDateTime($this->year . '-' . $this->month . '-1');
    $date->setTimezone(new \DateTimezone(DATETIME_STORAGE_TIMEZONE));
    return $date->format('Y-m-01\T00:00:01');
  }

  public function getDateEnd() {
    $startdate = date($this->year . '-' . $this->month . '-1');
    $date = new DrupalDateTime($startdate);
    $date->setTimezone(new \DateTimezone(DATETIME_STORAGE_TIMEZONE));
    return $date->format('Y-m-t\T23:59:59');
  }


  public function getIncomeDonations() {
    $data = $this->getSalesData('donation');
    return round($data['total'], 2);
  }

  public function getIncomeFoodDrinks() {
    $data = $this->getSalesData('food & drinks');
    return round($data['total'], 2);
  }

  public function getIncomeMemberships() {
    $data = $this->getSalesData('membership');
    return round($data['total'], 2);
  }

  public function getIncomeFixedCosts() {
    $data = $this->getSalesData('fixed costs');
    return round($data['total'], 2);
  }

  public function getIncomeGoodies() {
    $data = $this->getSalesData('goodies');
    return round($data['total'], 2);
  }


  public function getPurchasesFoodDrinks() {
    $data = $this->getPurchasesData('food & drinks');
    return round($data['total'], 2);
  }

  public function getPurchasesMaterial() {
    $data = $this->getPurchasesData('material');
    return round($data['total'], 2);
  }

  public function getPurchasesGoodies() {
    $data = $this->getPurchasesData('goodies');
    return round($data['total'], 2);
  }

  public function getPurchasesFixedCosts() {
    $data = $this->getPurchasesData('fixed costs');
    return round($data['total'], 2);
  }


  public function getSalesData($term_label = '') {

    $query = \Drupal::entityQuery('booking')
      ->condition('type', ['sale'], 'IN')
      ->condition('field_booking_date', $this->getDateStart(), '>')
      ->condition('field_booking_date', $this->getDateEnd(), '<');

    if(!empty($term_label)) {
      $tag = current(taxonomy_term_load_multiple_by_name($term_label, 'bookkeeping_tags'));
      if(!$tag) {
        return NULL;
      }
      $query->condition('field_booking_tags.target_id', $tag->id());
    }

    $query->sort('field_booking_date' , 'ASC');

    $amount = 0;
    $i = 0;
    foreach ($query->execute() as $item) {
      $booking = BookingEntity::load($item);
      $amount = $amount + $booking->field_booking_amount->value;
      $tags = [];
      foreach($booking->get('field_booking_tags')->referencedEntities() as $tag) {
        $tags[] = Html::getClass($tag->label());
      }
      $lines[] = [
        'id' => $booking->id(),
        'amount' => $booking->field_booking_amount->value,
        'memo' => $booking->field_booking_memo->value,
        'date' => $booking->field_booking_date->value,
        'valid' => $booking->field_booking_valid->value,
        'tags' => $tags,
      ];
      $i++;
    }

    $response = [
      'total' => $amount,
      'lines' => $lines,
    ];

    return $response;
  }

  public function getPurchasesData($term_label = '') {
    $query = \Drupal::entityQuery('booking')
      ->condition('type', ['purchase'], 'IN')
      ->condition('field_booking_date', $this->getDateStart(), '>')
      ->condition('field_booking_date', $this->getDateEnd(), '<');

    if(!empty($term_label)) {
      $tag = current(taxonomy_term_load_multiple_by_name($term_label, 'bookkeeping_tags'));
      if(!$tag) {
        return NULL;
      }
      $query->condition('field_booking_tags.target_id', $tag->id());
    }

    $amount = 0;
    $i = 0;
    foreach ($query->execute() as $item) {
      $booking = BookingEntity::load($item);
      $amount = $amount + $booking->field_booking_amount->value;
      $tags = [];
      foreach($booking->get('field_booking_tags')->referencedEntities() as $tag) {
        $tags[] = Html::getClass($tag->label());
      }
      $lines[$i] = [
        'id' => $booking->id(),
        'amount' => $booking->field_booking_amount->value,
        'memo' => $booking->field_booking_memo->value,
        'date' => $booking->field_booking_date->value,
        'valid' => $booking->field_booking_valid->value,
        'tags' => $tags,
      ];
      $i++;
    }

    $response = [
      'total' => $amount,
      'lines' => $lines,
    ];

    return $response;
  }

  public function getMembersNew() {

  }

  public function getMembersReg() {

  }

  public function getMembersSoc() {

  }

  public function getMembersStopped() {

  }

}











