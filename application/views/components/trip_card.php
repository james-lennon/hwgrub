<div class="ui fluid card">
	<div class="content">
      	<a class="centered right floated">
  			<i class="plus icon"></i>
  			Place Order
  		</a>
      	<div class="header"><? echo $restaurant_name; ?></div>
      	<div class="meta">Estimated time of arrival: <? echo date("g:i A", $eta); ?></div>
      	<div class="description">
        	Place order by <? echo date("g:i A", $expiration); ?>
      	</div>
    </div>
</div>
