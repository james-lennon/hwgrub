<div class="ui centered doubling grid">
	<div class="centered six wide column">
		<? $this->load->view("components/user_card", array("first_name"=>$user->first_name, "last_name"=>$user->last_name, "phone"=>$user->phone, "img_url"=>$user->img_url, "good_ratings"=>22, "bad_ratings"=>3)); ?>
	</div>
</div>