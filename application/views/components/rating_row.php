<div class="comment">
	<div class="content">
		<div class="ui grid">
			<div class="three wide column">
				<i class="left floated 
		      	<? 
		      	if($rating->value==1){
		      		echo 'green thumbs up';
		      	}else{
		      		echo 'red thumbs down';
		      	}
		      	?> big icon"></i>
			</div>
			<div class="ten wide column">
				<? echo $rating->rating_text; ?>
			</div>
			<div class="three wide column">
				<i class="big <? echo $rating->type==0?'car':'food'; ?> icon"></i>
			</div>
    </div>
</div>
<div class="ui divider"></div>