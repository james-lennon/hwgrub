<div class="ui card">
  <!-- <div class="image">
    <img src="<? echo "images/default.jpg";//echo $img_url; ?>">
  </div> -->
  <div class="content">
    <div class="header"><? echo "$first_name $last_name"; ?></div>
    <div class="meta">
      <a class="group"><? echo $phone; ?></a>
    </div>
  </div>
  <div class="extra content">
    <div class="green">
      <i class="thumbs up icon"></i>
      <? echo $good_ratings; ?>
    </div>
    <div class="red">
      <i class="thumbs down icon"></i>
      <? echo $bad_ratings; ?>
    </div>
  </div>
</div>