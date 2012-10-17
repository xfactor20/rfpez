<?php if ($finish_signup): ?>
  <?php Section::inject('page_title', "Create password for $user->email") ?>
<?php else: ?>
  <?php Section::inject('page_title', "Reset password for $user->email") ?>
<?php endif; ?>
<form class="form-horizontal" action="<?php echo Jade\Dumper::_text(route('reset_password', array($user->reset_password_token))); ?>" method="POST">
  <div class="control-group">
    <label class="control-label">New Password</label>
    <div class="controls">
      <input type="password" name="password" />
    </div>
  </div>
  <div class="form-actions">
    <button class="btn btn-primary" type="submit">Submit</button>
  </div>
</form>