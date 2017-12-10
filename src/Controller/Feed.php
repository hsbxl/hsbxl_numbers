<?php


namespace Drupal\hsbxl_numbers\Controller;


use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Drupal\hsbxl_numbers\NumbersService;
use Drupal\hsbxl_members\MembershipService;
use Drupal\simplified_bookkeeping\BookkeepingService;


class Feed extends ControllerBase implements ContainerInjectionInterface {

  public function __construct(MembershipService $membership, BookkeepingService $bookkeeping, NumbersService $numbersService) {
    $this->membership = $membership;
    $this->bookkeeping = $bookkeeping;
    $this->numbers = $numbersService;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('hsbxl_members.membership'),
      $container->get('simplified_bookkeeping.bookkeeping'),
      $container->get('hsbxl_numbers.numbers')
    );
  }

  public function year($year) {
    $months = range(1, 12);

    foreach ($months as $month) {
      $this->numbers->setYear(floatval($year));
      $this->numbers->setMonth(floatval($month));

      $income_total = $this->numbers->getIncomeDonations()
        + $this->numbers->getIncomeFoodDrinks()
        + $this->numbers->getIncomeMemberships()
        + $this->numbers->getIncomeFixedCosts();

      $purchases_total = $this->numbers->getPurchasesFoodDrinks()
        + $this->numbers->getPurchasesMaterial()
        + $this->numbers->getPurchasesFixedCosts();

      $difference = $purchases_total + $income_total;

      $response_data[$month] = [
        'month' => $this->numbers->getMonth() . '/' . $this->numbers->getYear(),
        'getIncomeMemberships' => $this->numbers->getIncomeMemberships(),
        'getIncomeDonations' => $this->numbers->getIncomeDonations(),
        'getIncomeFoodDrinks' => $this->numbers->getIncomeFoodDrinks(),
        'getIncomeFixedCosts' => $this->numbers->getIncomeFixedCosts(),
        'getincomeTotal' => round($income_total, 2),
        'getPurchasesFoodDrinks' => $this->numbers->getPurchasesFoodDrinks(),
        'getPurchasesMaterial' => $this->numbers->getPurchasesMaterial(),
        'getPurchasesFixedCosts' => $this->numbers->getPurchasesFixedCosts(),
        'getPurchasesTotal' => round($purchases_total, 2),
        'getDifference' => round($difference, 2),
      ];
    }

    $response = new Response();
    $response->setContent(json_encode($response_data));
    $response->headers->set('Content-Type', 'application/json');
    return $response;
  }

  public function month($year, $month) {

    $this->numbers->setYear(floatval($year));
    $this->numbers->setMonth(floatval($month));

    $income_total = $this->numbers->getIncomeDonations()
      + $this->numbers->getIncomeFoodDrinks()
      + $this->numbers->getIncomeMemberships()
      + $this->numbers->getIncomeFixedCosts();

    $purchases_total = $this->numbers->getPurchasesFoodDrinks()
      + $this->numbers->getPurchasesMaterial()
      + $this->numbers->getPurchasesFixedCosts();

    $difference = $purchases_total + $income_total;


    $response_data = [
      'month' => $this->numbers->getMonth() . '/' . $this->numbers->getYear(),
      'getIncomeMemberships' => $this->numbers->getIncomeMemberships(),
      'getIncomeDonations' => $this->numbers->getIncomeDonations(),
      'getIncomeFoodDrinks' => $this->numbers->getIncomeFoodDrinks(),
      'getIncomeFixedCosts' => $this->numbers->getIncomeFixedCosts(),
      'getincomeTotal' => round($income_total, 2),
      'getPurchasesFoodDrinks' => $this->numbers->getPurchasesFoodDrinks(),
      'getPurchasesMaterial' => $this->numbers->getPurchasesMaterial(),
      'getPurchasesFixedCosts' => $this->numbers->getPurchasesFixedCosts(),
      'getPurchasesTotal' => round($purchases_total, 2),
      'getDifference' => round($difference, 2),
      'getMembersUnique' => $this->numbers->getMembersUnique(),
      'getMembersNew' => $this->numbers->getMembersNew(),
    ];

    $sales = $this->numbers->getSalesData();
    $response_data['sales'] = [];
    $i = 0;
    foreach ($sales['lines'] as $sale) {
      $response_data['sales'][$i] = [
        'date' => $sale['date'],
        'amount' => $sale['amount'],
        'tags' => [],
      ];

      foreach($sale['tags'] as $tag) {
        $response_data['sales'][$i]['tags'][] = $tag;
      }
      $i++;
    }

    $purchases = $this->numbers->getPurchasesData();
    $response_data['purchases'] = [];
    foreach ($purchases['lines'] as $purchase) {
      $response_data['purchases'][$purchase['id']] = [
        'date' => $purchase['date'],
        'amount' => $purchase['amount'],
        'tags' => [],
      ];

      foreach($purchase['tags'] as $tag) {
        $response_data['purchases'][$purchase['id']]['tags'][] = $tag;
      }
    }

    $response = new Response();
    $response->setContent(json_encode($response_data));
    $response->headers->set('Content-Type', 'application/json');
    return $response;
  }
}