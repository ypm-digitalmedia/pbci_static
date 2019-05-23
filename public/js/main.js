var cards;

var saved = []
var savedString = "";
var savedNew = []
var savedStringNew = "";

var savedData = [];
var savedDataString = "";
var numMax = 200;
var num = numShown = 0;

$(document).ready(function() {
	
	createLocalStorage();
	getLocalStorage();


	// query saved results and replace buttons on result pages
	
	$(".saveresult").each(function() {
		
		var card = $(this).attr("card");
		if (typeof card !== 'undefined') {
			
			// alert(card);
			
			if( _.indexOf(saved,card) == -1 ) {
				// item not in array!
			} else {
				// item already in array!
				var savedHTML = "<p align='center' style='margin-top: 20px'><strong>SAVED</strong>&nbsp;<a href='javascript:void(0)' class='removesaved' card='"+card+"'>Remove</a></p>";
				$(this).replaceWith(savedHTML);
			}
			
		}
		
	});
	
	// saved results
	if( $("body").hasClass("saved-results") ) {
		
		if( saved.length == 0 ) {
			$("#content").html("<h2>No saved cards were found.</h2>")
		} else {

			// take savedString, send it to getSaved.php, return data table

			$.ajax({
				type: "POST",
				url: 'getSaved.php',
				data: {saved: savedString},
				async: false,
				success: function(response){
					console.log("response:");
					savedDataString = response;
					console.log(savedDataString);
					console.log("response, parsed:");
					savedData = JSON.parse(response);
					console.log(savedData);
				}
			});


			if( savedData.length <= numMax ) {
				num = savedData.length;
				numShown = savedData.length;
			} else {
				num = numMax;
				numShown = "many";
			}


			var savedHTML = '<div id="yw0" class="grid-view">';
				savedHTML += '	<table class="items sortable-items" cellpadding="0" cellspacing="0" style="margin-bottom: 20px"><thead><tr>';
				savedHTML += '			<th id="yw0_c0"><a class="sort-link" href="#">Remove</a></th><th id="yw0_c1"><a class="sort-link" href="#">Card</a></th><th id="yw0_c2"><a class="sort-link" href="#">Image</a></th><th id="yw0_c3"><a class="sort-link" href="#">Genus</a></th><th id="yw0_c4"><a class="sort-link" href="#">Species</a></th><th id="yw0_c5"><a class="sort-link" href="#">Age</a></th><th id="yw0_c6"><a class="sort-link" href="#">Locality</a></th><th id="yw0_c7"><a class="sort-link" href="#">Sort</a></th></tr>';
				savedHTML += '		</thead><tbody class="ui-sortable">';


			_.forEach(savedData,function(row,index) {

				var rowType = index%2==0?"odd":"even";

				savedHTML += '			<tr class="savedrow '+rowType+'"><td><a class="removesaved-table" href="#" card="'+ row['code']+'">Remove</a></td><td><a href="../card/'+ row['code']+'" title="View Info" class="viewinfo-table">'+row['code']+'</a></td><td><a href="http://images.peabody.yale.edu/ci/' + row['code'] + '.jpg" rel="prettyPhoto[]"><img src="http://images.peabody.yale.edu/ci/web/'+row['code']+'.jpg" alt="'+row['image']+'"></a></td><td>'+row['genus']+'</td><td>'+row['species']+'</td><td>'+row['age']+'</td><td>'+row['locality']+'</td><td class="sortable-column" data-id="'+ row['code'] +'" image-id="'+ row['code']+'"><img class="sortable-column-handler" style="cursor: move;" src="../public/images/icon.png" alt=""></td></tr>';

			});


				savedHTML += '		</tbody></table>';
				savedHTML += '		<div class="summary">Displaying <strong>'+num+'</strong> of <strong>'+numShown+' </strong>saved records.</div>';
				savedHTML += '</div>';

			$("#content").html(savedHTML);

			$('#pretty_photo a').attr('rel','prettyPhoto[]');
			$('a[rel^="prettyPhoto"]').prettyPhoto({'opacity':0.6,'modal':true,'theme':'facebook'});
		
			$('.sortable-clipboard-area').sortable({
					connectWith : '.sortable-items tbody'
				});

			$('#yw0 .sortable-items tbody').sortable({
				connectWith: '.sortable-clipboard-area',
				axis : 'y',
				update : function (event, ui) {
					var ids = [];
					$('#yw0 .sortable-items .sortable-column').each(function(i) {
						ids[i] = $(this).data('id');
					});

					var clipboard = [];
					$('.sortable-clipboard-area .sortable-column').each(function(i) {
						clipboard[i] = $(this).data('id');
					});

				}
			});

			$("#content").append('<p><strong><input class="btnsmall submit" name="yt0" id="createpdf" type="button" value="Create PDF"></strong></p>');

			$("#content").append('<p><strong><input class="btnsmall reset viewinfo new-search-savedpage" name="yt0" type="button" value="Search"></strong></p>');
		}
	}
	
	
	
	
	
	
	// event listeners =============================================================
	
	$("#searchAgain").click(function() {
		if( $("#findcards").is(":hidden") ) {
			$("#findcards").cardDown();
			$("#searchAgain").text("Hide Search Form");
		} else {
			$("#findcards").cardUp();
			$("#searchAgain").text("Search Again");
		}
	});
	
	
	
	$(".new-search").click(function() {
		$("#searchAgain").text("Hide Search Form");
		$("#findcards").show();
		$(document).scrollTop(0);
	});
	
	$(".new-search-savedpage").click(function() {
		document.location = "../search/";
	})
	
	
	$(".clear-button").click(function() {
		$(this).closest('form').find("input[type=text]").val("");
		$(this).closest('form').find("select").prop('selectedIndex',0);
	});
	
	
	$(".infobutton").click(function() {
		var card = $(this).attr("card");
		if (typeof card !== 'undefined') {
			document.location = "../card/" + card;
		}
	});

	$('#createpdf').click(function(){
		var data = [];
		$('.ui-sortable tr td.sortable-column').each(function(){

//			data.push($(this).attr('data-id'));
			data.push($(this).attr('image-id'));
		});
		
 	  if( data.length > 0 ) {
		  $('form').remove();
		  $('<form method="post" action="makePDF.php" target="_blank"></form>').appendTo('body');
		  $("<input type='hidden' id='carddata' name='saved' />").appendTo('form');
		  $('#carddata').val(JSON.stringify(data));
		  $('form').submit();
	  } else {
		  alert( "no saved cards!");
	  }
		return false;
	});


	
	
	// event listeners - programatically-added element trick =====================
	
	$("body").click(function (event) {
		if ($(event.target).hasClass("removesaved") ) {
			// 'remove' link on search results page
			
			getLocalStorage();
			var card = $(event.target).attr("card").toString();
			if (typeof card !== 'undefined') {
				var originalHTML = "<p><strong><input class='btnsmall saveresult' card='" + card + "' name='yt0' type='button' value='Save card'></strong></p>";
				savedNew = [];
				savedNewString = "";
				
				savedNew = _.without(saved,card);

				savedStringNew = savedNew.join("|");
				localStorage.setItem("pbciSaved",savedStringNew);
				
				console.log("card " + card + " removed.");
				
				$(event.target).parent().replaceWith(originalHTML);
				
				getLocalStorage();
			}
		} else if( $(event.target).hasClass("removesaved-table")) {
			// 'remove' link on saved results	
			
			getLocalStorage();
			var card = $(event.target).attr("card").toString();
			if( typeof card !== 'undefined') {
				
				savedNew = [];
				savedNewString = "";
				
				savedNew = _.without(saved,card);
				savedData = _.reject(savedData,function(o) {
					return o['code'] == card;
				});
				console.log(savedData);
				
				savedStringNew = savedNew.join("|");
				localStorage.setItem("pbciSaved",savedStringNew);
				
				if( savedData.length <= numMax ) {
					num = savedData.length;
					numShown = savedData.length;
				} else {
					num = numMax;
					numShown = "many";
				}
				
				$(".summary").html('Displaying <strong>'+num+'</strong> of <strong>'+numShown+' </strong>saved records.</div>');
				
				$(event.target).closest('.savedrow').remove();
			
				console.log("card " + card + " removed.");
				getLocalStorage();
			}
		} else if( $(event.target).hasClass("saveresult")) {
			
			var card = $(event.target).attr("card").toString();
			if (typeof card !== 'undefined') {
				getLocalStorage();
				savedNew = [];
				savedNewString = "";
				savedNew = saved;

				savedNew.push(card.toString());
				savedNew = _.uniq(savedNew);
				savedStringNew = savedNew.join("|");

				localStorage.setItem("pbciSaved",savedStringNew);

				console.log("card " + card + " added.");

				var savedHTML = "<p align='center' style='margin-top: 20px'><strong>SAVED</strong>&nbsp;<a href='javascript:void(0)' class='removesaved' card='"+card+"'>Remove</a></p>";
				$(event.target).replaceWith(savedHTML);

				getLocalStorage();
			}


		}
	});
	
	
});




function createLocalStorage() {
	console.log('initializing local storage.\n\n');
	if (typeof(Storage) == "undefined") {
	  // Sorry! No Web Storage support..
		console.error("localStorage not supported.  Results may not be saved.")
	} else {
	  // Code for localStorage/sessionStorage.
		
		if( !localStorage.getItem('pbciSaved') || localStorage.getItem('pbciSaved').length == 0 ) {
			console.warn("localStorage variable 'pbciSaved' is empty.");
			localStorage.setItem('pbciSaved',"");
		}
	}
}

function getLocalStorage () {
	// saved results => LocalStorage ===============================================
	console.log('initializing local storage.\n\n');
	if (typeof(Storage) == "undefined") {
	  // Sorry! No Web Storage support..
		console.error("localStorage not supported.  Results may not be saved.")
	} else {
	  // Code for localStorage/sessionStorage.
		
		if( localStorage.getItem('pbciSaved') ) {

			
			savedString = localStorage.getItem('pbciSaved');
			saved = savedString.split("|");
			console.log("SAVED: \n");
			console.log(savedString);
			console.log(saved);
			console.log("\n");
		}
	}
}