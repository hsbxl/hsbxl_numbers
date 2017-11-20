<?php


namespace Drupal\hsbxl_numbers\Controller;


use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\hsbxl_numbers\NumbersService;
use Drupal\hsbxl_members\MembershipService;
use Drupal\simplified_bookkeeping\BookkeepingService;


class Graph extends ControllerBase implements ContainerInjectionInterface {

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

  public function public($year) {
    return [
      '#markup' => '<div id="hsbxl_graph"></div>',
      '#attached' => [
        'library' => [
          'hsbxl_numbers/graph',
          drupal_get_path('module', 'plotly_js') . '/plotly_js/plotly_js',
        ],
      ],
    ];
  }
}