<div class="ui fluid card">
	<div class="content">
      <? if(!isset($my_trip) && $user_id != $driver_id): ?>
      <a class="centered right floated place-order-btn" trip="<? echo $trip_id; ?>" name="<? echo $restaurant_name ?>">
  			<i class="plus icon"></i>
  			Place Order
  		</a>
      <? endif; ?>
    	<div class="header"><? echo $restaurant_name; ?></div>
    	<div class="meta">Estimated time of arrival: <? echo date("g:i A", $eta); ?></div>
    	<div class="description">
      	Place order by <? echo date("g:i A", $expiration); ?>
    	</div>
    </div>
</div>
