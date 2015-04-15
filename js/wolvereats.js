var base_url = "http://localhost:8888/";
var user_id = -1, trip_id = -1, rating_user_id = -1;

$(document).ready(function(){
  $("#phone-input").mask("(999) 999-9999");
  $("#trip-expiration").mask("99:99");
  $("#trip-eta").mask("99:99");

  $("#signin-error").hide();
  $("#signup-error").hide();

  showModalPage("signin-page");
  showModal("signin-content");
  showModalTab("#trips_tab");
  check_login();
});

function check_login(){
  $.post(base_url+"users/login", {}, function(data,status){
    // console.log("login check: "+data);
    var dataObj = $.parseJSON(data);
    if(!("error" in dataObj)){
      onSignInSuccess(dataObj);
    }
  });
}

function showModal(name){
  $(".modal-content").hide();
  $("#"+name).fadeIn();
}

function showModalPage(name){
  $(".modal-page").hide();
  $("#"+name).fadeIn();
}

function onSignInSubmit(){
  $("#signin-form").addClass("loading");
  var values = $("#signin-form").form("get values");
  values.no_hash = 1;
  $.post(base_url+"users/login",values,function(data, status){
    // console.log(data);
    $("#signin-form").removeClass("loading");
    $("#signin-form").form("clear");

    var dataObj = $.parseJSON(data);
    if("error" in dataObj){
      console.log("Error: "+dataObj.error);
      $("#signin-error").show();
    }else{
      onSignInSuccess(dataObj);
    }
  });
}

function onSignUpSubmit(){
  $("#signup-form").addClass("loading");
  var values = $("#signup-form").form("get values");
  // console.log(values);
  $.post(base_url+"users/add",values,function(data,status){
    // console.log(data);
    $("#signup-form").removeClass("loading");
    $("#signup-form").form("clear");

    var dataObj = $.parseJSON(data);
    if("error" in dataObj){
      console.log("Error: "+dataObj.error);
      $("#signup-error").show();
    }else{
      onSignUpSuccess();
      $("#success-content").after("<p>"+dataObj.url+"</p>");
    }
  });
}

function onSignUpSuccess(){
  showModal("success-content");
}

function onSignInSuccess(result){
  user_id = result["user_id"];
  console.log("Signed in: "+user_id);
  showModalPage("home-page");
  refreshAll();

  loadProfile(user_id);
}

$("#signin-btn").click(function(){
  $("#signin-form").form("validate form");
});

$("#show-signup-btn").click(function(){
  showModal("signup-content");
});

$("#signup-btn").click(function(){
  $("#signup-form").form("validate form");
});

//Home Page

function showModalTab(name){
  $(".modal-tab").hide();
  $(name).show();
}

$(".tab-btn").click(function(){
  $(".tab-btn").removeClass("active");
  $(this).addClass("active");
  var tab_name = $(this).attr("href");
  showModalTab(tab_name);
  return false;
});

$("#logout-btn").click(function(){
  showModalPage("signin-page");
  $.post(base_url+"users/logout",{});
});

function refreshAll(){
  loadTrips();
  loadMyTrips();
  loadMyOrders();
}

$("#trips-refresh").click(function(){
  refreshAll();
});

$("#trips-add").click(function(){
  $("#add-trip-modal").modal("show");
});

function loadTrips(){
  $("#trips-refresh").addClass("loading");
  $.post(base_url+"trips/get_active_trips_content", {}, function(data, status){
    $("#trips-list").html(data);

    setUpLinks();
    
    $("#trips-refresh").removeClass("loading");
  });
}

function setUpLinks(){
  $(".view-user").click(function(){
    $("#view-user-modal").modal("show");
    var view_user_id = $(this).attr("user-id");
    $.post(base_url+"users/get_user_content", {user_id:view_user_id}, function(data, status){
      $("#user-content").html(data);
    });
  });
  $(".place-order-btn").click(function(){
    trip_id = $(this).attr("trip");
    console.log("trip_id="+trip_id);
    $("#add-order-modal").modal("show");
    $("#order-form").form("clear");
    $("#order-restaurant-name").html($(this).attr("name"));
  });
  $(".add-rating-btn").click(function(){
    rating_user_id = $(this).attr("user-id");
    $("#add-rating-modal").modal("show");
    $("#rating-form").form("clear");
    $("#rating-user-name").html($(this).attr("name"));
  });
  $(".accept-order-btn").click(function(){
    var accept_order_id = $(this).attr("order-id");
    $.post(base_url+"orders/accept_order", {order_id:accept_order_id}, function(data, status){
      console.log("Accept order response: "+data);
      refreshAll();
    });
  });
}

//My Orders

$("#orders-refresh-btn").click(function(){
  refreshAll();
});

function loadMyOrders(){
  $("#orders-refresh-btn").addClass("loading");

  $.post(base_url+"orders/get_orders_content", {}, function(data, status){
    console.log(data);
    $("#orders-list").html(data);
    $("#orders-refresh-btn").removeClass("loading");
  });
}

//My Trips

$("#my-trips-refresh-btn").click(function(){
  refreshAll();
});

function loadMyTrips(){
  $("#my-trips-refresh-btn").addClass("loading");
  $.post(base_url+"trips/get_user_trips_content", {}, function(data,status){

    $("#my-trips-list").html(data);
    $("#my-trips-refresh-btn").removeClass("loading");

    setUpLinks();
  });
}

//Profile

function loadProfile(id){
  $.post(base_url+"users/get_user_content", {user_id:id}, function(data, status){
    // console.log(data);
    $("#user-profile-content").html(data);
    setUpLinks();
  });
}

// Order Modal

