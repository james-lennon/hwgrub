<div class="ui comments">
	<!-- <h3 class="ui dividing header">Orders</h3> -->
	<? 
	for ($i=0; $i<count($orders); $i++) {
		// var_dump($orders[$i]);
		$this->load->view("components/order_row", array("order"=>$orders[$i]));
	}
	?>
</div>