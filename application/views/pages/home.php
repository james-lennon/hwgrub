<?
$this->load->view("components/header");
?>
<div class="modal-page" id="signin-page">
  <!-- Sign In Section -->
  <section id="food-section">
    <div class="ui three column centered doubling stackable grid">
      <div class="ui column">
        <div class="ui stacked segment signin">
          <div class="modal-content" id="signin-content">
            <form class="ui form" id="signin-form">
              <h3 class="ui red dividing header">Sign In</h3>
              <div class="field">
                <div class="ui left icon input">
                  <i class="mail icon"></i>
                  <input type="text" placeholder="Email" name="email">
                </div>
              </div>
              <div class="ui hidden divider"></div>
              <div class="field">
                <div class="ui left icon input">
                  <i class="lock icon"></i>
                  <input type="password" placeholder="Password" name = "password">
                </div>
              </div>
              <div class="ui divider"></div>
              <div class="ui buttons">
                <div class="ui red button" id="signin-btn">Sign In</div>
                <div class="or"></div>
                <div class="ui button" id="show-signup-btn">Create Account</div>
              </div>
              <div class="ui error message" id="signin-error">
                <div class="header">Try Again</div>
                <p>Invalid email and password combination.</p>
              </div>
            </form>
          </div>
          <div class="modal-content" id="signup-content">
            <form class="ui form" id="signup-form">
              <h3 class="ui red dividing header">Create Account</h3>
              <div class="field">
                <label>Email</label>
                <div class="ui left icon input">
                  <i class="mail icon"></i>
                  <input type="email" name='email' placeholder="name@example.com">
                  <div class="ui corner label">
                    <i class="asterisk icon"></i>
                  </div>
                </div>
              </div>
              <div class="field">
                <label>Name</label>
                <div class="two fields">
                  <div class="field">
                    <div class="ui input">
                      <input type="text" name="firstname" placeholder="First Name">
                      <div class="ui corner label">
                        <i class="asterisk icon"></i>
                      </div>
                    </div>
                  </div>
                  <div class="field">
                    <div class="ui input">
                      <input type="text" name="lastname" placeholder="Last Name">
                      <div class="ui corner label">
                        <i class="asterisk icon"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="field">
                <label>Phone</label>
                <div class="ui left icon input">
                  <i class="call icon"></i>
                  <input type="tel" name="phone" placeholder="(323) 555-4792" id="phone-input">
                  <div class="ui corner label">
                    <i class="asterisk icon"></i>
                  </div>
                </div>
              </div>
              <div class="ui divider"></div>
              <div class="ui red button" id="signup-btn">Submit</div>
              <div class="ui error message" id="signup-error">
                <div class="header">Error</div>
                <p>Email address already in use.</p>
              </div>
            </form>
          </div>
          <div class="modal-content" id="success-content">
            <div class="ui green dividing header">Success</div>
            <div class="ui center aligned basic segment" id="success-segment">
              <i class="massive green check circle icon" id="success-icon"></i>
              <h3 class="ui green header">Confirmation email sent!</h3>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- Information Section -->
  <!-- <section id="info-section">
    OH yea
    <div class="ui stackable doubling grid">
      <div class="three column row">
        <div class="column"><p>HEllo</p></div>
        <div class="column">abd</div>
        <div class="column">asdf</div>
      </div>
    </div>
  </section> -->
