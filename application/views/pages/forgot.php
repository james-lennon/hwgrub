<?
$this->load->view("components/header");
?>
<br><br>
<div class="ui three column centered stackable doubling grid">
	<div class="ui column">
		<div class="ui stacked segment">
			<div class="ui form">
				<?php echo form_open(uri_string()); ?>
					<div class="field">
						<input placeholder="Password" name="password" type="password" autofocus>
					</div>
					<div class="field">
						<input placeholder="Confirm Password" name="confirm-password" type="password" value="">
					</div>
					<input type="submit" value="Set Password" class="ui green submit button">
				</form>
			</div>
		</div>
	</div>
</div>

<?
$this->load->view("components/footer");
?>
