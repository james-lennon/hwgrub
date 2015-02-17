<div class="ui grid">
	<div class="column">
	<? 
	for ($i=0; $i<count($trips); $i++){
		$this->load->view("components/trip_card", $trips[$i]);
		if(count($orders[$i])>0){
			$this->load->view("content/trip_orders", array("orders"=>$orders[$i]));
		}
	} 
	?>
	</div>
</div>