</div>
<!-- Home Page -->
<div class="modal-page" id="home-page">
  <div class="ui page grid">
    <div class="row">
      <div class="ui pointing menu">
        <a class="active item tab-btn" href="#trips_tab">
          <i class="car icon"></i> Trips
        </a>
        <a class="item tab-btn" href="#my_orders_tab">
          <i class="food icon"></i> My Orders
        </a>
        <a class="item tab-btn" href="#my_trips_tab">
          <i class="check icon"></i> My Trips
        </a>
        <a class="item tab-btn" href="#profile_tab">
          <i class="user icon"></i> Profile
        </a>
        <div class="right menu">
          <a class="item" id="logout-btn">
            <i class="sign out icon"></i> Logout
          </a>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="sixteen wide column">
        <!-- Trips Tab -->
        <div class="modal-tab" id="trips_tab">
          <h1 class="ui dividing header">Trips</h1>
          <div class="ui labeled icon button" id="trips-refresh">
            <i class="refresh icon"></i>
            Refresh
          </div>
          <div class="ui labeled right icon button" id="trips-add">
            <i class="plus icon"></i>
            I'm driving to ...
          </div>
          <div class="ui hidden divider"></div>
          <div id="trips-list">

          </div>
        </div>
        <!-- My Orders Tab -->
        <div class="modal-tab" id="my_orders_tab">
          <h1 class="ui dividing header">My Orders</h1>
          <div class="ui labeled icon button" id="orders-refresh-btn">
            <i class="refresh icon"></i>
            Refresh
          </div>
          <div class="ui hidden divider"></div>
          <div id="orders-list"></div>
        </div>
        <!-- My Trips Tab -->
        <div class="modal-tab" id="my_trips_tab">
          <h1 class="ui dividing header">My Trips</h1>
          <div class="ui labeled icon button" id="my-trips-refresh-btn">
            <i class="refresh icon"></i>
            Refresh
          </div>
          <div class="ui hidden divider"></div>
          <div id="my-trips-list"></div>
        </div>
        <!-- Profile Tab -->
        <div class="modal-tab" id="profile_tab">
          <h1 class="ui dividing header">Profile</h1>
          <div class="ui indeterminate text loader" id="profile-loader">Loading...</div>
          <div id="user-profile-content">

          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Popup Modals -->
  <div class="ui modal" id="add-order-modal">
    <i class="close icon"></i>
    <div class="header">
      Place Order For <span id="order-restaurant-name"></span>
    </div>
    <div class="content">
      <div class="image">
        <i class="food icon"></i>
      </div>
      <div class="description">
        <form class="ui form" id="order-form">
          <div class="field">
            <label>Please provide a detailed description of your order:</label>
            <textarea id="order-desc" name="order_text"></textarea>
          </div>
          <div class="ui hidden divider"></div>
          <div class="field">
            <label>Enter the extra amount of money you will pay the driver:</label>
            <input type="text" placeholder="e.g. 3" id="order-fee" name="fee">
            <!-- <div class="ui icon buttons">
              <div class="decrement ui basic red button"><i class="minus icon"></i></div>
              <div class="increment ui basic green button"><i class="plus icon"></i></div>
            </div> -->
          </div>
        </form>
      </div>
    </div>
    <div class="actions">
      <div class="two fluid ui buttons">
        <div class="ui red button close">
          <i class="remove icon"></i>
          Close
        </div>
        <div class="ui green button" id="order-submit-btn">
          <i class="checkmark icon"></i>
          Place Order
        </div>
      </div>
    </div>
  </div>
  <div class="ui modal" id="add-trip-modal">
    <i class="close icon"></i>
    <div class="header">
      Add a Trip
    </div>
    <div class="content">
      <div class="image">
        <i class="car icon"></i>
      </div>
      <div class="description">
        <p></p>
        <form class="ui form" id="trip-form">
          <div class="field">
            <label>Where are you going?</label>
            <input type="text" name="restaurant"></input>
          </div>
          <div class="ui hidden divider"></div>
          <div class="field">
            <label>How late will you accept orders?</label>
            <input type="text" placeholder="e.g. 12:30" id="trip-expiration" name="expiration_string">
          </div>
          <div class="ui hidden divider"></div>
          <div class="field">
            <label>When will you get back to school?</label>
            <input type="text" placeholder="e.g. 01:30" id="trip-eta" name="eta_string">
          </div>
        </form>
      </div>
    </div>
    <div class="actions">
      <div class="two fluid ui buttons">
        <div class="ui red button close">
          <i class="remove icon"></i>
          Close
        </div>
        <div class="ui green button" id="trip-submit-btn">
          <i class="checkmark icon"></i>
          Submit
        </div>
      </div>
    </div>
  </div>
  <div class="ui modal" id="add-rating-modal">
    <i class="close icon"></i>
    <div class="header">
      Add a Rating for <span id="rating-user-name"></span>
    </div>
    <div class="content">
      <div class="image">
        <i class="user icon"></i>
      </div>
      <div class="description">
        <form class="ui form" id="rating-form">
          <h4 class="ui dividing header">Did this User deliver food or order food?</h4>
          <div class="two fields">
            <div class="field">
              <div class="ui radio checkbox">
                <input type="radio" name="type" value="0" checked="checked">
                <label><i class="big car icon right floated"></i>Delivered</label>
              </div>
            </div>
            <div class="field">
              <div class="ui radio checkbox">
                <input type="radio" name="type" value="1">
                <label><i class="big food icon"></i>Ordered</label>
              </div>
            </div>
          </div>
          <div class="ui hidden divider"></div>
          <div class="field">
            <h4 class="ui dividing header">Describe your experience with this user:</h4>
            <textarea id="rating-desc" name="rating_text"></textarea>
          </div>
          <div class="ui hidden divider"></div>
          <div class="two fields">
            <div class="field">
              <div class="ui radio checkbox">
                <input type="radio" checked="checked" name="rating_value" value="1">
                <label><i class="big green thumbs up icon right floated"></i>Good</label>
              </div>
            </div>
            <div class="field">
              <div class="ui radio checkbox">
                <input type="radio" name="rating_value" value="0">
                <label><i class="big red thumbs down icon"></i>Bad</label>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
    <div class="actions">
      <div class="two fluid ui buttons">
        <div class="ui red button close">
          <i class="remove icon"></i>
          Close
        </div>
        <div class="ui green button" id="rating-submit-btn">
          <i class="checkmark icon"></i>
          Submit
        </div>
      </div>
    </div>
  </div>
  <div class="ui basic inverted modal" id="view-user-modal">
    <i class="close icon"></i>
    <div class="header">
      View User
    </div>
    <div class="content" id="user-content">
      <div class="filler"></div>
    </div>
  </div>

</div>
<?
$this->load->view("components/footer");
?>