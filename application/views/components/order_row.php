<div class="comment">
  <a class="avatar">
    <img src="<? echo img_url; ?>">
  </a>
  <div class="content">
    <a class="author"><? echo "$first_name $last_name"; ?></a>
    <div class="metadata">
      <span class="date">Fee = $<? echo "$fee"; ?></span>
      <? if($state==1): ?>
      <div>
        <i class="check icon"></i>
        Accepted
      </div>
      <? endif; ?>
    </div>
    <div class="text">
      <? echo $description; ?>
    </div>
  </div>
</div>
