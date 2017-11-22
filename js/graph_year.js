(function ($, Drupal) {


  var dataURL = "http://lcl.dashboard.hsbxl.be/numbers/yearfeed";
  $.getJSON( dataURL, {
    format: "json"
  }).done(function( data ) {

    console.log(data);

    var incomeTotal = [];
    var incomeMemberships = [];
    var incomeDonations = [];
    var incomeFoodDrinks = [];
    var incomeFixedCosts = [];
    var spendingFoodDrinks = [];
    var spendingMaterial = [];
    var spendingFixedCosts = [];
    var spendingTotal = [];

    $.each(data, function(i, item) {
      incomeTotal.push(item.getincomeTotal);
      incomeMemberships.push(item.getIncomeMemberships);
      incomeDonations.push(item.getIncomeDonations);
      incomeFixedCosts.push(item.getIncomeFixedCosts);
      incomeFoodDrinks.push(item.getIncomeFoodDrinks);

      spendingFoodDrinks.push(item.getPurchasesFoodDrinks);
      spendingMaterial.push(item.getPurchasesMaterial);
      spendingFixedCosts.push(item.getPurchasesFixedCosts);

      purchase = Math.abs(item.getPurchasesTotal);
      spendingTotal.push(purchase);
    });

    var income = {
      x: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Aug', 'Sep', 'Okt', 'Nov', 'Dec'],
      y: incomeTotal,
      name: 'Income',
      marker: {color: 'rgb(46, 255, 0)'},
      type: 'bar'
    };

    var purchases = {
      x: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Aug', 'Sep', 'Okt', 'Nov', 'Dec'],
      y: spendingTotal,
      name: 'Spending',
      marker: {color: 'rgb(255, 0, 0)'},
      type: 'bar'
    };

    layout = {
      barmode: 'group',
      title: 'Books HSBXL',
    };




    /*var inMemberships = {
      x: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Aug', 'Sep', 'Okt', 'Nov', 'Dec'],
      y: incomeMemberships,
      name: 'Income Memberships',
      type: 'bar'
    };

    var inDonations = {
      x: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Aug', 'Sep', 'Okt', 'Nov', 'Dec'],
      y: incomeDonations,
      name: 'Income Donations',
      type: 'bar'
    };

    var inFixedCosts = {
      x: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Aug', 'Sep', 'Okt', 'Nov', 'Dec'],
      y: incomeFixedCosts,
      name: 'Income fixed costs',
      type: 'bar'
    };

    var inFoodDrinks= {
      x: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Aug', 'Sep', 'Okt', 'Nov', 'Dec'],
      y: incomeFoodDrinks,
      name: 'Income food & drinks',
      type: 'bar'
    };


    var outFoodDrinks = {
      x: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Aug', 'Sep', 'Okt', 'Nov', 'Dec'],
      y: spendingFoodDrinks,
      name: 'Income Memberships',
      type: 'bar'
    };

    var outMaterial = {
      x: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Aug', 'Sep', 'Okt', 'Nov', 'Dec'],
      y: spendingMaterial,
      name: 'Income Memberships',
      type: 'bar'
    };

    var outFixedCosts = {
      x: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Aug', 'Sep', 'Okt', 'Nov', 'Dec'],
      y: spendingFixedCosts,
      name: 'Income Memberships',
      type: 'bar'
    };

    layout = {
      barmode: 'stack',
      title: 'Books HSBXL',
    };*/




    Plotly.newPlot('hsbxl_graph', [income, purchases], layout);
  });


})(jQuery, Drupal);