$("#add-order-modal").modal({
  selector:
  {
    close: ".close"
  }
});

$("#order-submit-btn").click(function(){
  $("#order-form").form("validate form");
  return false;
});

function onOrderSubmit(){
  $("#order-submit-btn").addClass("loading");

  // var desc = $("#order-desc").val(), fee = $("#order-fee").val();
  var values = $("#order-form").form("get values");
  values.trip_id = trip_id;
  $.post(base_url+"orders/place_order",values,function(data, status){
    console.log("place order response: "+data);

    $("#order-submit-btn").removeClass("loading");
    $("#order-form").form("clear");
    $("#add-order-modal").modal("hide");
    refreshAll();
  });
}

//Trip Modal

$("#add-trip-modal").modal({
  selector:
  {
    close: ".close"
  }
});

function stringToTimestamp(time){
  var comps = time.split(":");
  var oDate = new Date();
  oDate.setHours(parseInt(comps[0], 10), parseInt(comps[1], 10), 0, 0);
  var sTimestamp = oDate.getTime();
  return sTimestamp/1000;
}

function onTripSubmit(){
  $("#trip-submit-btn").addClass("loading");

  var values = $("#trip-form").form("get values");
  values.eta = stringToTimestamp(values.eta_string);
  values.expiration = stringToTimestamp(values.expiration_string);
  console.log(values.expiration);

  $.post(base_url+"trips/add_trip", values, function(data, status){
    console.log("Trip response: "+data);
    var dataObj = $.parseJSON(data);

    $("#trip-submit-btn").removeClass("loading");
    $("#trip-form").form("clear");
    $("#add-trip-modal").modal("hide");
    refreshAll();
  });
}

$("#trip-submit-btn").click(function(){
  $("#trip-form").form("validate form");
  return false;
});

//Rating Modal

$("#add-rating-modal").modal({
  selector:
  {
    close: ".close"
  }
});

$("#rating-submit-btn").click(function(){
  $("#rating-form").form("validate form");
  return false;
});

function onRatingSubmit(){
  console.log("rating submitted");
  $("#rating-submit-btn").addClass("loading");

  var values = $("#rating-form").form("get values");
  values.user_id = rating_user_id;
  console.log(values);

  $.post(base_url+"ratings/add_rating", values, function(data, status){
    console.log("Rating response: "+data);
    var dataObj = $.parseJSON(data);

    $("#rating-submit-btn").removeClass("loading");
    $("#rating-form").form("clear");
    $("#add-rating-modal").modal("hide");
  });
}

//Form Validation

$('#signin-form')
.form({
  email: {
    identifier : 'email',
    rules: [
      {
        type   : 'empty',
        prompt : 'Please enter an email'
      },
      {
        type: 'email',
        prompt: 'Please enter a valid email'
      }
    ]
  },
  password: {
    identifier : 'password',
    rules: [
      {
        type   : 'empty',
        prompt : 'Please enter a password'
      }
    ]
  }
},
{
  onSuccess: onSignInSubmit,
  inline: true,
  keyboardShortcuts: false
});
$('#signup-form')
.form({
  email: {
    identifier : 'email',
    rules: [
      {
        type   : 'empty',
        prompt : 'Please enter an email'
      },
      {
        type: 'email',
        prompt: 'Please enter a valid email'
      }
    ]
  },
  firstname: {
    identifier : 'firstname',
    rules: [
      {
        type   : 'empty',
        prompt : 'Please enter your first name'
      }
    ]
  },
  lastname: {
    identifier : 'lastname',
    rules: [
      {
        type   : 'empty',
        prompt : 'Please enter your last name'
      }
    ]
  },
  phone: {
    identifier : 'phone',
    rules: [
      {
        type   : 'empty',
        prompt : 'Please enter your phone number'
      }
    ]
  }
},
{
  onSuccess: onSignUpSubmit,
  inline: true,
  keyboardShortcuts: false
});
$("#order-form").form({
  order_text: {
    identifier : 'order_text',
    rules: [
      {
        type   : 'empty',
        prompt : 'Please enter a description of your order'
      }
    ]
  },
  fee: {
    identifier : 'fee',
    rules: [
      {
        type   : 'empty',
        prompt : 'Please enter a fee'
      },
      {
        type: "integer",
        prompt: "This must be a valid integer"
      }
    ]
  }
},
{
  onSuccess: onOrderSubmit,
  inline: true,
  keyboardShortcuts: false
});
$("#trip-form").form({
  restaurant: {
    identifier : 'restaurant',
    rules: [
      {
        type   : 'empty',
        prompt : 'Please enter a restaurant name'
      }
    ]
  },
  expiration_string: {
    identifier : 'expiration_string',
    rules: [
      {
        type   : 'empty',
        prompt : 'Please enter an expiration time'
      }
    ]
  },
  eta_string: {
    identifier : 'eta_string',
    rules: [
      {
        type   : 'empty',
        prompt : 'Please enter an arrival time'
      }
    ]
  }
},
{
  onSuccess: onTripSubmit,
  inline: true,
  keyboardShortcuts: false
});
$("#rating-form").form({
  type: {
    identifier : 'type',
    rules: [
      {
        type   : 'empty',
        prompt : 'Please enter choose a rating type'
      }
    ]
  },
  rating_text: {
    identifier : 'rating_text',
    rules: [
      {
        type   : 'empty',
        prompt : 'Please enter a description'
      }
    ]
  },
  rating_value: {
    identifier : 'rating_value',
    rules: [
      {
        type   : 'empty',
        prompt : 'Please choose good or bad'
      }
    ]
  },
},
{
  onSuccess: onRatingSubmit,
  inline: true,
  keyboardShortcuts: false
});