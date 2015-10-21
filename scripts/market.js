var items = {};
var engine = {};
var cookie = [];
if($.cookie('frequented') != null){
	var cookie = JSON.parse($.cookie('frequented'));
}
var highlights = [];
	
function coinImages(itemPrice){
	if(itemPrice == "" || typeof itemPrice == null){
			return "";
	}else if(itemPrice < 100){
		return itemPrice + " <img class = coin-img src = /images/copper.png>";
	}else if(itemPrice < 10000){	
		return Math.floor(itemPrice / 100) % 100 + " <img class = coin-img src = /images/silver.png> " + itemPrice % 100 + "  <img class = coin-img src = /images/copper.png>";
	}
	return Math.floor(itemPrice / 10000) % 10000 + " <img class = coin-img src = /images/gold.png> " + Math.floor(itemPrice / 100) % 100 + " <img class = coin-img src = /images/silver.png> " + itemPrice % 100 + "  <img class = coin-img src = /images/copper.png>";
}
	
$(document).ready(function(){
	
	$.getJSON('\\ajax\\searchData.php',function(data){
		items = data;
		setup();
	});
		
	function setup(){
		engine = new Bloodhound({		
			datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			local: items,
			sorter: function(a,b){
				var str = $('#typeahead').val();
				if(a.name.toLowerCase().substring(0,str.length) == str.toLowerCase() && b.name.toLowerCase().substring(0,str.length) != str.toLowerCase()) return -1
				if(a.name.toLowerCase().substring(0,str.length) != str.toLowerCase() && b.name.toLowerCase().substring(0,str.length) == str.toLowerCase()) return 1
				if(a.name > b.name)return 1
				else if(a.name < b.name)return -1
				else return 0
			}
		});
		$('#typeahead').removeAttr('disabled');
		$('#typeahead').focus();
		$('#result-table').show();
		$('#load_img').hide();
		engine.initialize();	
		
		cookie = items.filter(function(item){
			var num = cookie.indexOf(item.id) > -1;
			if(num){
				highlights[cookie.indexOf(item.id)] = item;
				return num;
			}
		});
		
		updateList(highlights);
	}
	  
	function updateList(itemList){	
		$('#result-table tbody').find("tr:gt(0)").remove();
		$.each(itemList, function(i, item){	
			var coinsMaxOffer = coinImages(item['max_offer']);
			var coinsMinSell = coinImages(item['min_sell']);
			if(i >= 10){
				return false;
			}		
			$('#result-table > tbody:last-child').append("<tr id = row-link data-href=/item/" + item['id'] + "><td width = 10%><img class = item-img width = 38px src=/images/items/" + Math.floor(item['id']/1000) + "/" + item['id'] + ".jpg></td><td width = 40%>" + item['name'] + "</td><td width = 25%>" + coinsMaxOffer + "</td><td width = 25%>" + coinsMinSell + "</td></tr>");
		});
	}
	
	$('#input-img').on('click', function(){
		if($(this).attr('src') == "/images/xicon.png"){
			$('#typeahead').val('');
			updateList(highlights);
			$('#typeahead').focus();
			$(this).attr("src","/images/searchicon.png");
		}
	});
	
	$('body').on('click', '#row-link', function(event){	
		$.fancybox({
			href		: $(this).attr('data-href'),			
			type: 'iframe',
			fitToView	: true,
			topRatio	: '.40',
			width		: '800px',
			height		: '600px',
			autoSize	: false,
			closeClick	: false,
			openEffect	: 'none'
		});		
	});
	
	$('#typeahead').on('input', function(){		
		if($(this).val() === ''){			
			updateList(highlights);
			$('#input-img').attr("src","/images/searchicon.png");
		}else{
			engine.search($(this).val(),updateList);
			$('#input-img').attr("src","/images/xicon.png");
		}				
	});	  
});