<?php

namespace Drupal\hsbxl_numbers;

use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\hsbxl_members\MembershipService;
use Drupal\taxonomy\Entity\Term;
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
    return $data['total'];
  }

  public function getIncomeFoodDrinks() {
    $data = $this->getSalesData('food & drinks');
    return $data['total'];
  }

  public function getIncomeMemberships() {
    $data = $this->getSalesData('membership');
    return $data['total'];
  }

  public function getIncomeFixedCosts() {
    $data = $this->getSalesData('fixed costs');
    return $data['total'];
  }


  public function getPurchasesFoodDrinks() {
    $data = $this->getPurchasesData('food & drinks');
    return $data['total'];
  }

  public function getPurchasesMaterial() {
    $data = $this->getPurchasesData('material');
    return $data['total'];
  }

  public function getPurchasesFixedCosts() {
    $data = $this->getPurchasesData('fixed costs');
    return $data['total'];
  }


  public function getSalesData($term_label) {
    $tag = current(taxonomy_term_load_multiple_by_name($term_label, 'bookkeeping_tags'));
    if(!$tag) {
      return NULL;
    }
    $query = \Drupal::entityQuery('booking')
      ->condition('type', ['sale'], 'IN')
      ->condition('field_booking_date', $this->getDateStart(), '>')
      ->condition('field_booking_date', $this->getDateEnd(), '<')
      ->condition('field_booking_tags.target_id', $tag->id());

    $amount = 0;
    $i = 0;
    foreach ($query->execute() as $item) {
      $booking = BookingEntity::load($item);
      $amount = $amount + $booking->field_booking_amount->value;
      foreach($booking->get('field_booking_tags')->referencedEntities() as $tag) {
        $tags[] = $tag->id();
      }
      $lines[$i] = [
        'amount' => $booking->field_booking_amount->value,
        'memo' => $booking->field_booking_memo->value,
        'date' => $booking->field_booking_date->value,
        'valid' => $booking->field_booking_valid->value,
        'tags' => $tags,
      ];
    }

    $response = [
      'total' => $amount,
      'lines' => $lines,
    ];

    return $response;
  }

  public function getPurchasesData($term_label) {
    $tag = current(taxonomy_term_load_multiple_by_name($term_label, 'bookkeeping_tags'));
    if(!$tag) {
      return NULL;
    }
    $query = \Drupal::entityQuery('booking')
      ->condition('type', ['purchase'], 'IN')
      ->condition('field_booking_date', $this->getDateStart(), '>')
      ->condition('field_booking_date', $this->getDateEnd(), '<')
      ->condition('field_booking_tags.target_id', $tag->id());

    $amount = 0;
    foreach ($query->execute() as $item) {
      $booking = BookingEntity::load($item);
      $amount = $amount + $booking->field_booking_amount->value;
      $lines[] = [
        'amount' => $booking->field_booking_amount->value,
        'memo' => $booking->field_booking_memo->value,
        'date' => $booking->field_booking_date->value,
        'tags' => [],
        'valid' => $booking->field_booking_valid->value,
      ];
      foreach($booking->get('field_booking_tags')->referencedEntities() as $tag) {
        $lines['tags'][] = $tag->id();
      }
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











