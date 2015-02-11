<div class="ui grid">
	<? 
	foreach ($trips as $trip){
		$this->load->view("components/trip_card", $trip);
	} 

	?>
</div>