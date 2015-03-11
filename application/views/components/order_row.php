<div class="comment">
  <a class="avatar">
    <img src="<? echo $order->img_url; ?>">
  </a>
  <div class="content">
    <a class="author view-user" user-id="<? echo $order->customer_id; ?>"><? echo "$order->first_name $order->last_name"; ?></a>
    <div class="metadata">
      <span class="date">Fee = $<? echo "$order->fee"; ?></span>
      <? if($order->state==1): ?>
        <div class="">
          <i class="green check icon">Accepted</i>
        </div>
      <? endif; ?>
    </div>
    <div class="text">
      <? echo $order->order_text; ?>
      <? if(isset($my_trip) && $order->state==0 && !$expired): ?>
        <a class="accept-order-btn" order-id="<? echo $order->order_id; ?>">
          <i class="big green check icon"></i>
        </a>
      <? endif; ?>
    </div>
  </div>
</div>
