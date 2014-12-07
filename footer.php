$(function() {
			    var splitBtn = $('.x-split-button');

			    $('button.x-button-drop').on('click', function() {
			      if (!splitBtn.hasClass('open'))
			          splitBtn.addClass('open');
			    });
			  
			    $('.x-split-button').click(function(event){
			        event.stopPropagation();
			    });

			    $('html').on('click',function() {
			       if (splitBtn.hasClass('open'))
			          splitBtn.removeClass('open');
			    });
			});

			$("#search_form").tokenInput("data/search.php", 
                {
                    theme: "mrktbeat",
                    preventDuplicates: true,
                    tokenValue: "name",
                    hintText: "Type a market name"
                });
		});
	</script>
</body>
</html>