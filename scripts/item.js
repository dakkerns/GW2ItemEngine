var url = window.location.href;
var val = url.substring(url.lastIndexOf('/') + 1);

function coinImages(itemPrice){
	if(itemPrice == ""){
			return "";
	}else if(itemPrice < 100){
		return itemPrice + " <img class = coin-img src = /images/copper.png>";
	}else if(itemPrice < 10000){	
		return Math.floor(itemPrice / 100) % 100 + " <img class = coin-img src = /images/silver.png> " + itemPrice % 100 + "  <img class = coin-img src = /images/copper.png>";
	}
	return Math.floor(itemPrice / 10000) % 10000 + " <img class = coin-img src = /images/gold.png> " + Math.floor(itemPrice / 100) % 100 + " <img class = coin-img src = /images/silver.png> " + itemPrice % 100 + "  <img class = coin-img src = /images/copper.png>";
}	

function tickPos(dates){
	var i;
	var ticks = [];
	for(i = 0; i < dates.length; i++){
		var date = new Date(dates[i]);
		if(date.getHours() == 0){
			ticks.push(i);
		}
	}
	
	return ticks;
}

$(document).ready(function(){
	
	var options = {
		chart: {
			renderTo: 'highcharts_container',
			height: 300
		},
		colors: ['#8AB8E6', '#E68A5C','#9EC32D','#C2A3FF'],
		title:{
			text: 'Sales Trends'
		},
		xAxis: {			
			tickPositions: [],
			categories: [],			
			labels: {
                formatter: function () {
					var date = new Date(this.value);
					if(date.getHours() == 0){
						return "<b>" + this.value.substring(0, 6) + "</b>";
					}else{
						return "";
					}
                }
            }
		},
		yAxis: [{
			title: {
				text: 'Price'
			},
			labels: {
				formatter: function() {
					return coinImages(this.value);
				},
				useHTML: true
			},
			offset: 5
		}, {
			title: {
				text: 'Volume'
			},
			opposite: true,
            gridLineWidth: 0,
            offset: 0,
		}],
		tooltip: {
			crosshairs: {
				width: 0.5,
				color: 'black'
			},
			borderWidth: 0,
            borderRadius: 0,
            shadow: false,
            formatter: function () {
                var str = '<b>' + this.x + '</b>';
				$.each(this.points, function () {
					if(this.series.name == "Lowest Price" || this.series.name == "Highest Offer"){
						str += '<br/>' + this.series.name + ': ' + coinImages(this.y);						
					}else{
						str += '<br/>' + this.series.name + ': ' + this.y;
					}
				});			              

                return str;
            },
            shared: true,
			useHTML: true
        },
		plotOptions: {
            series: {
                marker: {
                    enabled: false
                }
            }
        },
		series: []
	};
	$.getJSON('\\ajax\\highcharts.php', {id: val}, function(json) {
			options.xAxis.tickPositions = tickPos(json[0]['data']);
            options.xAxis.categories = json[0]['data']; 	
			options.series[0] = json[3];			
			options.series[0].type = 'column';
			options.series[0].yAxis = 1;
			options.series[1] = json[4];
			options.series[1].type = 'column';
			options.series[1].yAxis = 1;
            options.series[2] = json[1];
			options.series[2].type = 'line';
			options.series[3] = json[2];
			options.series[3].type = 'line';
			
            chart = new Highcharts.Chart(options);			
	});		
});