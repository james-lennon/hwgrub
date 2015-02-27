<div class="ui centered doubling stackable equal height grid">
	<div class="centered equal height row">
		<div class="five wide column">
			<? $this->load->view("components/user_card", array("first_name"=>$user->first_name, "last_name"=>$user->last_name, "phone"=>$user->phone, "img_url"=>$user->img_url, "user_id"=>$user->user_id)); ?>
		</div>
		<div class="eleven wide column">
			<div class="ratings-list">
				<? 
				for($i=0; $i<count($ratings); $i++){
					$this->load->view("components/rating_row", array("rating"=>$ratings[$i]));
				}
				?>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$("#rating-submit-btn").click(function(){
  $("#rating-form").form("validate form");
  return false;
});
</script>