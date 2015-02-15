<div class="comment">
  <a class="avatar">
    <img src="<? echo $order->img_url; ?>">
  </a>
  <div class="content">
    <a class="author"><? echo "$order->first_name $order->last_name"; ?></a>
    <div class="metadata">
      <span class="date">Fee = $<? echo "$order->fee"; ?></span>
      <? if($order->state==1): ?>
      <div>
        <i class="check icon"></i>
        Accepted
      </div>
      <? endif; ?>
    </div>
    <div class="text">
      <? echo $order->order_text; ?>
    </div>
  </div>
</div>
