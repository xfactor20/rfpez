<?php Section::inject('page_title', "$project->title") ?>
<?php Section::inject('active_subnav', "view") ?>
<?php if ($project->is_mine()): ?>
  <?php Section::inject('no_page_header', true) ?>
  <?php echo Jade\Dumper::_html(View::make('projects.partials.toolbar')->with('project', $project)); ?>
  <?php echo Jade\Dumper::_html(View::make('projects.partials.answer_question_form')); ?>
<?php endif; ?>
<div class="row">
  <div class="span6">
    <?php echo Jade\Dumper::_html($project->body); ?>
  </div>
  <div class="span5 offset1">
    <h4>Proposals due <?php echo Jade\Dumper::_text(RelativeTime::format($project->proposals_due_at)); ?></h4>
    <?php if (Auth::vendor()): ?>
      <?php if ($bid = $project->my_current_bid()): ?>
        <a class="btn btn-small btn-primary" href="<?php echo Jade\Dumper::_text(route('bid', array($project->id, $bid->id))); ?>">View my bid</a>
      <?php elseif ($bid = $project->my_current_bid_draft()): ?>
        <a class="btn btn-success" href="<?php echo Jade\Dumper::_text(route('new_bids', array($project->id))); ?>">Continue Writing Bid</a>
      <?php else: ?>
        <a class="btn btn-success" href="<?php echo Jade\Dumper::_text(route('new_bids', array($project->id))); ?>">Bid on this Contract</a>
      <?php endif; ?>
    <?php endif; ?>
    <div class="no-auth-only">
      <a class="btn btn-success" href="#signinModal" data-toggle="modal">Sign in</a> to bid on this project.
    </div>
    <hr />
    <div class="q-and-a">
      <h4>Q &amp; A</h4>
      <div class="questions">
        <?php if ($project->questions): ?>
          <?php foreach($project->questions as $question): ?>
            <?php echo Jade\Dumper::_html(View::make('projects.partials.question')->with('question', $question)); ?>
          <?php endforeach; ?>
        <?php else: ?>
          <p class="no-questions-asked">No questions have been asked.</p>
        <?php endif; ?>
      </div>
      <div class="vendor-only">
        <h4>Ask a question about this project</h4>
        <form id="ask-question-form" action="<?php echo Jade\Dumper::_text(route('questions')); ?>" method="post">
          <input type="hidden" name="project_id" value="<?php echo Jade\Dumper::_text($project->id); ?>" />
          <textarea name="question" placeholder="Type your question here"></textarea>
          <button class="btn btn-primary btn-small" data-loading-text="Sending...">Submit Question</button>
        </form>
      </div>
    </div>
  </div>
</div>