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
    $this->month = $month;
  }



}











