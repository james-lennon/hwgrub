<div class="ui comments">
	<h3 class="ui dividing header">Orders</h3>
	<? 
	foreach ($orders as $order) {
		// var_dump($order);
		$this->load->view("components/order_row", $order);
	}
	?>
</div>