(function ($, Drupal) {

  var month = drupalSettings.simplified_bookkeeping_monthgraph.month;
  var year = drupalSettings.simplified_bookkeeping_monthgraph.year;
  var dataURL = "http://lcl.dashboard.hsbxl.be/numbers/monthfeed/" + year + "/" + month;

  $.getJSON( dataURL, {
    format: "json"
  }).done(function(data) {

    console.log(data);
    data.sales.sort(function(a,b){
      return new Date(b.date) - new Date(a.date);
    });

    var trace1 = {
      x: ['In: €' + data.getincomeTotal, 'Out: €' + Math.abs(data.getPurchasesTotal)],
      y: [data.getIncomeMemberships, null],
      name: 'In Memberships',
      type: 'bar',
      marker: {
        color: '#5E9732'
      }
    };

    var trace2 = {
      x: ['In: €' + data.getincomeTotal, 'Out: €' + Math.abs(data.getPurchasesTotal)],
      y: [data.getIncomeDonations, null],
      name: 'In Donations',
      type: 'bar',
      marker: {
        color: '#87BC5E'
      }
    };

    var trace3 = {
      x: ['In: €' + data.getincomeTotal, 'Out: €' + Math.abs(data.getPurchasesTotal)],
      y: [data.getIncomeFixedCosts, null],
      name: 'In fixed costs',
      type: 'bar',
      marker: {
        color: '#3C7113'
      }
    };

    var trace4 = {
      x: ['In: €' + data.getincomeTotal, 'Out: €' + Math.abs(data.getPurchasesTotal)],
      y: [data.getIncomeFoodDrinks, null],
      name: 'In Food & Drinks',
      type: 'bar',
      marker: {
        color: '#214B00'
      }
    };

    var trace5 = {
      x: ['In: €' + data.getincomeTotal, 'Out: €' + Math.abs(data.getPurchasesTotal)],
      y: [null, Math.abs(data.getPurchasesFoodDrinks)],
      name: 'Out Food & Drinks',
      type: 'bar',
      marker: {
        color: '#FF4132'
      }
    };

    var trace6 = {
      x: ['Income: €' + data.getincomeTotal, 'Out: €' + Math.abs(data.getPurchasesTotal)],
      y: [null, Math.abs(data.getPurchasesMaterial)],
      name: 'Out Material',
      type: 'bar',
      marker: {
        color: '#D4726A'
      }
    };

    var trace7 = {
      x: ['Income: €' + data.getincomeTotal, 'Out: €' + Math.abs(data.getPurchasesTotal)],
      y: [null, Math.abs(data.getPurchasesFixedCosts)],
      name: 'Out Fixed costs',
      type: 'bar',
      marker: {
        color: '#801D15'
      }
    };

    var graphdata = [trace7, trace6, trace5, trace4, trace3, trace2, trace1];
    var graphlayout = {barmode: 'stack'};

    Plotly.newPlot('hsbxl_graph', graphdata, graphlayout);


    $.each(data.sales, function (i, item) {
      var tags = item.tags.join(' ');
      $( "table#sales tbody" ).append(
          "<tr class=" + tags + "><td>" + item.date + "</td><td>" + item.amount + "</td><td>" + tags + "</td></tr>"
      );
    })

    $.each(data.purchases, function (i, item) {
      var tags = item.tags.join(' ');
      $( "table#purchases tbody" ).append(
          "<tr class=" + tags + "><td>" + item.date + "</td><td>" + item.amount + "</td><td>" + tags + "</td></tr>"
      );
    })


  });


})(jQuery, Drupal);